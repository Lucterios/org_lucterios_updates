<?php
// Method file write by SDK tool
// --- Last modification: Date 05 June 2008 23:22:18 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/UpdateServers.tbl.php');
require_once('CORE/extension_params.tbl.php');
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@Rafraichir et controler les modules à télécharger
//@PARAM@ 

function ModulesToUpgrade_APAS_refresh(&$self)
{
//@CODE_ACTION@
$params = new DBObj_CORE_extension_params;
$param_val = $params->getParameters('org_lucterios_updates');
$date_last_refresh = trim($param_val['DateLastRefresh']);
//
$format = '%d/%m/%Y %H:%M:%S';
$refresh = true;
if($date_last_refresh != '') {
	$dates = strptime($date_last_refresh,$format);
	$nows = strptime( strftime($format),$format);
	echo"<!-- dates=". print_r($dates, true)." -->\n";
	echo"<!-- now=". print_r( strptime( strftime($format),$format), true)." -->\n";
	$date = mktime($dates['tm_hour'],$dates['tm_min'],$dates['tm_sec'],$dates['tm_mon'],$dates['tm_mday'],$dates['tm_year']);
	$now = mktime($nows['tm_hour'],$nows['tm_min'],$nows['tm_sec'],$nows['tm_mon'],$nows['tm_mday'],$nows['tm_year']);
	$refresh = (($now-$date)>(3600*24));
	echo"<!-- refresh=$refresh= (n=$now<(d=$date+t=24h)) Dif=".(($now-$date)/60.0)." -->\n";
}
if($refresh) {
	$res = false;
	$ModulesToUpgrade = new DBObj_org_lucterios_updates_ModulesToUpgrade;
	$ModulesToUpgrade->find();
	while($ModulesToUpgrade->fetch())$ModulesToUpgrade->delete();
	require_once"CORE/extensionManager.inc.php";
	$dir = 'usr/org_lucterios_updates/'; deleteDir($dir);
	$UpdateServers = new DBObj_org_lucterios_updates_UpdateServers;
	$UpdateServers->actif = 'o';
	$UpdateServers->find();
	while($UpdateServers->fetch()) {
		$res = $UpdateServers->rafraichir() || $res;
	}
	$ModulesToUpgrade = new DBObj_org_lucterios_updates_ModulesToUpgrade;
	$ModulesToUpgrade->find();
	while($ModulesToUpgrade->fetch())if(!$ModulesToUpgrade->isPossible())$ModulesToUpgrade->delete();
	if($res) {
		$params = new DBObj_CORE_extension_params;
		$params->extensionId = 'org_lucterios_updates';
		$params->paramName = 'DateLastRefresh';
		$params->find();
		$params->fetch();
		$params->value = strftime($format);
		$params->update();
	}
}
$res = true;
$ModulesToUpgrade = new DBObj_org_lucterios_updates_ModulesToUpgrade;
$ModulesToUpgrade->etat = 0;
$ModulesToUpgrade->find();
while($ModulesToUpgrade->fetch()) {
	$serveur = $ModulesToUpgrade->getField('serveur');
	$res = $res && $serveur->accessible();
}
return $res;
//@CODE_ACTION@
}

?>
