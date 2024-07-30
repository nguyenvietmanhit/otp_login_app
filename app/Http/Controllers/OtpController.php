<?php

namespace App\Http\Controllers;

use App\Models\CustomerOtp;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => [
                'required',
                'regex:/^(0[1-9][0-9]{8,9})$/',
            ]
        ], [
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại Việt Nam hợp lệ.',
        ]);

        $ip_address = $request->ip();
        // Check rate-limiting
        $limit = 115; // Max OPT requests per hour
        $count = CustomerOtp::where('ip_address', $ip_address)
            ->where('created_at', '>=', Carbon::now()->subMinutes(30))
            ->count();

        if ($count >= $limit) {
            return redirect()->back()->withErrors(['message' => "Bạn đã thực hiện quá $limit đăng nhập cho phép trong 30p. Vui lòng thử lại sau 30p nữa"]);
        }

        $phone = $request->phone;

        // Check trong bảng customer_otps xem đã tồn tại OTP hay chưa
        $customer_otp = CustomerOtp::where('phone', $phone)->first();
        if (!$customer_otp) {
            // Giả lập otp, sau cần lấy từ API
            $otp = rand(10000, 99999);
            CustomerOtp::create([
                'phone' => $phone,
                'otp' => $otp,
                'ip_address' => $ip_address,
                'expires_at' => Carbon::now()->addMinutes(30)
            ]);
        }

        session()->put('phone', $phone);
        return redirect()->route('otp.verify');
    }

    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        // Kiểm tra nếu ít nhất một OTP không được nhập
        $allFieldsFilled = !collect($request->input('otps'))->contains(function($field) {
            return empty($field) || !is_numeric($field) || strlen($field) > 1;
        });

        if (!$allFieldsFilled) {
            return back()->withErrors(['otps' => 'Phải nhập OTP và các input phải phải là số có duy nhất 1 ký tự']);
        }

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer) {
            return redirect()->back()->withErrors(['email' => 'Email not found.']);
        }

        $otp = CustomerOtp::where('user_id', $customer->id)
            ->where('otp', $request->otp)
            ->where('expires_at', '>=', Carbon::now())
            ->first();
        if ($otp) {
            Auth::login($customer);
            $otp->delete();
            session()->forget('phone');
            return redirect()->route('home');
        }

        return redirect()->back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }
}
