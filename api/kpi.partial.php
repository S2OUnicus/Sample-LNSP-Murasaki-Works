<?php
http_response_code(200);
$fallback = require PROJECT_ROOT . '/lib/fallback.php';
$items = $fallback['kpis'];
if (extension_loaded('pdo_sqlite')) {
try {
    $repo = Repository::make($config);
    $items = $repo->kpis();
} catch (Throwable $e) {
    // SQLite接続エラー時はfallbackデータを返します。
}
}
?>
<?php foreach ($items as $kpi): ?>
    <div>
        <div class="mk-kpi-card">
            <p class="uk-text-small uk-margin-remove"><?= e($kpi['label']) ?></p>
            <p class="uk-h3 uk-margin-remove"><?= e($kpi['value']) ?><span class="uk-text-small"><?= e($kpi['unit'] ?? '') ?></span></p>
            <p class="uk-text-meta uk-margin-remove"><?= e($kpi['memo'] ?? '') ?></p>
        </div>
    </div>
<?php endforeach; ?>
