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
            ['key' => 'global',         'name' => 'Global Settings',         'url' => 'ALL PAGES'],
            ['key' => 'home',           'name' => 'Main',                    'url' => '/'],
            ['key' => 'about',          'name' => 'About',                   'url' => '/kor/html/company/about.html'],
            ['key' => 'news',           'name' => 'News',                    'url' => '/kor/html/company/news.html'],
            ['key' => 'team',           'name' => 'Team',                    'url' => '/kor/html/company/team.html'],
            ['key' => 'aslanx',         'name' => 'AslanX',                  'url' => '/kor/html/aslanX/aslanX.html'],
            ['key' => 'case_studies',   'name' => 'Case Studies',            'url' => '/kor/html/resource/caseStudies.html'],
            ['key' => 'education',      'name' => 'Education',               'url' => '/kor/html/resource/education.html'],
            ['key' => 'publication',    'name' => 'Publication',             'url' => '/kor/html/resource/publication.html'],
            ['key' => 'events',         'name' => 'Events',                  'url' => '/kor/html/resource/events.html'],
            ['key' => 'deep_gen',       'name' => 'Deep Generative Design',  'url' => '/kor/html/solution/deepGenerativeDesign.html'],
            ['key' => 'evaluation',     'name' => 'Evaluation',              'url' => '/kor/html/solution/evaluation.html'],
            ['key' => 'generation',     'name' => 'Generation',              'url' => '/kor/html/solution/generation.html'],
            ['key' => 'optimization',   'name' => 'Optimization',            'url' => '/kor/html/solution/optimization.html'],
            ['key' => 'recommendation', 'name' => 'Recommendation',          'url' => '/kor/html/solution/recommendation.html'],
            ['key' => 'tech_blog',      'name' => 'Tech Blog',               'url' => '/kor/html/resource/techBlog.html'],
            ['key' => 'contact',        'name' => 'Contact Us',              'url' => '/kor/html/contact/contactUs.html'],
        ];
    }

    public function index()
    {
        $defaults = [
            'home'         => ['meta_title' => '나니아랩스 (Narnia Labs) - 제조 산업을 위한 생성형 AI 솔루션', 'meta_description' => '나니아랩스는 딥 제너레이티브 디자인 기술을 통해 제품 설계의 혁신을 제안하는 AI 전문 기업입니다.'],
            'about'        => ['meta_title' => '회사 소개 | 나니아랩스 (Narnia Labs)', 'meta_description' => '나니아랩스의 미션과 비전을 소개합니다. 기술로 설계의 가치를 높입니다.'],
            'aslanx'       => ['meta_title' => 'AslanX - AI 설계 최적화 플랫폼 | 나니아랩스', 'meta_description' => '데이터 기반의 설계 생성 및 평가를 지원하는 AslanX 솔루션을 만나보세요.'],
            'deep_gen'     => ['meta_title' => 'Deep Generative Design | 나니아랩스 기술', 'meta_description' => '나니아랩스만의 독보적인 딥 제너레이티브 디자인 기술을 확인하세요.'],
            'case_studies' => ['meta_title' => '케이스스터디 - 성공 사례 | 나니아랩스', 'meta_description' => '현대자동차 등 파트너사들과 함께한 실질적인 프로젝트 성과를 확인하세요.'],
            'news'         => ['meta_title' => '새로운 소식 (News) | 나니아랩스', 'meta_description' => '나니아랩스의 최신 소식, 보도자료, 테크 블로그를 한눈에 확인하세요.'],
            'team'         => ['meta_title' => '팀 소개 (Team) | 나니아랩스 사람들', 'meta_description' => '나니아랩스의 혁신을 실현하는 전문가 팀을 소개합니다.'],
            'education'    => ['meta_title' => '에듀케이션 - 교육 프로그램 | 나니아랩스', 'meta_description' => '산업계와 학계를 잇는 생성형 AI 교육 프로그램을 제공합니다.'],
            'publication'  => ['meta_title' => '퍼블리케이션 - 연구 성과 | 나니아랩스', 'meta_description' => '최고 권위의 학회에 게재된 나니아랩스의 기술 논문들을 확인하세요.'],
            'events'       => ['meta_title' => '이벤트 - 학술 활동 및 세미나 | 나니아랩스', 'meta_description' => '나니아랩스가 참여하는 국내외 전시회와 세미나 소식을 전해드립니다.'],
        ];

        foreach (self::pages() as $page) {
            $setting = SeoSetting::updateOrCreate(
                ['page_key' => $page['key']],
                ['page_name' => $page['name'], 'page_url' => $page['url']]
            );
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
        $base  = 'https://narnia.ai';
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

    // ── rss.xml (RSS 2.0) ──
    public function feed()
    {
        $base    = 'https://narnia.ai';
        $feedUrl = $base . '/rss.xml';

        // RFC 2822 날짜 (4자리 연도 강제)
        $rfc = fn($dt) => $dt
            ? \Carbon\Carbon::parse($dt)->format('D, d M Y H:i:s O')
            : now()->format('D, d M Y H:i:s O');

        // XML 불허 제어문자 제거
        $clean = fn($str) => preg_replace(
            '/[\x{0000}-\x{0008}\x{000B}-\x{000C}\x{000E}-\x{001F}\x{2028}\x{2029}\x{FFFE}\x{FFFF}]/u',
            '', (string)$str
        );

        $now = now()->format('D, d M Y H:i:s O');

        // 뉴스 최신 20건 — 링크는 narnia.ai 내부 URL
        $news = \App\Models\News::orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(function ($item) use ($base, $rfc, $clean) {
                $date = $item->published_at ?? $item->created_at;
                return [
                    'guid'     => 'news-' . $item->id,
                    'title'    => $clean($item->title),
                    'link'     => $base . '/kor/html/company/news.html?id=' . $item->id,
                    'desc'     => $clean(strip_tags($item->content ?? $item->title)),
                    'date'     => $rfc($date),
                    'date_raw' => $date ? \Carbon\Carbon::parse($date)->timestamp : now()->timestamp,
                    'category' => '뉴스',
                ];
            });

        // 테크블로그 최신 20건
        $techBlogs = \App\Models\TechBlog::where('is_active', true)
            ->orderByDesc('published_date')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(function ($item) use ($base, $rfc, $clean) {
                $dt = $item->published_date ?: ($item->created_at ?? now());
                return [
                    'guid'     => 'techblog-' . $item->id,
                    'title'    => $clean($item->title),
                    'link'     => $base . '/kor/html/resource/techBlog.html?id=' . $item->id,
                    'desc'     => $clean(strip_tags($item->description ?? $item->title)),
                    'date'     => $rfc($dt),
                    'date_raw' => \Carbon\Carbon::parse($dt)->timestamp,
                    'category' => '테크블로그',
                ];
            });

        // 합치고 날짜순 정렬
        $items = $news->merge($techBlogs)
            ->sortByDesc('date_raw')
            ->take(30)
            ->values();

        $esc   = fn($str) => htmlspecialchars($clean((string)$str), ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $cdata = fn($str) => '<![CDATA[' . str_replace(']]>', ']]]]><![CDATA[>', $clean((string)$str)) . ']]>';

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . PHP_EOL;
        $xml .= '<channel>' . PHP_EOL;
        $xml .= "  <title>{$cdata('나니아랩스 (Narnia Labs)')}</title>\n";
        $xml .= "  <link>{$base}</link>\n";
        $xml .= "  <description>{$cdata('나니아랩스 최신 뉴스 및 테크블로그')}</description>\n";
        $xml .= "  <language>ko</language>\n";
        $xml .= "  <lastBuildDate>{$now}</lastBuildDate>\n";
        $xml .= "  <atom:link href=\"{$feedUrl}\" rel=\"self\" type=\"application/rss+xml\" />\n";

        foreach ($items as $item) {
            $xml .= "  <item>\n";
            $xml .= "    <title>{$cdata($item['title'])}</title>\n";
            $xml .= "    <link>{$esc($item['link'])}</link>\n";
            $xml .= "    <guid isPermaLink=\"false\">{$esc($base . '/rss/' . $item['guid'])}</guid>\n";
            $xml .= "    <description>{$cdata(mb_substr($item['desc'], 0, 300))}</description>\n";
            $xml .= "    <pubDate>{$item['date']}</pubDate>\n";
            $xml .= "    <category>{$cdata($item['category'])}</category>\n";
            $xml .= "  </item>\n";
        }

        $xml .= '</channel>' . PHP_EOL;
        $xml .= '</rss>';

        return response($xml, 200)->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
