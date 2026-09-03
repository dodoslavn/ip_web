<?php

use Wolfcast\BrowserDetection;

require_once('detector.php');

$binfo = trim($_GET['binfo'] ?? '');
if ($binfo === '') exit;

$browser     = new BrowserDetection();
$browser->setUserAgent($binfo);
$browserName = $browser->getName();
$browserVer  = $browser->getVersion();
$OS          = $browser->getPlatform();
$OSver       = $browser->getPlatformVersion();
$isBot       = $browser->isRobot();
$botInt      = ($isBot === 'true' || $isBot === true) ? 1 : 0;

if ($browser->is64bitPlatform()) $OSver .= ' 64bit';
if ($OSver === 'unknown' && strpos($binfo, 'Linux x86_64') !== false) $OSver = 'amd64';
$OSver = str_replace('Windows', '', $OSver);

$conn = mysqli_connect("localhost", "ipfordosk", "ipfordosk1", "ipfordosk");
if (!$conn) exit;

$stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS pocet FROM known WHERE full = ?");
mysqli_stmt_bind_param($stmt, 's', $binfo);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_array(mysqli_stmt_get_result($stmt));

if ($row['pocet'] == 0) {
    $ins = mysqli_prepare($conn, "INSERT INTO known VALUES(NULL, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($ins, 'ssssiis', $browserName, $browserVer, $OS, $OSver, $botInt, $botInt, $binfo);
    mysqli_stmt_execute($ins);
}
