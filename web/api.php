<?php

use Wolfcast\BrowserDetection;

require_once('detector.php');

$conn = mysqli_connect("localhost", "ipfordosk", "ipfordosk1", "ipfordosk");

$bi = mysqli_real_escape_string($conn, $_GET['bi'] ?? '');  // browser info (user agent string)
$rc = (int)($_GET['rc'] ?? 0);                              // return code: 1=browser 2=bver 3=os 4=osver

if ($bi === '') exit;

$stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS pocet FROM known WHERE full = ?");
mysqli_stmt_bind_param($stmt, 's', $bi);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_array(mysqli_stmt_get_result($stmt));

if ($row['pocet'] == 0) {
    $browser = new BrowserDetection();
    $browser->setUserAgent($bi);
    $browserName = $browser->getName();
    $browserVer  = $browser->getVersion();
    $OS          = $browser->getPlatform();
    $OSver       = $browser->getPlatformVersion();
    if ($browser->is64bitPlatform()) $OSver .= ' 64bit';

    $ins = mysqli_prepare($conn, "INSERT INTO known VALUES(NULL, ?, ?, ?, ?, 0, ?)");
    mysqli_stmt_bind_param($ins, 'sssss', $browserName, $browserVer, $OS, $OSver, $bi);
    mysqli_stmt_execute($ins);
}

$sel = mysqli_prepare($conn, "SELECT * FROM known WHERE full = ? LIMIT 1");
mysqli_stmt_bind_param($sel, 's', $bi);
mysqli_stmt_execute($sel);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($sel));

if ($row) {
    if ($rc === 1) echo $row['browser'];
    elseif ($rc === 2) echo $row['bver'];
    elseif ($rc === 3) echo $row['OS'];
    elseif ($rc === 4) echo $row['OSver'];
}
