<?php

namespace App\Http\Controllers;

use App\Models\SeoSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    // 페이지 목록 정의
    public static function pages(): array
    {
        return [
            ['key' => 'home',           'name' => '메인',              'url' => '/'],
            ['key' => 'about',          'name' => '회사 소개 (About)',   'url' => '/kor/html/company/about.html'],
            ['key' => 'team',           'name' => '팀 (Team)',           'url' => '/kor/html/company/team.html'],
            ['key' => 'news',           'name' => '뉴스 (News)',         'url' => '/kor/html/company/news.html'],
            ['key' => 'aslanx',         'name' => 'AslanX',             'url' => '/kor/html/aslanX/aslanX.html'],
            ['key' => 'case_studies',   'name' => '케이스스터디',          'url' => '/kor/html/resource/caseStudies.html'],
            ['key' => 'education',      'name' => '에듀케이션',            'url' => '/kor/html/resource/education.html'],
            ['key' => 'publication',    'name' => '퍼블리케이션',          'url' => '/kor/html/resource/publication.html'],
            ['key' => 'events',         'name' => '이벤트',              'url' => '/kor/html/resource/events.html'],
            ['key' => 'deep_gen',       'name' => 'Deep Generative Design', 'url' => '/kor/html/solution/deepGenerativeDesign.html'],
            ['key' => 'evaluation',     'name' => 'Evaluation',          'url' => '/kor/html/solution/evaluation.html'],
            ['key' => 'generation',     'name' => 'Generation',          'url' => '/kor/html/solution/generation.html'],
            ['key' => 'optimization',   'name' => 'Optimization',        'url' => '/kor/html/solution/optimization.html'],
            ['key' => 'recommendation', 'name' => 'Recommendation',      'url' => '/kor/html/solution/recommendation.html'],
            ['key' => 'global',         'name' => '전역 설정 (공통 스크립트)', 'url' => 'ALL PAGES'],
        ];
    }

    public function index()
    {
        // 자동 데이터 채우기 (비어있는 경우에만)
        $defaults = [
            'home' => ['meta_title' => '나니아랩스 (Narnia Labs) - 제조 산업을 위한 생성형 AI 솔루션', 'meta_description' => '나니아랩스는 딥 제너레이티브 디자인 기술을 통해 제품 설계의 혁신을 제안하는 AI 전문 기업입니다.'],
            'about' => ['meta_title' => '회사 소개 | 나니아랩스 (Narnia Labs)', 'meta_description' => '나니아랩스의 미션과 비전을 소개합니다. 기술로 설계의 가치를 높입니다.'],
            'aslanx' => ['meta_title' => 'AslanX - AI 설계 최적화 플랫폼 | 나니아랩스', 'meta_description' => '데이터 기반의 설계 생성 및 평가를 지원하는 AslanX 솔루션을 만나보세요.'],
            'deep_gen' => ['meta_title' => 'Deep Generative Design | 나니아랩스 기술', 'meta_description' => '나니아랩스만의 독보적인 딥 제너레이티브 디자인 기술을 확인하세요.'],
            'case_studies' => ['meta_title' => '케이스스터디 - 성공 사례 | 나니아랩스', 'meta_description' => '현대자동차 등 파트너사들과 함께한 실질적인 프로젝트 성과를 확인하세요.'],
            'news' => ['meta_title' => '새로운 소식 (News) | 나니아랩스', 'meta_description' => '나니아랩스의 최신 소식, 보도자료, 테크 블로그를 한눈에 확인하세요.'],
            'team' => ['meta_title' => '팀 소개 (Team) | 나니아랩스 사람들', 'meta_description' => '나니아랩스의 혁신을 실현하는 전문가 팀을 소개합니다.'],
            'education' => ['meta_title' => '에듀케이션 - 교육 프로그램 | 나니아랩스', 'meta_description' => '산업계와 학계를 잇는 생성형 AI 교육 프로그램을 제공합니다.'],
            'publication' => ['meta_title' => '퍼블리케이션 - 연구 성과 | 나니아랩스', 'meta_description' => '최고 권위의 학회에 게재된 나니아랩스의 기술 논문들을 확인하세요.'],
            'events' => ['meta_title' => '이벤트 - 학술 활동 및 세미나 | 나니아랩스', 'meta_description' => '나니아랩스가 참여하는 국내외 전시회와 세미나 소식을 전해드립니다.']
        ];

        foreach (self::pages() as $page) {
            $setting = SeoSetting::updateOrCreate(
                ['page_key' => $page['key']],
                ['page_name' => $page['name'], 'page_url' => $page['url']]
            );
            
            // 데이터가 아예 없는 경우에만 자동 채우기
            if (empty($setting->meta_title) && isset($defaults[$page['key']])) {
                $setting->update($defaults[$page['key']]);
            }
        }

        $settings = SeoSetting::orderBy('id')->get()->keyBy('page_key');
        $pages    = self::pages();

        return view('admin.seo.index', compact('settings', 'pages'));
    }

    public function edit(string $key)
    {
        $setting = SeoSetting::where('page_key', $key)->firstOrFail();
        return view('admin.seo.edit', compact('setting'));
    }

    public function update(Request $request, string $key)
    {
        $setting = SeoSetting::where('page_key', $key)->firstOrFail();

        $data = $request->validate([
            'meta_title'       => 'nullable|string|max:100',
            'meta_description' => 'nullable|string|max:320',
            'meta_keywords'    => 'nullable|string|max:500',
            'og_title'         => 'nullable|string|max:100',
            'og_description'   => 'nullable|string|max:320',
            'og_image'         => 'nullable|string|max:500',
            'twitter_card'     => 'nullable|string|max:50',
            'canonical_url'    => 'nullable|string|max:500',
            'index_allow'      => 'nullable|boolean',
            'head_script'      => 'nullable|string',
        ]);

        $data['index_allow'] = $request->boolean('index_allow');
        $setting->update($data);

        return redirect()->route('seo.index')->with('success', "[{$setting->page_name}] SEO 설정이 저장되었습니다.");
    }

    // ── Public API GET /admin/api/seo?page=home ──
    public function apiGet(Request $request)
    {
        $key = $request->query('page');
        if (!$key) {
            // 전체 반환
            $all = SeoSetting::all()->keyBy('page_key')->map(fn($s) => $this->formatSeo($s));
            return response()->json($all)->header('Access-Control-Allow-Origin', '*');
        }

        $setting = SeoSetting::where('page_key', $key)->first();
        if (!$setting) {
            return response()->json([])->header('Access-Control-Allow-Origin', '*');
        }

        return response()->json($this->formatSeo($setting))
            ->header('Access-Control-Allow-Origin', '*');
    }

    private function formatSeo(SeoSetting $s): array
    {
        return [
            'meta_title'       => $s->meta_title,
            'meta_description' => $s->meta_description,
            'meta_keywords'    => $s->meta_keywords,
            'og_title'         => $s->og_title,
            'og_description'   => $s->og_description,
            'og_image'         => $s->og_image,
            'twitter_card'     => $s->twitter_card,
            'index_allow'      => $s->index_allow,
            'canonical_url'    => $s->canonical_url,
            'head_script'      => $s->head_script,
        ];
    }

    // ── sitemap.xml ──
    public function sitemap()
    {
        $base  = 'https://narnialabs.mycafe24.com';
        $pages = self::pages();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($pages as $page) {
            $url = $page['url'] === '/' ? $base . '/' : $base . $page['url'];
            $xml .= "  <url>\n    <loc>{$url}</loc>\n    <changefreq>weekly</changefreq>\n    <priority>0.8</priority>\n  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
