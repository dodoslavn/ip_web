<?php

if (false)
	{
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	}

$datum = StrFTime("%d.%m.%Y", Time());
$time = StrFTime("%H:%M", Time());

if (!empty($_SERVER["HTTP_CF_CONNECTING_IP"])) 
	{ $ip = $_SERVER["HTTP_CF_CONNECTING_IP"]; }
else 
	{ $ip = $_SERVER["REMOTE_ADDR"]; }

#$ip = $_SERVER['REMOTE_ADDR'];
$binfo = $_SERVER['HTTP_USER_AGENT'];

require_once('detector.php');

$object = new BrowserDetection();

$browserName = $object->getName();
$browserVer = $object->getVersion();
$OSver= $object->getPlatformVersion();
$OS = $object->getPlatform();
$bot = $object->isRobot();

if ($bot == "true") $bot = 1;
else $bot = 0;

if ($object->is64bitPlatform())
	{ $OSver .= " 64bit"; }
	
	
############################################################################ 
$conn = mysqli_connect("localhost", "ipfordosk", "ipfordosk1", "ipfordosk");

$sql = mysqli_query($conn, "SELECT COUNT(*) as pocet FROM known WHERE full = '$binfo'");
$sql = mysqli_fetch_array($sql);
if ( $sql['pocet'] == 0 )
	{ mysqli_query($conn, "INSERT INTO known VALUES( NULL, '$browserName', '$browserVer', '$OS', '$OSver', '$bot', '$binfo')"); }


############################################################################ 
if (strpos($binfo, ' libwww-perl') != false )
	{
	echo "Your IP: ".$ip."\n";
	die;
	}

if ( $OSver == "unknown" ) $OSver = "";
echo '
<html>
<head>
  <title>Moja IP adresa</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
  <meta name="author" content="Dodoslav Novák" > 
  <link href="favicon.ico" rel="icon" type="image/ico" >
</head>

<body>
  <center>
    <span style="font-size: 12px">'.$binfo.'</span> - <a href="/tutorial.php">generator</a>
    <h2>
      <br>IP: '.$ip.'
      <br><br>Operačný systém: '.$OS." ".$OSver.'
      <br><br>Prehliadač: '.$browserName.' ('.$browserVer.')
    </h2>
  </center>
<body>
</html>';
?>
