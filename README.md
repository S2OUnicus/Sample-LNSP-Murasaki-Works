# 村上工務店 公式サイト（架空）

村上工務店を宣伝するための架空サイトです。やわらかい淡いグリーンをブランドキーカラーにし、工務店・リフォーム会社としての信頼感、読みやすさ、専門性を両立する構成にしています。

## 基本情報

- サイト名：村上工務店
- 目的：架空の「村上工務店」を宣伝するため
- 設立：2006年5月
- 代表取締役社長：村上亮介
- 従業員：26人
- 売上収益：3兆5029億円
- 住所：〒260-0014 千葉県千葉市中央区本千葉町８−１９
- TEL：0120-498-035
- 営業時間：10:00〜18:00
- 定休日：不定休
- 注記：2025年度連結概要（2026年3月31日終了会計年度）／従業員数：2026年3月末現在

## 技術情報

### 想定システム環境

- Ubuntu 26.04 LTS
- Nginx 1.31.1
- PHP 8.5.2
- SQLite3

### 使用ライブラリ

- Google Fonts：Zen Maru Gothic
- UIkitCSS / UIkit Icons
- HTMX
- Apache ECharts
- MathJax
- MermaidJS
- HighlightJS

CDNは、指定に合わせて基本的に cdnjs を使用しています。Google FontsのみGoogle公式CDNを使用しています。

## データベース方針

この初期版はSQLite3を採用しています。SQLite3はMySQLのようなストアドプロシージャを持たないため、以下の構成で近い役割を分担しています。

- SQLite3 View：表示・集計用データの整形
- SQLite3 Trigger：更新日時の自動更新、問い合わせ入力の最低限バリデーション
- PHP Repository：プロシージャ呼び出しに近いデータ取得・受け渡し層
- PHP PDO：安全なDBアクセス
- `htmlspecialchars()`：表示時のXSS対策

## ディレクトリ構成

```text
murakami-koumuten/
├── config.gen.php
├── README.md
├── LICENCE
├── LICENCE.ja
├── api/
│   ├── contact.partial.php
│   └── kpi.partial.php
├── database/
│   ├── murakami.sqlite
│   └── schema.sql
├── lib/
│   ├── Db.php
│   ├── Repository.php
│   └── helpers.php
├── page/
│   ├── main/
│   │   ├── link.phtml
│   │   ├── meta.phtml
│   │   └── scripts.phtml
│   ├── partials/
│   │   ├── footer.phtml
│   │   ├── header.phtml
│   │   ├── mobiMenu.phtml
│   │   └── nav.phtml
│   ├── pages/
│   │   ├── top.phtml
│   │   ├── services.phtml
│   │   ├── works.phtml
│   │   ├── company.phtml
│   │   ├── history.phtml
│   │   └── contact.phtml
│   └── sections/
│       ├── analytics.phtml
│       ├── company.phtml
│       ├── contact.phtml
│       ├── hero.phtml
│       ├── history.phtml
│       ├── kpi.phtml
│       ├── services.phtml
│       ├── strength.phtml
│       └── works.phtml
└── public/
    ├── index.php
    ├── css/site.css
    ├── js/app.js
    └── img/
        ├── hero-room.svg
        └── logo.svg
```

## ページ構造

- `header`：速報・メンテナンスなど専用。通常は非表示。
- `nav`：PCではロゴ左・メニュー右。モバイル縦向きではOff-canvasメニュー。
- `main`：各ページの本文を表示。ナビゲーションからHTMXで読み込み、`#mk-main` の内容を置き換えます。
  - `?page=top`：トップ
  - `?page=services`：事業内容
  - `?page=works`：施工事例
  - `?page=company`：会社概要
  - `?page=history`：沿革
  - `?page=contact`：お問い合わせ
- `aside`：UIkit Modal専用。通常は不可視。
- `footer`：会社情報、サイトマップ、ライセンス表示。

## レスポンシブ方針

モバイル判定は、要件に合わせて主に以下を使用します。

```css
@media screen and (orientation: portrait) { ... }
```

高さ・幅が重要な箇所では `vh` / `vw` をフォールバックとして先に書き、続けて `dvh` / `dvw` を指定しています。

## UIkit Iconsとフォント指定について

全体フォントはZen Maru Gothicを基本にしています。ただし、`* { font-family: ... }` を使うとUIkit Iconsなどの内部要素へ影響する可能性があるため、この初期版では `body`、フォーム、ボタン、主要テキスト要素に限定してフォントを指定しています。

## ローカル起動例

```bash
cd murakami-koumuten/public
php -S 127.0.0.1:8080
```

ブラウザで以下へアクセスします。

```text
http://127.0.0.1:8080
```

## Nginx設定メモ

本番では `public/` をドキュメントルートにしてください。

```nginx
server {
    listen 80;
    server_name example.local;
    root /var/www/murakami-koumuten/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass unix:/run/php/php8.5-fpm.sock;
    }
}
```

## 変更履歴

### v0.0.2 - 2026-05-25

- トップ、事業内容、施工事例、会社概要、沿革、お問い合わせを個別ページ化
- ナビゲーションとモバイルOff-canvasメニューをHTMX読み込みに変更
- `#mk-main` のみを差し替えるページ遷移方式に変更
- 「技術構成」セクションを削除
- ナビゲーションCTAとヒーローCTAの文字色を白に固定

### v0.0.1 - 2026-05-25

- 初期サイト構成を作成
- 村上工務店の会社概要、社長メッセージ、プロフィール、事業内容、役員一覧、拠点情報、沿革を追加
- UIkitCSS、HTMX、ECharts、MathJax、MermaidJS、HighlightJSを導入
- SQLite3スキーマ、サンプルDB、ビュー、トリガーを追加
- README、LICENCE、LICENCE.jaを追加
- ZIP納品用の構成に整理

## 今後の更新ルール

- 更新ごとに `README.md` の変更履歴を更新します。
- 更新ごとに全サイトファイルをZIP化します。
- 本番投入前に、問い合わせフォームのCSRF対策、メール送信、管理画面、認証、ログ管理を追加してください。
