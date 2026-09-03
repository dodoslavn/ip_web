<?php


require_once('detector.php');

$browser = new BrowserDetection();

echo $browser;
exit;

$userAgent       = $browser->getUserAgent();               //string
$browserName     = $browser->getName();                    //string
$browserVer      = $browser->getVersion();                 //string
$platformFamily  = $browser->getPlatform();                //string
$platformVer     = $browser->getPlatformVersion(true);     //string
$platformName    = $browser->getPlatformVersion();         //string
$platformIs64bit = $browser->is64bitPlatform();            //boolean
$isMobile        = $browser->isMobile();                   //boolean
$isRobot         = $browser->isRobot();                    //boolean
$isInIECompat    = $browser->isInIECompatibilityView();    //boolean
$strEmulatedIE   = $browser->getIECompatibilityView();     //string
$arrayEmulatedIE = $browser->getIECompatibilityView(true); //array('browser' => '', 'version' => '')
$isChromeFrame   = $browser->isChromeFrame();              //boolean
$isAol           = $browser->isAol();                      //boolean
$aolVer          = $browser->getAolVersion();              //string

//Test if the user uses Microsoft Edge
if ($browser->getName() == BrowserDetection::BROWSER_EDGE) {
    echo 'You are using Edge!';
}

//Test if the user uses specific versions of Internet Explorer
if ($browser->getName() == BrowserDetection::BROWSER_IE) {
    //As you can see you can compare major and minor versions under a string format '#.#.#' (no limit in depth)
    if ($browser->compareVersions($browser->getVersion(), '11.0.0.0') < 0) {
        echo 'You are using IE < 11.';
    }

    if ($browser->compareVersions($browser->getVersion(), '11.0.0') == 0) {
        echo 'You are using IE 11.';
    }

    if ($browser->compareVersions($browser->getVersion(), '11.0') > 0) {
        echo 'You are using IE > 11.';
    }

    if ($browser->compareVersions($browser->getVersion(), '11') >= 0) {
        echo 'You are using IE 11 or greater.';
    }
}

//Test a new user agent and output the instance of BrowserDetection as a string
$browser->setUserAgent('Mozilla/5.0 (Windows NT 6.3; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0');
echo $browser;
