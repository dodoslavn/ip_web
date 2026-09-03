<?php

use Wolfcast\BrowserDetection;

require_once('detector.php');

// Language detection
$supported = ['en', 'sk', 'cs', 'de', 'fr'];
$lang = 'en';
if (!empty($_GET['lang']) && in_array($_GET['lang'], $supported)) {
    $lang = $_GET['lang'];
} elseif (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $al = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    foreach ($supported as $code) {
        if (strpos($al, $code) !== false) { $lang = $code; break; }
    }
}

$strings = [
    'en' => ['title' => 'My IP Address',    'ip' => 'IP Address',  'os' => 'Operating System', 'browser' => 'Browser',    'date' => 'Date',  'time' => 'Time',   'mobile' => 'Mobile', 'bot' => 'Bot', 'yes' => 'Yes', 'no' => 'No'],
    'sk' => ['title' => 'Moja IP adresa',   'ip' => 'IP adresa',   'os' => 'Operačný systém',  'browser' => 'Prehliadač', 'date' => 'Dátum', 'time' => 'Čas',    'mobile' => 'Mobil',  'bot' => 'Bot', 'yes' => 'Áno', 'no' => 'Nie'],
    'cs' => ['title' => 'Moje IP adresa',   'ip' => 'IP adresa',   'os' => 'Operační systém',  'browser' => 'Prohlížeč',  'date' => 'Datum', 'time' => 'Čas',    'mobile' => 'Mobil',  'bot' => 'Bot', 'yes' => 'Ano', 'no' => 'Ne'],
    'de' => ['title' => 'Meine IP-Adresse', 'ip' => 'IP-Adresse',  'os' => 'Betriebssystem',   'browser' => 'Browser',    'date' => 'Datum', 'time' => 'Uhrzeit','mobile' => 'Mobil',  'bot' => 'Bot', 'yes' => 'Ja',  'no' => 'Nein'],
    'fr' => ['title' => "Mon adresse IP",   'ip' => 'Adresse IP',  'os' => "Système d'exp.",   'browser' => 'Navigateur', 'date' => 'Date',  'time' => 'Heure',  'mobile' => 'Mobile', 'bot' => 'Bot', 'yes' => 'Oui', 'no' => 'Non'],
];
$t = $strings[$lang];

// IP detection (Cloudflare-aware)
$ip = !empty($_SERVER['HTTP_CF_CONNECTING_IP'])
    ? $_SERVER['HTTP_CF_CONNECTING_IP']
    : $_SERVER['REMOTE_ADDR'];

$binfo = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Browser/OS detection
$browser     = new BrowserDetection();
$browserName = $browser->getName();
$browserVer  = $browser->getVersion();
$OS          = $browser->getPlatform();
$OSver       = $browser->getPlatformVersion();
$isMobile    = $browser->isMobile();
$isBot       = $browser->isRobot();
$botInt      = ($isBot === 'true' || $isBot === true) ? 1 : 0;

if ($browser->is64bitPlatform()) $OSver .= ' 64bit';
if ($OSver === 'unknown') $OSver = '';

// Plain-text response for command-line clients
if (strpos($binfo, 'libwww-perl') !== false) {
    echo "$ip\n";
    exit;
}

$datum     = date('d.m.Y');
$timeStr   = date('H:i');
$osDisplay = trim("$OS $OSver");
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($t['title']) ?></title>
  <meta name="author" content="Dodoslav Novák">
  <link rel="icon" href="favicon.ico" type="image/ico">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:      #0f1117;
      --surface: #1a1d27;
      --border:  #2a2d3a;
      --accent:  #4f8ef7;
      --text:    #e8eaf0;
      --muted:   #8b90a8;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 40px 48px;
      max-width: 520px;
      width: 100%;
    }

    .ip-block {
      text-align: center;
      margin-bottom: 32px;
      padding-bottom: 28px;
      border-bottom: 1px solid var(--border);
    }

    .ip-label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: var(--muted);
      margin-bottom: 10px;
    }

    .ip-value {
      font-size: 34px;
      font-weight: 700;
      color: var(--accent);
      font-family: 'Courier New', monospace;
      letter-spacing: 1px;
    }

    .info-grid { display: grid; gap: 14px; }

    .info-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .info-key {
      font-size: 12px;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.8px;
    }

    .info-val {
      font-size: 14px;
      font-weight: 500;
      text-align: right;
      max-width: 65%;
    }

    .badge {
      font-size: 11px;
      padding: 2px 10px;
      border-radius: 20px;
      font-weight: 600;
    }
    .badge-yes { background: #1a3a1a; color: #5bc45b; }
    .badge-no  { background: #222436; color: var(--muted); }

    .ua-string {
      margin-top: 28px;
      padding-top: 20px;
      border-top: 1px solid var(--border);
      font-size: 10px;
      color: var(--muted);
      word-break: break-all;
      line-height: 1.6;
    }

    .lang-switcher {
      margin-top: 24px;
      display: flex;
      gap: 8px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .lang-btn {
      font-size: 11px;
      padding: 4px 12px;
      border-radius: 20px;
      text-decoration: none;
      color: var(--muted);
      border: 1px solid var(--border);
      transition: color 0.15s, border-color 0.15s;
    }
    .lang-btn:hover, .lang-btn.active {
      color: var(--accent);
      border-color: var(--accent);
    }

    @media (max-width: 480px) {
      .card { padding: 28px 20px; }
      .ip-value { font-size: 26px; }
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="ip-block">
      <div class="ip-label"><?= $t['ip'] ?></div>
      <div class="ip-value"><?= htmlspecialchars($ip) ?></div>
    </div>

    <div class="info-grid">
      <div class="info-row">
        <span class="info-key"><?= $t['os'] ?></span>
        <span class="info-val"><?= htmlspecialchars($osDisplay ?: '—') ?></span>
      </div>
      <div class="info-row">
        <span class="info-key"><?= $t['browser'] ?></span>
        <span class="info-val"><?= htmlspecialchars("$browserName $browserVer") ?></span>
      </div>
      <div class="info-row">
        <span class="info-key"><?= $t['date'] ?></span>
        <span class="info-val"><?= htmlspecialchars($datum) ?></span>
      </div>
      <div class="info-row">
        <span class="info-key"><?= $t['time'] ?></span>
        <span class="info-val"><?= htmlspecialchars($timeStr) ?></span>
      </div>
      <div class="info-row">
        <span class="info-key"><?= $t['mobile'] ?></span>
        <span class="info-val">
          <span class="badge <?= $isMobile ? 'badge-yes' : 'badge-no' ?>">
            <?= $isMobile ? $t['yes'] : $t['no'] ?>
          </span>
        </span>
      </div>
      <div class="info-row">
        <span class="info-key"><?= $t['bot'] ?></span>
        <span class="info-val">
          <span class="badge <?= $botInt ? 'badge-yes' : 'badge-no' ?>">
            <?= $botInt ? $t['yes'] : $t['no'] ?>
          </span>
        </span>
      </div>
    </div>

    <div class="ua-string"><?= htmlspecialchars($binfo) ?></div>

    <nav class="lang-switcher">
      <?php foreach ($supported as $code): ?>
        <a href="?lang=<?= $code ?>" class="lang-btn <?= $code === $lang ? 'active' : '' ?>">
          <?= strtoupper($code) ?>
        </a>
      <?php endforeach; ?>
    </nav>
  </div>
</body>
</html>
