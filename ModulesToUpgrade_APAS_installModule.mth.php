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
// --- Last modification: Date 11 September 2008 0:17:52 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@Installation des téléchargement
//@PARAM@ 

function ModulesToUpgrade_APAS_installModule(&$self)
{
//@CODE_ACTION@
$dir_usr="usr/org_lucterios_updates/";
require_once "CORE/extensionManager.inc.php";
require_once "CORE/dbcnx.inc.php";
global $connect;
$connect->begin();
try
{
	$extlist=array();
	$modules=new DBObj_org_lucterios_updates_ModulesToUpgrade;
	$modules->etat=3;
	$modules->find();
	while ($modules->fetch())
	{
		$lpk_file=$dir_usr.$modules->module.".lpk";
		if (is_file($lpk_file) && $modules->isPossible()) {
			$is_client=($modules->famille=='client');
			if ($modules->famille=='applis')
				$module_name='applis';
			else
				$module_name=$modules->module;
			$dir_module=Extension::getFolder($module_name,'',$is_client);
			$ext_backup=$dir_usr.$modules->module;
			if(is_dir($dir_module) && !$is_client && ($modules->module!='SDK'))
				rename($dir_module,$ext_backup);

			require_once("Archive/Tar.php");
			$tar_object = new Archive_Tar($lpk_file,'gz');
			if ($modules->module=='CORE')
				$res=$tar_object->extract('.');
			else
				$res=$tar_object->extract($dir_module);
			if (!PEAR::isError($res))
			{
				$extlist[]=array($module_name,!$is_client);
				$modules->etat=5;
				$modules->update();
			} else {
				$modules->etat=4;
				$modules->update();
				throw new Exception($res->getMessage());
			}
		}
	}
	$install=refreshDataBase(true);
	logAutre($install);
	$connect->commit();
	return array($extlist,$install);
} catch (Exception $e) {
	$connect->rollback();
	$dh = opendir($dir_usr);
	while (($file = readdir($dh)) != false)
	{
		if (($file=='.') || ($file=='..'))
			continue;
		if(is_dir($dir_usr.'/'.$file))
		{
			$dir_module=Extension::getFolder($file);
			if ($dir_module===false)
				$dir_module=Extension::getFolder($file,'',true);
			if (is_dir($dir_module))
			{
				deleteDir($dir_module);
				rename($dir_usr.'/'.$file,$dir_module);
			}
		}
	}
	closedir($dh);
	return array(null,$e->getMessage());
}
//@CODE_ACTION@
}

?>
