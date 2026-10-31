<?php
/* =====================================================================
   קבלת לידים מהטפסים – אינסטלטור ברגע
   1. בודק את הפרטים
   2. שומר שורה בקובץ data/leads.csv (גיבוי – הקובץ חסום לגישה מבחוץ)
   3. שולח מייל לכתובת שב-LEAD_EMAIL
   מחזיר JSON: {"ok":true} או {"ok":false,"error":"..."}
   ===================================================================== */
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Robots-Tag: noindex');

function out($ok, $error = '', $code = 200) {
    http_response_code($code);
    echo json_encode($ok ? ['ok' => true] : ['ok' => false, 'error' => $error], JSON_UNESCAPED_UNICODE);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') out(false, 'method', 405);

// בקשות רק מהאתר עצמו
$host = $_SERVER['HTTP_HOST'] ?? '';
$origin = $_SERVER['HTTP_ORIGIN'] ?? ($_SERVER['HTTP_REFERER'] ?? '');
if ($origin !== '' && parse_url($origin, PHP_URL_HOST) !== parse_url('http://' . $host, PHP_URL_HOST)) out(false, 'origin', 403);

function field($k, $max) {
    $v = trim((string)($_POST[$k] ?? ''));
    $v = preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $v);   // בלי תווי שליטה ושורות חדשות
    return mb_substr($v, 0, $max, 'UTF-8');
}

// שדה מלכודת לרובוטים – מעמידים פנים שהכול עבר
if (field('website', 100) !== '') out(true);

$name   = field('name', 60);
$issue  = field('issue', 60);
$source = field('source', 40);
$page   = field('page', 120);
$fromAds = field('from_ads', 1) === '1';

// טלפון ישראלי תקין
$d = preg_replace('/[^\d+]/', '', (string)($_POST['phone'] ?? ''));
if (strpos($d, '+972') === 0) $d = '0' . substr($d, 4);
elseif (strpos($d, '972') === 0 && strlen($d) >= 11) $d = '0' . substr($d, 3);
$d = preg_replace('/\D/', '', $d);
if (strlen($d) === 9 && $d[0] !== '0') $d = '0' . $d;
if (!preg_match('/^0(?:5\d{8}|7\d{8}|[23489]\d{7})$/', $d)) out(false, 'phone', 422);
$phone = $d;

$allowedIssues = ['', 'סתימה', 'נזילה / רטיבות', 'ברז או סוללה', 'דוד מים', 'אסלה / ניאגרה', 'שיפוץ או החלפת צנרת', 'אחר', 'חירום'];
if (!in_array($issue, $allowedIssues, true)) $issue = 'אחר';
if (!preg_match('#^/[\w\-/.]*$#u', $page)) $page = '/';

$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) @mkdir($dataDir, 0750, true);

// הגבלה פשוטה: עד 6 שליחות ל-10 דקות מאותה כתובת
$ip = $_SERVER['REMOTE_ADDR'] ?? '0';
$rlFile = $dataDir . '/rate-' . substr(hash('sha256', $ip . __DIR__), 0, 16) . '.txt';
$now = time();
$hits = array_filter(array_map('intval', @file($rlFile, FILE_IGNORE_NEW_LINES) ?: []), function ($t) use ($now) { return $t > $now - 600; });
if (count($hits) >= 6) out(false, 'rate', 429);
$hits[] = $now;
@file_put_contents($rlFile, implode("\n", $hits), LOCK_EX);
// ניקוי קבצי הגבלה ישנים מדי פעם
if (mt_rand(1, 50) === 1) foreach (glob($dataDir . '/rate-*.txt') ?: [] as $f) if (filemtime($f) < $now - 3600) @unlink($f);

$tz = new DateTimeZone('Asia/Jerusalem');
$when = (new DateTime('now', $tz))->format('Y-m-d H:i');
$adsText = $fromAds ? 'גוגל אדס' : 'אתר / חיפוש רגיל';

// גיבוי לקובץ (מנטרל נוסחאות אקסל בתחילת תא)
$csvFile = $dataDir . '/leads.csv';
$isNew = !file_exists($csvFile);
$saved = false;
if ($fh = @fopen($csvFile, 'a')) {
    if (flock($fh, LOCK_EX)) {
        if ($isNew) { fwrite($fh, "\xEF\xBB\xBF"); fputcsv($fh, ['תאריך', 'שם', 'טלפון', 'תקלה', 'טופס', 'מקור', 'עמוד']); }
        $row = array_map(function ($v) { return preg_match('/^[=+\-@]/', $v) ? "'" . $v : $v; }, [$when, $name, $phone, $issue, $source, $adsText, $page]);
        $saved = fputcsv($fh, $row) !== false;
        flock($fh, LOCK_UN);
    }
    fclose($fh);
}

// מייל
$siteHost = preg_replace('/^www\./', '', preg_replace('/[^A-Za-z0-9.\-]/', '', explode(':', $host)[0]));
$from = MAIL_FROM !== '' ? MAIL_FROM : ('no-reply@' . ($siteHost ?: 'localhost'));
$subject = 'ליד חדש מהאתר – ' . ($name !== '' ? $name . ' ' : '') . $phone . ($issue ? ' (' . $issue . ')' : '');

$rows = [
    'שם' => $name ?: '—',
    'טלפון' => $phone,
    'תקלה' => $issue ?: '—',
    'טופס' => $source ?: '—',
    'מקור' => $adsText,
    'עמוד' => $page,
    'זמן' => $when,
];
$html = '<div dir="rtl" style="font-family:Arial,sans-serif;font-size:15px">'
    . '<h2 style="margin:0 0 12px">ליד חדש – ' . htmlspecialchars(SITE_NAME) . '</h2><table cellpadding="6" style="border-collapse:collapse">';
foreach ($rows as $k => $v) {
    $html .= '<tr><td style="border:1px solid #ddd;background:#f5f8fb"><b>' . htmlspecialchars($k) . '</b></td><td style="border:1px solid #ddd">' . htmlspecialchars($v) . '</td></tr>';
}
$wa = 'https://wa.me/972' . substr($phone, 1);
$html .= '</table><p><a href="tel:' . $phone . '">להתקשר</a> · <a href="' . $wa . '">לכתוב בווטסאפ</a></p></div>';

$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset=UTF-8',
    'From: =?UTF-8?B?' . base64_encode(SITE_NAME) . '?= <' . $from . '>',
    'X-Mailer: PHP',
];
$sent = @mail(LEAD_EMAIL, '=?UTF-8?B?' . base64_encode($subject) . '?=', $html, implode("\r\n", $headers), '-f' . $from);

// גם אם המייל נכשל, הליד נשמר בקובץ – ולכן מחזירים הצלחה אם לפחות אחד מהם עבר
if ($sent || $saved) out(true);
out(false, 'send', 500);
