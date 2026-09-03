<?php

use Wolfcast\BrowserDetection;

require_once('detector.php');

$bi = $_GET['bi'] ?? '';  // user agent string
$rc = (int)($_GET['rc'] ?? 0);  // 1=browser name, 2=browser ver, 3=OS, 4=OS ver

if ($bi === '') exit;

$browser = new BrowserDetection();
$browser->setUserAgent($bi);

if ($rc === 1) echo $browser->getName();
elseif ($rc === 2) echo $browser->getVersion();
elseif ($rc === 3) echo $browser->getPlatform();
elseif ($rc === 4) {
    $osver = $browser->getPlatformVersion();
    if ($browser->is64bitPlatform()) $osver .= ' 64bit';
    echo $osver;
}
