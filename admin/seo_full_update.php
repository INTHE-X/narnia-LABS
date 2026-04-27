<?php
use App\Models\SeoSetting;

$ogImage = 'https://narnialabs.mycafe24.com/assets/img/common/og_image.png';
$base    = 'https://narnialabs.mycafe24.com';

$seoData = [

    /* ── 전역 설정 ── */
    'global' => [
        'meta_title'       => '나니아랩스 (Narnia Labs) — Physical AI for Manufacturing',
        'meta_description' => '나니아랩스는 제조 설계를 위한 Physical AI 플랫폼 AslanX를 개발합니다. 생성형 AI로 설계 혁신을 경험하세요.',
        'meta_keywords'    => '나니아랩스, AslanX, Physical AI, 생성형 AI, 제조 AI, 설계 최적화',
        'og_title'         => '나니아랩스 — Manufacturing AI Platform',
        'og_description'   => '제조 산업을 위한 AI 솔루션, 나니아랩스입니다.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base,
        'index_allow'      => true,
    ],

    /* ── 메인 ── */
    'home' => [
        'meta_title'       => '나니아랩스 (Narnia Labs) — 제조 산업을 위한 Physical AI',
        'meta_description' => '나니아랩스는 딥 제너레이티브 디자인 AI로 제품 설계를 혁신합니다. 최적 설계안을 수초 만에 생성하고 평가하는 AslanX 플랫폼을 만나보세요.',
        'meta_keywords'    => '나니아랩스, AslanX, Physical AI, 생성형 AI, 제조 설계, 설계 최적화, 딥러닝 설계',
        'og_title'         => '나니아랩스: AI로 설계의 한계를 넘다',
        'og_description'   => '제조 산업을 위한 혁신적인 생성형 AI 플랫폼 AslanX. 설계 시간을 1달에서 1분으로.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/',
        'index_allow'      => true,
    ],

    /* ── About ── */
    'about' => [
        'meta_title'       => '회사 소개 (About) | 나니아랩스 Narnia Labs',
        'meta_description' => '나니아랩스는 제조 설계의 패러다임을 바꾸는 Physical AI 전문 기업입니다. 우리의 미션과 비전, 그리고 핵심 기술을 소개합니다.',
        'meta_keywords'    => '나니아랩스 소개, 회사 비전, Physical AI 스타트업, 제조 혁신 기업',
        'og_title'         => '나니아랩스 소개 — Physical AI for Manufacturing',
        'og_description'   => '제조 설계 혁신을 이끄는 나니아랩스의 미션과 기술력을 확인하세요.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/company/about.html',
        'index_allow'      => true,
    ],

    /* ── News ── */
    'news' => [
        'meta_title'       => '뉴스 (News) — 나니아랩스 최신 소식',
        'meta_description' => '나니아랩스의 최신 보도자료, 수상 소식, 언론 기사를 확인하세요. AI 설계 혁신 리더 나니아랩스의 새로운 소식을 가장 빠르게 전해드립니다.',
        'meta_keywords'    => '나니아랩스 뉴스, 보도자료, 언론 기사, AI 기업 소식, AslanX 소식',
        'og_title'         => '나니아랩스 뉴스 — 최신 소식 모음',
        'og_description'   => '나니아랩스의 최신 보도자료와 언론 기사를 한눈에 확인하세요.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/company/news.html',
        'index_allow'      => true,
    ],

    /* ── Team ── */
    'team' => [
        'meta_title'       => '팀 소개 (Team) | 나니아랩스의 사람들',
        'meta_description' => '나니아랩스의 혁신을 함께 만들어가는 전문가 팀을 소개합니다. AI·공학·사업개발 분야의 최고 전문가들이 모여 제조 설계의 미래를 설계합니다.',
        'meta_keywords'    => '나니아랩스 팀, AI 전문가, 엔지니어, 스타트업 팀, 채용',
        'og_title'         => '나니아랩스 팀 — 함께 미래를 설계하는 사람들',
        'og_description'   => 'AI와 공학 분야의 전문가들이 모인 나니아랩스 팀을 만나보세요.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/company/team.html',
        'index_allow'      => true,
    ],

    /* ── AslanX ── */
    'aslanx' => [
        'meta_title'       => 'AslanX — AI 설계 자동화 플랫폼 | 나니아랩스',
        'meta_description' => 'AslanX는 생성·예측·최적화 AI를 통합한 제조 설계 플랫폼입니다. 한 달 걸리던 설계를 1분으로 단축하고 3만 개 이상의 설계 대안을 자동으로 생성합니다.',
        'meta_keywords'    => 'AslanX, 설계 자동화, 생성형 AI 플랫폼, 제조 AI 소프트웨어, 설계 최적화',
        'og_title'         => 'AslanX — 제조 설계를 바꾸는 AI 플랫폼',
        'og_description'   => '생성·예측·최적화 AI가 하나로. AslanX로 설계 생산성을 극대화하세요.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/aslanX/aslanX.html',
        'index_allow'      => true,
    ],

    /* ── 테크블로그 ── */
    'tech_blog' => [
        'meta_title'       => '테크블로그 — 나니아랩스 기술 이야기',
        'meta_description' => 'AI 설계 자동화, 딥러닝, 생성형 AI에 관한 나니아랩스의 기술 인사이트를 공유합니다. 최신 연구와 실제 적용 사례를 깊이 있게 다룹니다.',
        'meta_keywords'    => '나니아랩스 테크블로그, AI 기술, 딥러닝, 제조 AI, 생성형 AI 인사이트',
        'og_title'         => '나니아랩스 테크블로그 — AI 기술 인사이트',
        'og_description'   => 'AI 설계 자동화의 최전선, 나니아랩스 기술 블로그에서 만나보세요.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/resource/techBlog.html',
        'index_allow'      => true,
    ],

    /* ── 컨택어스 ── */
    'contact' => [
        'meta_title'       => '문의하기 (Contact Us) | 나니아랩스',
        'meta_description' => 'AslanX 도입 문의, 파트너십, 투자 등 나니아랩스에 궁금한 점을 남겨주세요. 빠르고 친절하게 답변드리겠습니다.',
        'meta_keywords'    => '나니아랩스 문의, AslanX 도입 상담, 파트너십, 투자 문의, 컨택어스',
        'og_title'         => '나니아랩스에 문의하세요 — Contact Us',
        'og_description'   => 'AslanX 도입 문의부터 파트너십까지, 나니아랩스가 빠르게 답변드립니다.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/contact/contactUs.html',
        'index_allow'      => true,
    ],

    /* ── 케이스스터디 ── */
    'case_studies' => [
        'meta_title'       => '케이스스터디 — AI 설계 성공 사례 | 나니아랩스',
        'meta_description' => '현대자동차 등 국내외 파트너사와 함께한 AI 설계 자동화 프로젝트 성공 사례를 확인하세요. 실제 적용 결과와 성능 지표를 공개합니다.',
        'meta_keywords'    => 'AI 성공 사례, 제조 혁신 사례, 설계 자동화 프로젝트, 나니아랩스 레퍼런스',
        'og_title'         => '나니아랩스 케이스스터디 — 실전 AI 설계 성공 사례',
        'og_description'   => '국내외 제조 파트너사와 함께한 AI 설계 혁신 프로젝트 사례를 확인하세요.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/resource/caseStudies.html',
        'index_allow'      => true,
    ],

    /* ── 에듀케이션 ── */
    'education' => [
        'meta_title'       => '에듀케이션 — AI 설계 교육 프로그램 | 나니아랩스',
        'meta_description' => '나니아랩스의 AI 설계 교육 프로그램으로 제조 혁신을 이끄는 전문가가 되세요. 산학 연계 커리큘럼과 실전 프로젝트로 구성된 특화 과정을 제공합니다.',
        'meta_keywords'    => 'AI 교육, 제조 AI 전문가 양성, 산학 연계 교육, 딥러닝 설계 교육',
        'og_title'         => '나니아랩스 에듀케이션 — AI 제조 설계 전문가 과정',
        'og_description'   => '산학 연계 AI 설계 교육으로 제조 혁신 전문가로 성장하세요.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/resource/education.html',
        'index_allow'      => true,
    ],

    /* ── 퍼블리케이션 ── */
    'publication' => [
        'meta_title'       => '퍼블리케이션 — 나니아랩스 연구 논문 모음',
        'meta_description' => 'NeurIPS, ICLR 등 세계 최고 AI 학회에 게재된 나니아랩스의 연구 논문을 확인하세요. 생성형 AI와 제조 설계 분야의 최신 연구 성과를 공유합니다.',
        'meta_keywords'    => '나니아랩스 논문, AI 연구, 생성형 AI 논문, NeurIPS, 제조 AI 학술',
        'og_title'         => '나니아랩스 퍼블리케이션 — 세계적 수준의 AI 연구',
        'og_description'   => '세계 최고 AI 학회에 게재된 나니아랩스의 연구 논문을 확인하세요.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/resource/publication.html',
        'index_allow'      => true,
    ],

    /* ── 이벤트 ── */
    'events' => [
        'meta_title'       => '이벤트 — 나니아랩스 전시·세미나 일정',
        'meta_description' => '나니아랩스가 참가하는 국내외 전시회, 컨퍼런스, 세미나 일정을 확인하세요. AI·제조 분야 최신 행사 소식을 가장 먼저 전해드립니다.',
        'meta_keywords'    => '나니아랩스 이벤트, 전시회, 컨퍼런스, AI 세미나, 행사 일정',
        'og_title'         => '나니아랩스 이벤트 — 전시·세미나 일정',
        'og_description'   => '나니아랩스가 참가하는 국내외 AI 제조 행사 일정을 확인하세요.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/resource/events.html',
        'index_allow'      => true,
    ],

    /* ── Deep Generative Design ── */
    'deep_gen' => [
        'meta_title'       => 'Deep Generative Design | 나니아랩스 핵심 기술',
        'meta_description' => '나니아랩스의 딥 제너레이티브 디자인 기술로 수만 가지 최적 설계 대안을 자동 생성하세요. 인간이 생각하기 어려운 혁신적 구조를 AI가 제안합니다.',
        'meta_keywords'    => '딥 제너레이티브 디자인, 생성 모델, AI 설계, 위상 최적화, 설계 자동화',
        'og_title'         => 'Deep Generative Design — 나니아랩스 생성 AI 기술',
        'og_description'   => '수만 가지 최적 설계를 자동 생성하는 딥 제너레이티브 디자인 기술.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/solution/deepGenerativeDesign.html',
        'index_allow'      => true,
    ],

    /* ── Evaluation ── */
    'evaluation' => [
        'meta_title'       => 'Evaluation (예측 AI) | AslanX — 나니아랩스',
        'meta_description' => 'AslanX Evaluation으로 새로운 설계의 성능을 단 몇 초 만에 예측하세요. 나니아랩스의 예측 AI는 99% 정확도로 엔지니어링 성능을 검증합니다.',
        'meta_keywords'    => 'Evaluation AI, 성능 예측, AI 시뮬레이션, 설계 검증, AslanX Evaluator',
        'og_title'         => 'AslanX Evaluation — 99% 정확도 성능 예측 AI',
        'og_description'   => '설계 성능을 단 몇 초 만에 예측하는 나니아랩스 Evaluation AI.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/solution/evaluation.html',
        'index_allow'      => true,
    ],

    /* ── Generation ── */
    'generation' => [
        'meta_title'       => 'Generation (생성 AI) | AslanX — 나니아랩스',
        'meta_description' => 'AslanX Generation으로 혁신적인 설계 대안을 자동 생성하세요. 나니아랩스의 생성 AI는 3만 개 이상의 최적 설계안을 실시간으로 제안합니다.',
        'meta_keywords'    => 'Generation AI, 설계 생성, 자동 설계, AI Generator, AslanX Generator',
        'og_title'         => 'AslanX Generation — 3만+ 설계 대안 자동 생성',
        'og_description'   => '혁신적인 설계 대안을 자동으로 생성하는 나니아랩스 Generation AI.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/solution/generation.html',
        'index_allow'      => true,
    ],

    /* ── Optimization ── */
    'optimization' => [
        'meta_title'       => 'Optimization (최적화 AI) | AslanX — 나니아랩스',
        'meta_description' => 'AslanX Optimization으로 다목적 설계 최적화를 자동화하세요. 중량, 강도, 비용 등 복수 목표를 동시에 만족하는 최적 설계를 AI가 찾아드립니다.',
        'meta_keywords'    => 'Optimization AI, 설계 최적화, 다목적 최적화, 경량화 설계, AslanX Optimizer',
        'og_title'         => 'AslanX Optimization — AI 다목적 설계 최적화',
        'og_description'   => '중량·강도·비용을 동시에 최적화하는 나니아랩스 AI 솔루션.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/solution/optimization.html',
        'index_allow'      => true,
    ],

    /* ── Recommendation ── */
    'recommendation' => [
        'meta_title'       => 'Recommendation (추천 AI) | AslanX — 나니아랩스',
        'meta_description' => 'AslanX Recommendation으로 수천 개의 설계 후보 중 최적안을 즉시 추천받으세요. 나니아랩스의 추천 AI가 설계 의사결정을 빠르고 정확하게 지원합니다.',
        'meta_keywords'    => 'Recommendation AI, 설계 추천, AI 의사결정, 최적안 선정, AslanX Recommender',
        'og_title'         => 'AslanX Recommendation — AI 최적 설계 추천',
        'og_description'   => '수천 개 설계 후보에서 최적안을 즉시 추천하는 나니아랩스 AI.',
        'og_image'         => $ogImage,
        'twitter_card'     => 'summary_large_image',
        'canonical_url'    => $base . '/kor/html/solution/recommendation.html',
        'index_allow'      => true,
    ],
];

foreach ($seoData as $key => $data) {
    SeoSetting::updateOrCreate(
        ['page_key' => $key],
        array_merge($data, ['index_allow' => true])
    );
}

echo '✅ 전체 SEO 데이터 업데이트 완료! (' . count($seoData) . '개 페이지)';
