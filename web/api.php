<?php
$conn = mysqli_connect("localhost", "ipfordosk", "ipfordosk1", "ipfordosk");

$bi = mysqli_real_escape_string($conn, $_GET['bi']); #browser info
$rc = mysqli_real_escape_string($conn, $_GET['rc']); #return code

#if ($bi == "" or $rc == "") die;

$sql = mysqli_query($conn, "SELECT COUNT(*) as pocet FROM known WHERE full = '$bi'");
$sql = mysqli_fetch_array($sql);
if ( $sql['pocet'] == 0 )
	{
	require_once('detector.php');
	$object = new BrowserDetection();
	$browserName = $object->getName();
	$browserVer = $object->getVersion(); 
	$OSver= $object->getPlatformVersion(); 
	$OS = $object->getPlatform(); 
	
	if ($object->is64bitPlatform())
		{ $OSver .= " 64bit"; }	
	if ( $sql['pocet'] == 0 )
		{ mysqli_query($conn, "INSERT INTO known VALUES( NULL, '$browserName', '$browserVer', '$OS', '$OSver', '$bi')"); }	
	}

$sql = mysqli_query($conn, "SELECT COUNT(*) as pocet FROM known WHERE full = '$bi'");
$sql = mysqli_fetch_array($sql);
if ( $sql['pocet'] > 0 )
	{
	$sql = mysqli_query($conn, "SELECT * FROM known WHERE full = '$bi'");
	$sql = mysqli_fetch_array($sql);
	if ($rc == 1) #browser (Firefox)
		{ echo $sql['browser']; }
	elseif ($rc == 2) 
		{ echo $sql['bver']; } #browser version (53.0)
	elseif ($rc == 3) 
		{ echo $sql['bver']; } #os (Windows)
	elseif ($rc == 4) 
		{ echo $sql['bver']; } #osver (Windwos 8.1 64bit)
	}
?>
