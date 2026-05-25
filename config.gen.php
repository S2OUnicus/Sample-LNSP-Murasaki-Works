<?php
/**
 * 村上工務店サイト 基本設定
 * v0.0.2
 */
return [
    'version' => 'v0.0.2',
    'debug' => true,
    'maintenance' => [
        'enabled' => false,
        'message' => '現在、サイトメンテナンス中です。しばらくしてから再度アクセスしてください。',
    ],
    'meta' => [
        'author' => '@S2OUnicus,@S2OValor,https://s2o.me/',
        'theme_color' => '#9fcff7',
        'robots' => 'index,follow',
    ],
    'site' => [
        'name' => '村上工務店',
        'domain' => 'https://example.murakami-koumuten.local',
        'description' => '千葉を中心に、マンション内装・水回り・住まい全体のリフォームをやわらかく丁寧に支える架空の工務店サイトです。',
        'keywords' => '村上工務店,千葉 工務店,千葉 リフォーム,マンション 内装リフォーム,キッチン リフォーム,トイレ リフォーム',
        'brand_color' => '#A7D8B8',
        'brand_color_dark' => '#4F8F68',
        'brand_color_soft' => '#ECF8F0',
        'logo_text' => '村上工務店',
        'catchcopy' => 'やさしく整える、暮らしのリフォーム。',
    ],
    'company' => [
        'name' => '村上工務店',
        'established' => '2006年5月',
        'founded_year' => 2006,
        'president' => '村上亮介',
        'employees' => '26人',
        'revenue' => '3兆5029億円',
        'address' => '〒260-0014 千葉県千葉市中央区本千葉町８−１９',
        'tel' => '0120-498-035',
        'business_hours' => '10:00〜18:00',
        'closed' => '不定休',
        'note' => '注：2025年度連結概要（2026年3月31日終了会計年度）／従業員数：2026年3月末現在',
    ],
    'nav' => [
        ['label' => 'トップ', 'page' => 'top', 'href' => '?page=top'],
        ['label' => '事業内容', 'page' => 'services', 'href' => '?page=services'],
        ['label' => '施工事例', 'page' => 'works', 'href' => '?page=works'],
        ['label' => '会社概要', 'page' => 'company', 'href' => '?page=company'],
        ['label' => '沿革', 'page' => 'history', 'href' => '?page=history'],
        ['label' => 'お問い合わせ', 'page' => 'contact', 'href' => '?page=contact'],
    ],
    'database' => [
        'driver' => 'sqlite',
        'path' => 'database/murakami.sqlite',
        'schema' => 'database/schema.sql',
    ],
    'ui' => [
        'page_title_format' => '%s - %s',
        'default_page_name' => 'トップ',
        'default_page_category' => '公式サイト',
    ],
];
