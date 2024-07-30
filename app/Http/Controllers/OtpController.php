<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
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

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email not found']);
        }

        $otp = rand(10000, 99999);

        Otp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10)
        ]);

        return redirect()->route('otp.verify')->with('email', $request->email);
    }

    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email not found.']);
        }

        $otp = Otp::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('expires_at', '>=', Carbon::now())
            ->first();
        if ($otp) {
            Auth::login($user);
            $otp->delete();
            return redirect()->route('home');
        }

        return redirect()->back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }
}
