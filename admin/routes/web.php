<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PopupController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\ResourceMenuController;
use App\Http\Controllers\TechBlogController;
use App\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| Narnia Labs 어드민 라우트
|--------------------------------------------------------------------------
*/

// 정적 파일 서빙 (CSS/JS — .htaccess가 /admin/* 전체를 Laravel로 보내므로 여기서 파일 응답)
Route::get('/admin/css/{file}', function ($file) {
    $fullPath = public_path('css/' . $file);
    if (!file_exists($fullPath)) abort(404);
    return response()->file($fullPath, ['Content-Type' => 'text/css']);
});

Route::get('/admin/js/{file}', function ($file) {
    $fullPath = public_path('js/' . $file);
    if (!file_exists($fullPath)) abort(404);
    return response()->file($fullPath, ['Content-Type' => 'application/javascript']);
});

// 임시 opcache 클리어 (배포 후 삭제)
Route::get('/admin/clear-all-cache', function () {
    if (function_exists('opcache_reset')) opcache_reset();
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('view:clear');
    return 'ALL CACHE CLEARED OK';
});

// 마이그레이션 실행 (필요 시 사용)
Route::get('/admin/run-migrations', function () {
    try {
        \Artisan::call('migrate', ['--force' => true]);
        return '<pre>마이그레이션 완료:\n' . \Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return '<pre>오류: ' . $e->getMessage() . '</pre>';
    }
});

// SEO 데이터 자동 채우기 (임시)
Route::get('/run-seo-autofill', function() {
    require base_path('temp_seo_update.php');
    return "SEO 전용 데이터 자동 채우기 완료!";
});

// ── Public API (인증 불필요) ──────────────────────────────
Route::get('/admin/api/events', [EventController::class, 'apiIndex']);
Route::get('/admin/api/case-studies', [CaseStudyController::class, 'apiIndex']);
Route::get('/admin/api/education', [EducationController::class, 'apiIndex']);
Route::get('/admin/api/education/{id}', [EducationController::class, 'apiShow']);
Route::post('/admin/api/education/{id}/apply', [EducationController::class, 'apiApply']);
Route::options('/admin/api/education/{id}/apply', [EducationController::class, 'apiApply']);
Route::get('/admin/api/publications', [PublicationController::class, 'apiIndex']);
Route::get('/admin/api/resource-menus', [ResourceMenuController::class, 'apiIndex']);
Route::get('/admin/api/news', [NewsController::class, 'apiIndex']);
Route::get('/admin/api/news-latest', [NewsController::class, 'apiLatest']);
Route::get('/admin/api/teams', [TeamController::class, 'apiIndex']);
Route::get('/admin/api/seo', [SeoController::class, 'apiGet']);
Route::get('/admin/api/popups', [PopupController::class, 'apiIndex']);
Route::get('/admin/api/clients', [ClientController::class, 'apiIndex']);
Route::get('/admin/api/tech-blogs', [TechBlogController::class, 'apiIndex']);
Route::post('/admin/api/tech-blogs/upload-image', [TechBlogController::class, 'uploadEditorImage']);
Route::get('/admin/sitemap.xml', [SeoController::class, 'sitemap']);

// 문의 접수 (공개 API)
Route::post('/admin/api/inquiries', [InquiryController::class, 'apiStore']);
Route::options('/admin/api/inquiries', [InquiryController::class, 'apiOptions']);




Route::prefix('admin')->group(function () {

    // 로그인
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // 루트 → 대시보드로 리다이렉트
    Route::get('/', function () {
        return auth()->check()
            ? redirect()->route('dashboard')
            : redirect()->route('login');
    });

    // 인증 필요 어드민 라우트
    Route::middleware('auth')->group(function () {

        // 대시보드
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // 컨텐츠 관리
        Route::resource('news', NewsController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::patch('news/{news}/toggle-featured', [NewsController::class, 'toggleFeatured'])->name('news.toggleFeatured');
        Route::resource('events', EventController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('popup', PopupController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('team', TeamController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('case-studies', CaseStudyController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::patch('case-studies/{caseStudy}/toggle-featured', [CaseStudyController::class, 'toggleFeatured'])->name('case-studies.toggleFeatured');

        // 교육
        Route::resource('education', EducationController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('publications', PublicationController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('tech-blogs', TechBlogController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('applicants', ApplicantController::class)->only(['index', 'show', 'destroy']);

        // Resource 헤더 메뉴 관리
        Route::resource('resource-menus', ResourceMenuController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // 클라이언트 로고 관리
        Route::resource('clients', ClientController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // 상담 관리
        Route::resource('inquiry', InquiryController::class)->only(['index', 'show', 'destroy']);

        // SEO 관리
        Route::get('seo', [SeoController::class, 'index'])->name('seo.index');
        Route::get('seo/{key}/edit', [SeoController::class, 'edit'])->name('seo.edit');
        Route::put('seo/{key}', [SeoController::class, 'update'])->name('seo.update');
        Route::resource('members', MemberController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });

});

