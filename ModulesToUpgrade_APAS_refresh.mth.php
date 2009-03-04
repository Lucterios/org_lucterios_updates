<?php
// 
//     This file is part of Lucterios.
// 
//     Lucterios is free software; you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation; either version 2 of the License, or
//     (at your option) any later version.
// 
//     Lucterios is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
// 
//     You should have received a copy of the GNU General Public License
//     along with Lucterios; if not, write to the Free Software
//     Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
// 
// 	Contributeurs: Fanny ALLEAUME, Pierre-Olivier VERSCHOORE, Laurent GAY
//  // Method file write by SDK tool
// --- Last modification: Date 04 March 2009 19:47:32 By  ---

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
$format='d F Y h:i:s A';
$refresh = true;
if($date_last_refresh != '') {
	$date = strtotime($date_last_refresh);
	$now = strtotime(date($format));
	//echo"<!-- date=". print_r($date, true)." -->\n";
	//echo"<!-- now=". print_r($now, true)." -->\n";
	$refresh = (($now-$date)>(3600*24));
	echo"<!-- refresh=$refresh= (n=$now<(d=$date+t=24h)) Dif=".(($now-$date)/60.0)." -->\n";
}
if($refresh) {
	global $rootPath;
	if(!isset($rootPath))
		$rootPath = "";
	$res = false;
	$ModulesToUpgrade = new DBObj_org_lucterios_updates_ModulesToUpgrade;
	$ModulesToUpgrade->find();
	while($ModulesToUpgrade->fetch())
		$ModulesToUpgrade->delete();
	require_once"CORE/extensionManager.inc.php";
	$dir = $rootPath.'usr/org_lucterios_updates/'; 
	deleteDir($dir);
	$UpdateServers = new DBObj_org_lucterios_updates_UpdateServers;
	$UpdateServers->actif = 'o';
	$UpdateServers->find();
	while($UpdateServers->fetch()) {
		$res = $UpdateServers->rafraichir() || $res;
	}
	$self->clearImpossibleModules();
	if($res) {
		$params = new DBObj_CORE_extension_params;
		$params->extensionId = 'org_lucterios_updates';
		$params->paramName = 'DateLastRefresh';
		$params->find();
		if ($params->fetch()) {
			$params_upgrade = new DBObj_CORE_extension_params;
			$params_upgrade->get($params->id);
			$params_upgrade->value = date($format);
			$params_upgrade->update();
		}
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
