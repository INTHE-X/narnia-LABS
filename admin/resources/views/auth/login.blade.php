<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 로그인 — NARNIA LABS</title>
    <meta name="description" content="나니아랩스 관리자 로그인 페이지입니다.">
    <link rel="stylesheet" href="https://narnialabs.mycafe24.com/admin/css/admin.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Pretendard', sans-serif;
            background-color: #fff;
            overflow: hidden;
        }

        .login-wrapper {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* 왼쪽 로그인 영역 */
        .login-side-form {
            flex: 3;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 4%;
            background: #fff;
            z-index: 10;
        }

        @media (max-width: 1024px) {
            .login-side-form {
                flex: 1;
                padding: 0 40px;
            }
            .login-side-bg {
                display: none;
            }
        }

        .login-brand-logo {
            margin-bottom: 2rem;
        }

        .login-brand-logo img {
            height: 32px;
            width: auto;
        }

        .login-welcome {
            margin-bottom: 3.5rem;
        }

        .login-welcome h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.5rem 0;
            display: none; /* 이미지처럼 로고가 아이덴티티를 대신함 */
        }

        .login-welcome p {
            font-size: 0.95rem;
            color: #64748b;
            margin: 0;
        }

        /* 폼 스타일 */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.6rem;
        }

        .form-control {
            width: 100%;
            height: 52px;
            background: #f2f2f2; /* 푸른 기를 완전히 뺀 중립 회색 */
            border: none;
            border-radius: 8px;
            padding: 0 16px;
            font-size: 1rem;
            color: #1e293b;
            box-sizing: border-box;
            transition: all 0.2s;
        }

        /* 브라우저 자동완성 시 푸른 배경 방지 */
        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover, 
        .form-control:-webkit-autofill:focus, 
        .form-control:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #f2f2f2 inset !important;
            -webkit-text-fill-color: #1e293b !important;
        }

        .form-control:focus {
            outline: none;
            background: #e2e8f0;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            margin-bottom: 2rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .form-check-label {
            font-size: 0.85rem;
            color: #64748b;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            height: 56px;
            background: #222; /* 이미지처럼 짙은 배경 */
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-login:hover {
            background: #000;
        }

        /* 오류 메시지 */
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            border: 1px solid #fee2e2;
        }

        /* 오른쪽 배경 영역 */
        .login-side-bg {
            flex: 7;
            background-color: #000;
            background-image: url('https://narnialabs.mycafe24.com/assets/img/sub/about_bg.png');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .login-side-bg::after {
            content: "";
            position: absolute;
            bottom: 30px;
            left: 40px;
            color: rgba(255,255,255,0.4);
            font-size: 0.75rem;
            content: "Copyright © narnia.ai. All rights reserved.";
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <!-- 왼쪽 폼 영역 -->
    <div class="login-side-form">
        <div class="login-brand-logo" style="display: flex; align-items: center; gap: 10px;">
            <img src="https://narnialabs.mycafe24.com/assets/img/common/logo_symbol_color.svg" alt="Narnia Labs Symbol" style="height: 32px; width: auto;">
            <img src="https://narnialabs.mycafe24.com/assets/img/common/NARNIA%20LABS.svg" alt="Narnia Labs Text" style="height: 20px; width: auto; margin-top: 2px;">
        </div>
        
        <div class="login-welcome">
            <p>로그인 후 이용 가능합니다.</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">아이디</label>
                <input
                    class="form-control"
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="아이디를 입력해주세요"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label class="form-label">비밀번호</label>
                <input
                    class="form-control"
                    type="password"
                    name="password"
                    placeholder="••••••••••"
                    required
                >
            </div>

            <div class="form-check">
                <input
                    id="remember"
                    class="form-check-input"
                    type="checkbox"
                    name="remember"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <label class="form-check-label" for="remember">로그인 상태 유지</label>
            </div>

            <button class="btn-login" type="submit">로그인</button>

            {{-- reCAPTCHA 토큰 전송용 숨겨진 필드 --}}
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

            {{-- 구글 정책상 필수 안내 문구 --}}
            <div class="recaptcha-terms">
                This site is protected by reCAPTCHA and the Google <br>
                <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a> and
                <a href="https://policies.google.com/terms" target="_blank">Terms of Service</a> apply.
            </div>
        </form>
    </div>

    <!-- 오른쪽 배경 영역 -->
    <div class="login-side-bg"></div>
</div>

@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
<style>
    .grecaptcha-badge { visibility: hidden !important; }
    .recaptcha-terms {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 1.5rem;
        text-align: center;
        line-height: 1.4;
    }
    .recaptcha-terms a {
        color: #64748b;
        text-decoration: underline;
    }
</style>
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'login'}).then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
                form.submit();
            });
        });
    });
</script>
@endif

</body>
</html>
