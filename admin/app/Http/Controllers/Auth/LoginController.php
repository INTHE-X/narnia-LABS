<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * 로그인 페이지 표시
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * 로그인 처리
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => '아이디를 입력해주세요.',
            'password.required' => '비밀번호를 입력해주세요.',
        ]);

        // reCAPTCHA 검증 (키가 설정된 경우에만 실행)
        $secretKey = config('services.recaptcha.secret_key');
        if ($secretKey) {
            $recaptchaToken = $request->input('g-recaptcha-response');
            if (!$recaptchaToken) {
                return back()
                    ->withInput($request->only('username', 'remember'))
                    ->withErrors(['username' => '보안 검증 정보가 누락되었습니다. 페이지를 새로고침 해주세요.']);
            }

            $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $recaptchaToken,
                'remoteip' => $request->ip(),
            ]);

            if (!$response->json('success') || $response->json('score') < 0.5) {
                return back()
                    ->withInput($request->only('username', 'remember'))
                    ->withErrors(['username' => '자동입력 방지 검증에 실패했습니다. (봇 의심)']);
            }
        }

        $credentials = [
            'email'    => $request->username, // email 필드로 로그인
            'password' => $request->password,
        ];

        // username / email / name 모두로 로그인 가능
        $user = \App\Models\User::where('username', $request->username)
                    ->orWhere('email', $request->username)
                    ->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withInput($request->only('username', 'remember'))
            ->withErrors(['username' => '아이디 또는 비밀번호가 올바르지 않습니다.']);
    }

    /**
     * 로그아웃 처리
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
