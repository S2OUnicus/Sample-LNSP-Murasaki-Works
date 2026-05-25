PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS services (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    summary TEXT NOT NULL,
    icon TEXT NOT NULL DEFAULT 'paint-bucket',
    active INTEGER NOT NULL DEFAULT 1 CHECK (active IN (0, 1)),
    sort_order INTEGER NOT NULL DEFAULT 100,
    created_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime')),
    updated_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime'))
);

CREATE TABLE IF NOT EXISTS officers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    title TEXT NOT NULL,
    role_code TEXT NOT NULL,
    message TEXT NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 100,
    created_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime')),
    updated_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime'))
);

CREATE TABLE IF NOT EXISTS branches (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    area TEXT NOT NULL,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 100,
    created_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime')),
    updated_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime'))
);

CREATE TABLE IF NOT EXISTS histories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    year_label TEXT NOT NULL,
    title TEXT NOT NULL,
    body TEXT NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 100,
    created_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime')),
    updated_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime'))
);

CREATE TABLE IF NOT EXISTS works (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    category TEXT NOT NULL,
    location TEXT NOT NULL,
    summary TEXT NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 100,
    created_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime')),
    updated_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime'))
);

CREATE TABLE IF NOT EXISTS kpis (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    label TEXT NOT NULL,
    value TEXT NOT NULL,
    unit TEXT NOT NULL DEFAULT '',
    memo TEXT NOT NULL DEFAULT '',
    sort_order INTEGER NOT NULL DEFAULT 100,
    created_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime')),
    updated_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime'))
);

CREATE TABLE IF NOT EXISTS service_share (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    label TEXT NOT NULL,
    percent INTEGER NOT NULL CHECK (percent >= 0 AND percent <= 100),
    sort_order INTEGER NOT NULL DEFAULT 100,
    created_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime')),
    updated_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime'))
);

CREATE TABLE IF NOT EXISTS contact_requests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    tel TEXT NOT NULL,
    email TEXT NOT NULL,
    category TEXT NOT NULL,
    message TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'new' CHECK (status IN ('new', 'checked', 'closed')),
    created_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime')),
    updated_at TEXT NOT NULL DEFAULT (datetime('now', 'localtime'))
);

CREATE VIEW IF NOT EXISTS v_active_services AS
SELECT id, title, summary, icon, sort_order
FROM services
WHERE active = 1;

CREATE VIEW IF NOT EXISTS v_contact_summary AS
SELECT category, COUNT(*) AS request_count
FROM contact_requests
GROUP BY category;

CREATE TRIGGER IF NOT EXISTS trg_services_updated_at
AFTER UPDATE ON services
FOR EACH ROW
BEGIN
    UPDATE services SET updated_at = datetime('now', 'localtime') WHERE id = OLD.id;
END;

CREATE TRIGGER IF NOT EXISTS trg_contact_requests_updated_at
AFTER UPDATE ON contact_requests
FOR EACH ROW
BEGIN
    UPDATE contact_requests SET updated_at = datetime('now', 'localtime') WHERE id = OLD.id;
END;

CREATE TRIGGER IF NOT EXISTS trg_contact_requests_validate
BEFORE INSERT ON contact_requests
FOR EACH ROW
WHEN length(trim(NEW.name)) = 0 OR length(trim(NEW.message)) < 8
BEGIN
    SELECT RAISE(ABORT, 'お問い合わせ内容が短すぎます。');
END;

INSERT INTO services (title, summary, icon, sort_order) VALUES
('マンション内装リフォーム', '壁紙・床材・建具・収納まで、住みながら進めやすい工程で室内をやさしく刷新します。', 'home', 10),
('トイレ・キッチン改修', '水回りの使い勝手、清掃性、収納量を見直し、毎日の家事が軽くなる空間へ整えます。', 'settings', 20),
('原状回復・賃貸物件対応', 'オーナー様・管理会社様向けに、退去後の修繕、部分補修、短納期の内装更新へ対応します。', 'future', 30),
('収納・造作家具', '暮らし方に合わせて、可動棚・ワークスペース・造作収納を設計し、すっきり使える住まいにします。', 'album', 40),
('省エネ・断熱リフォーム', '内窓、断熱材、設備更新など、体感温度と光熱費のバランスを考えた提案を行います。', 'bolt', 50),
('住まいの小修繕', 'ドア調整、手すり設置、網戸交換など、暮らしの小さな困りごとも丁寧に対応します。', 'check', 60);

INSERT INTO officers (name, title, role_code, message, sort_order) VALUES
('村上亮介', 'CEO / 代表取締役社長', 'CEO', '住まいの悩みを、相談しやすく、わかりやすく、安心できる形に変えることを大切にしています。', 10),
('如月長門', '代表取締役副社長 CFO', 'CFO', '費用の透明性と計画性を重視し、納得して進められるリフォーム体験を支えます。', 20),
('キム・キャット', '取締役執行役員専務 CHRO', 'CHRO', '職人・設計・管理のチーム力を高め、人にやさしい施工体制をつくります。', 30);

INSERT INTO branches (area, name, description, sort_order) VALUES
('東京', '東京本社', '首都圏全体の法人・大型案件の相談窓口です。', 10),
('千葉', '千葉拠点', '本千葉を中心に、地域密着のリフォーム相談を承ります。', 20),
('神奈川', '神奈川拠点', 'マンション内装と水回り改修を中心に対応します。', 30),
('埼玉', '埼玉拠点', '戸建て・集合住宅の小修繕から改修まで対応します。', 40),
('山口', '山口拠点', '西日本エリアの協力施工ネットワークを支えます。', 50),
('大阪', '大阪拠点', '関西圏のリフォーム・原状回復案件に対応します。', 60),
('名古屋', '名古屋拠点', '中部エリアの内装・設備改修を担当します。', 70),
('札幌', '札幌拠点', '寒冷地の断熱・水回り相談にも対応します。', 80);

INSERT INTO histories (year_label, title, body, sort_order) VALUES
('2006年5月', '村上工務店 設立', '千葉市中央区で、地域密着の住まい修繕事業として創業。', 10),
('2010年', 'マンション内装リフォームを本格化', '壁紙・床材・建具の更新を中心に、集合住宅向けの施工体制を整備。', 20),
('2015年', '水回り専門チームを設置', 'トイレ・キッチン・洗面の改修提案を強化。', 30),
('2020年', '首都圏拠点を拡張', '東京・神奈川・埼玉の相談窓口を整備し、施工管理体制を拡充。', 40),
('2026年3月', '2025年度連結概要を公開', '売上収益3兆5029億円、従業員26人として概要を整理。', 50);

INSERT INTO works (title, category, location, summary, sort_order) VALUES
('明るい壁紙で整えた中古マンション', '内装リフォーム', '千葉市中央区', '淡いグリーンをアクセントに、リビングと廊下の印象をやわらかく刷新。', 10),
('掃除しやすいコンパクトトイレ', 'トイレ改修', '船橋市', '節水型トイレと手洗い収納を組み合わせ、清潔感と収納量を両立。', 20),
('家事動線を短くしたキッチン', 'キッチン改修', '習志野市', '収納計画と照明を見直し、調理から片付けまでの流れを改善。', 30),
('賃貸物件の短期原状回復', '原状回復', '東京都江東区', '退去後の壁紙・床補修を短納期で整え、募集開始までを支援。', 40);

INSERT INTO kpis (label, value, unit, memo, sort_order) VALUES
('設立', '2006年5月', '', '地域密着の修繕事業として創業', 10),
('従業員', '26', '人', '2026年3月末現在', 20),
('売上収益', '3兆5029億', '円', '2025年度連結概要', 30),
('営業時間', '10:00〜18:00', '', '不定休', 40);

INSERT INTO service_share (label, percent, sort_order) VALUES
('内装リフォーム', 36, 10),
('水回り改修', 28, 20),
('原状回復', 18, 30),
('造作・収納', 10, 40),
('小修繕', 8, 50);
