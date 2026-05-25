<?php
declare(strict_types=1);

define('PROJECT_ROOT', dirname(__DIR__));
$config = require PROJECT_ROOT . '/config.gen.php';
require_once PROJECT_ROOT . '/lib/helpers.php';
require_once PROJECT_ROOT . '/lib/Db.php';
require_once PROJECT_ROOT . '/lib/Repository.php';

$pageRoutes = [
    'top' => ['name' => 'トップ', 'category' => '公式サイト', 'template' => 'top.phtml'],
    'services' => ['name' => '事業内容', 'category' => 'サービス', 'template' => 'services.phtml'],
    'works' => ['name' => '施工事例', 'category' => '実績紹介', 'template' => 'works.phtml'],
    'company' => ['name' => '会社概要', 'category' => '企業情報', 'template' => 'company.phtml'],
    'history' => ['name' => '沿革', 'category' => '企業情報', 'template' => 'history.phtml'],
    'contact' => ['name' => 'お問い合わせ', 'category' => '相談予約', 'template' => 'contact.phtml'],
];

$currentPage = $_GET['page'] ?? 'top';
if (!is_string($currentPage) || !isset($pageRoutes[$currentPage])) {
    $currentPage = 'top';
}
$pageInfo = $pageRoutes[$currentPage];
$pageName = $pageInfo['name'];
$pageCategory = $pageInfo['category'];
$pageTemplate = PROJECT_ROOT . '/page/pages/' . $pageInfo['template'];

$fallback = require PROJECT_ROOT . '/lib/fallback.php';
$services = $fallback['services'];
$officers = $fallback['officers'];
$branches = $fallback['branches'];
$histories = $fallback['histories'];
$works = $fallback['works'];
$kpis = $fallback['kpis'];
$serviceShare = $fallback['serviceShare'];

if (extension_loaded('pdo_sqlite')) {
try {
    $repo = Repository::make($config);
    $services = $repo->services();
    $officers = $repo->officers();
    $branches = $repo->branches();
    $histories = $repo->histories();
    $works = $repo->works();
    $kpis = $repo->kpis();
    $serviceShare = $repo->serviceShare();
} catch (Throwable $e) {
    if (!empty($config['debug'])) {
        error_log($e->getMessage());
    }
    // SQLite接続エラー時も、サイト表示はfallbackデータで継続します。
}
}

$partial = $_GET['partial'] ?? null;
if ($partial !== null) {
    $allowedPartials = [
        'kpi' => PROJECT_ROOT . '/api/kpi.partial.php',
        'contact' => PROJECT_ROOT . '/api/contact.partial.php',
    ];
    if (isset($allowedPartials[$partial])) {
        require $allowedPartials[$partial];
        exit;
    }
    http_response_code(404);
    echo 'Not Found';
    exit;
}
?>
<!doctype html>
<html lang="ja">
<head>
    <?php require PROJECT_ROOT . '/page/main/meta.phtml'; ?>
    <?php require PROJECT_ROOT . '/page/main/link.phtml'; ?>
</head>
<body hx-headers='{"X-Requested-With":"HTMX"}' class="mk-body">
<?php require PROJECT_ROOT . '/page/partials/header.phtml'; ?>
<?php require PROJECT_ROOT . '/page/partials/nav.phtml'; ?>
<?php require PROJECT_ROOT . '/page/partials/mobiMenu.phtml'; ?>

<main id="mk-main" class="mk-main" data-current-page="<?= e($currentPage) ?>">
    <?php require $pageTemplate; ?>
</main>

<aside aria-hidden="true">
    <div id="estimate-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-border-rounded">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="uk-modal-title">概算相談</h2>
            <p>間取り、施工範囲、希望時期を確認して概算を整理します。正式運用時は専用フォームへ拡張します。</p>
        </div>
    </div>
</aside>

<?php require PROJECT_ROOT . '/page/partials/footer.phtml'; ?>
<?php require PROJECT_ROOT . '/page/main/scripts.phtml'; ?>
</body>
</html>
