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
//  // library file write by SDK tool
// --- Last modification: Date 30 August 2008 16:37:51 By  ---

//@BEGIN@

function install_org_lucterios_updates($ExensionVersions) {
	$text = "";
	require_once"CORE/extension_params.tbl.php";
	$params = new DBObj_CORE_extension_params;
	$params->extensionId = 'org_lucterios_updates';
	$params->paramName = 'GUID';
	$params->find();
	if($params->fetch()) {
		require_once"extensions/org_lucterios_updates/GUIDGenerator.inc.php";
		$decode = uuidDecode($params->value);
		if(($params->value == '') || !$decode['check']) {
			$params->value = uuid();
			$params->update();
		}
	}
	if( is_dir('extensions/updates') || is_dir('../extensions/updates')) {
		require_once"CORE/menu.tbl.php";
		$menus = new DBObj_CORE_menu;
		$menus->extensionId = 'updates';
		$menus->find();
		while($menus->fetch())$menus->delete();
	}
	if( is_dir('UpdateClients/java') && ! is_file('UpdateClients/java/setup.inc.php')) {
		$version = file('UpdateClients/java/version.txt');
		$text .= "V1=$version{[newline]}";
		$version = trim($version[0]);
		$text .= "V2=$version{[newline]}";
		list($vmaj,$vmin,$vrel,$vbld) = split(' ',$version);
		$text .= "V1=$vmaj,$vmin,$vrel,$vbld{[newline]}";
		$h = fopen('UpdateClients/java/setup.inc.php','w'); fwrite($h,'<?php'."\n"); fwrite($h,'$extention_name="java";'."\n"); fwrite($h,'$extention_description="Client générique pour toutes applications compatible Lucterios.{[newline]}Nécessite un moteur Java (voir http://www.java.com).";'."\n"); fwrite($h,'$extention_appli="";'."\n"); fwrite($h,'$extention_famille="client";'."\n"); fwrite($h,'$extention_titre="interface graphique Lucterios";'."\n"); fwrite($h,'$extension_libre=true;'."\n"); fwrite($h,"\$version_max=$vmaj;"."\n"); fwrite($h,"\$version_min=$vmin;"."\n"); fwrite($h,"\$version_release=$vrel;"."\n"); fwrite($h,"\$version_build=$vbld;"."\n"); fwrite($h,'$depencies=array();'."\n"); fwrite($h,'$rights=array();'."\n"); fwrite($h,'$menus=array();'."\n"); fwrite($h,'$actions=array();'."\n"); fwrite($h,'$params=array();'."\n"); fwrite($h,'?>'."\n"); fclose($h);
	}
	return $text;
}

//@END@
?>
