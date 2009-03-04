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
// --- Last modification: Date 04 March 2009 19:45:04 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('CORE/extension.tbl.php');
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@controle si les dépendances sont vérifiées
//@PARAM@ 

function ModulesToUpgrade_APAS_checkDependante(&$self)
{
//@CODE_ACTION@
// echo "<!-- ### MODULE=".$self->module." v=".$self->version." ### -->\n";
$depend_list=$self->getDependDesc();
foreach($depend_list as $depend_item) {
	$new_status=false;
	$local_status=false;
	$ext_name=$depend_item[0];
	$version_max=$depend_item[1];
	$version_min=$depend_item[2];
	$option=($depend_item[3]=='o');
	$module=new DBObj_org_lucterios_updates_ModulesToUpgrade;
	$module->module=$ext_name;
	if ($module->find() && $module->fetch())
		$new_status=version_compare($module->version,$version_max,'<=') && version_compare($module->version,$version_min,'>=');
	else
		$new_status=$option;

	$extension=new DBObj_CORE_extension;
	$extension->extensionId=$ext_name;
	if ($extension->find() && $extension->fetch()) {
		$version=$extension->versionMaj.'.'.$extension->versionMin;
		$local_status=version_compare($version,$version_max,'<=') && version_compare($version,$version_min,'>=');
	}
	else
		$local_status=$option;
	// echo "<!-- ** $ext_name [$version_min;$version_max] (new:$new_status/local:$local_status) ** -->\n";
	if (!$new_status && !$local_status)
		return false;
}
if ($self->famille!='') {
	// echo "<!-- 888 Famille=".$self->famille." 888 -->\n";
	global $rootPath;
	if(!isset($rootPath))
		$rootPath = "";
	$list_ext=getExtensions($rootPath);
	foreach($list_ext as $extName=>$extDir)
	{
		$current_extension=new Extension($extName,$extDir);
		if (($self->famille!='applis') && ($extName!=$self->module) && ($current_extension->famille==$self->famille))
		{
			$res=false;
			foreach($depend_list as $depend_item) {
				if ($depend_item[0]==$extName)
					$res=true;
			}
			if (!$res)
				return false;
		}
	}
}
// echo "<!-- 0000000000000000 -->\n";
return true;
//@CODE_ACTION@
}

?>
