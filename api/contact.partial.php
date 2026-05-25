<?php
header('Content-Type: text/html; charset=UTF-8');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method !== 'POST') {
    http_response_code(405);
    echo '<div class="uk-alert-danger" uk-alert>POSTのみ対応しています。</div>';
    return;
}

$name = trim((string)($_POST['name'] ?? ''));
$tel = trim((string)($_POST['tel'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$category = trim((string)($_POST['category'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if ($name === '' || $tel === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $category === '' || mb_strlen($message) < 8) {
    http_response_code(422);
    echo '<div class="uk-alert-warning" uk-alert>入力内容を確認してください。メール形式と相談内容の長さが必要です。</div>';
    return;
}

if (!extension_loaded('pdo_sqlite')) {
    http_response_code(503);
    echo '<div class="uk-alert-warning" uk-alert>SQLite3ドライバが有効ではないため、デモ保存は行えません。入力形式の確認のみ完了しました。</div>';
    return;
}

try {
    $pdo = Db::pdo($config);
    $stmt = $pdo->prepare('INSERT INTO contact_requests (name, tel, email, category, message) VALUES (:name, :tel, :email, :category, :message)');
    $stmt->execute([
        ':name' => $name,
        ':tel' => $tel,
        ':email' => $email,
        ':category' => $category,
        ':message' => $message,
    ]);
    echo '<div class="uk-alert-success" uk-alert><strong>送信ありがとうございます。</strong><br>デモ環境ではSQLite3に保存しました。実運用ではメール通知・管理画面連携を追加してください。</div>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<div class="uk-alert-danger" uk-alert>保存に失敗しました。時間をおいて再度お試しください。</div>';
    if (!empty($config['debug'])) {
        echo '<pre class="uk-margin-small-top"><code>' . e($e->getMessage()) . '</code></pre>';
    }
}
