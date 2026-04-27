<?php
use App\Models\SeoSetting;

$seoData = [
    'home' => [
        'meta_title' => '나니아랩스 (Narnia Labs) - 제조 산업을 위한 생성형 AI 솔루션',
        'meta_description' => '나니아랩스는 딥 제너레이티브 디자인 기술을 통해 제품 설계의 혁신을 제안하는 AI 전문 기업입니다. 최적의 설계안을 실시간으로 생성하고 평가하세요.',
        'meta_keywords' => '나니아랩스, 생성형 AI, 딥 제너레이티브 디자인, 제조 AI, 설계 최적화, AslanX',
        'og_title' => '나니아랩스: AI로 설계의 한계를 넘다',
        'og_description' => '제조 산업을 위한 혁신적인 생성형 AI 솔루션, 나니아랩스입니다.'
    ],
    'about' => [
        'meta_title' => '회사 소개 | 나니아랩스 (Narnia Labs)',
        'meta_description' => '나니아랩스의 미션과 비전을 소개합니다. 우리는 기술로 설계의 가치를 높이고 산업의 혁신을 이끕니다.',
        'meta_keywords' => '나니아랩스 소개, 비전, 미션, AI 스타트업',
    ],
    'aslanx' => [
        'meta_title' => 'AslanX - AI 설계 최적화 플랫폼 | 나니아랩스',
        'meta_description' => '데이터 기반의 설계 생성 및 평가를 지원하는 AslanX 솔루션을 만나보세요. 복잡한 설계를 AI가 더 빠르고 정확하게 도와줍니다.',
        'meta_keywords' => 'AslanX, 설계 플랫폼, AI 소프트웨어',
    ],
    'deep_gen' => [
        'meta_title' => 'Deep Generative Design | 나니아랩스 기술',
        'meta_description' => '나니아랩스만의 독보적인 딥 제너레이티브 디자인 기술을 통해 수만 가지의 설계 대안을 검토하세요.',
        'meta_keywords' => '딥 제너레이티브 디자인, 생성 모델, AI 설계',
    ],
    'case_studies' => [
        'meta_title' => '케이스스터디 - 성공 사례 | 나니아랩스',
        'meta_description' => '현대자동차 등 유수의 파트너사들과 함께한 나니아랩스의 실질적인 프로젝트 성과를 확인하세요.',
        'meta_keywords' => 'AI 성공 사례, 프로젝트 현황, 제조 혁신 사례',
    ],
    'news' => [
        'meta_title' => '새로운 소식 (News) | 나니아랩스',
        'meta_description' => '나니아랩스의 최신 소식, 보도자료, 테크 블로그를 한눈에 확인하세요.',
        'meta_keywords' => '나니아랩스 뉴스, 보도자료, 공지사항',
    ],
    'team' => [
        'meta_title' => '팀 소개 (Team) | 나니아랩스 사람들',
        'meta_description' => '나니아랩스의 혁신을 실현하는 전문가 팀을 소개합니다.',
        'meta_keywords' => '나니아랩스 팀원, 채용, AI 전문가',
    ],
    'education' => [
        'meta_title' => '에듀케이션 - 교육 프로그램 | 나니아랩스',
        'meta_description' => '산업계와 학계를 잇는 생성형 AI 교육 프로그램을 제공합니다.',
        'meta_keywords' => 'AI 교육, 제조 AI 전문가 양성',
    ],
    'publication' => [
        'meta_title' => '퍼블리케이션 - 연구 성과 | 나니아랩스',
        'meta_description' => '최고 권위의 학회와 저널에 게재된 나니아랩스의 기술 논문들을 확인하세요.',
        'meta_keywords' => '연구 논문, 학회 발표, AI 알고리즘',
    ],
    'events' => [
        'meta_title' => '이벤트 - 학술 활동 및 세미나 | 나니아랩스',
        'meta_description' => '나니아랩스가 참여하는 국내외 전시회와 세미나 소식을 전해드립니다.',
        'meta_keywords' => '전시회, 세미나, 컨퍼런스 소식',
    ]
];

foreach ($seoData as $key => $data) {
    SeoSetting::where('page_key', $key)->update($data);
}

echo "SEO 데이터 업데이트 완료!";
