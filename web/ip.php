  <?php
if ( isset($_GET['binfo']) && $_GET['binfo'] != "" )
    { $binfo = " ".$_GET['binfo']; }
else die;


require_once('detector.php');

$object = new BrowserDetection($binfo);

$browserName = $object->getName();
$browserVer = $object->getVersion(); 
$OSver = $object->getPlatformVersion(); 
$OS = $object->getPlatform();
$bot = $object->isRobot(); 


if ($bot == "true") $bot = 1;
else $bot = 0;

if ($object->is64bitPlatform())
	{ $OSver .= " 64bit"; }

if ( $OSver == "unknown" && strpos($binfo, "Linux x86_64") == true )
    { $OSver = "amd64"; }

$OSver = str_replace("Windows","",$OSver);
############################################################################ 
$conn = mysqli_connect("localhost", "ipfordosk", "ipfordosk1", "ipfordosk");

$sql = mysqli_query($conn, "SELECT COUNT(*) as pocet FROM known WHERE full = '$binfo'");
$sql = mysqli_fetch_array($sql);
if ( $sql['pocet'] == 0 )
	{ mysqli_query($conn, "INSERT INTO known VALUES( NULL, '$browserName', '$browserVer', '$OS', '$OSver','$bot','$binfo')"); }




?>
