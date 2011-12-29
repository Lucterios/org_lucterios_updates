<?php
// 	This file is part of Diacamma, a software developped by "Le Sanglier du Libre" (http://www.sd-libre.fr)
// 	Thanks to have payed a retribution for using this module.
// 
// 	Diacamma is free software; you can redistribute it and/or modify
// 	it under the terms of the GNU General Public License as published by
// 	the Free Software Foundation; either version 2 of the License, or
// 	(at your option) any later version.
// 
// 	Diacamma is distributed in the hope that it will be useful,
// 	but WITHOUT ANY WARRANTY; without even the implied warranty of
// 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// 	GNU General Public License for more details.
// 
// 	You should have received a copy of the GNU General Public License
// 	along with Lucterios; if not, write to the Free Software
// 	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
// 
// 		Contributeurs: Fanny ALLEAUME, Pierre-Olivier VERSCHOORE, Laurent GAY
// Method file write by SDK tool
// --- Last modification: Date 28 December 2011 23:33:47 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@supprime les modules non installable par le jeu des dépendance
//@PARAM@ 

function ModulesToUpgrade_APAS_clearImpossibleModules(&$self)
{
//@CODE_ACTION@
$modif=true;
global $rootPath;
if(!isset($rootPath))
	$rootPath = "./";
while ($modif) {
	$modif=false;
	$module=new DBObj_org_lucterios_updates_ModulesToUpgrade;
	$module->find();
	while ($module->fetch()) {
		if (!$module->checkDependante()) {
			$module->delete();
			$modif=true;
		}
	}
	if (!$modif) {
		require_once('CORE/extensionManager.inc.php');
		$ext_names=getExtensions($rootPath);
		foreach($ext_names as $extname=>$extpath) {
			if (!is_file($extpath."setup.inc.php")) {
				require_once("CORE/Lucterios_Error.inc.php");
				throw new LucteriosException(CRITIC,"fichier '$extpath.setup.inc.php' inconnu!");
			}
			require($extpath."setup.inc.php");
			foreach($depencies as $depency) {
				$version_max=$depency->version_majeur_max.".".$depency->version_mineur_max;
				$version_min=$depency->version_majeur_min.".".$depency->version_mineur_min;
				$module=new DBObj_org_lucterios_updates_ModulesToUpgrade;
				$module->module=$depency->name;
				$module->find();
				if ($module->fetch()) {
					$current_version_list=explode('.',$module->version);
					$current_version=$current_version_list[0].'.'.$current_version_list[1];
					if (version_compare($current_version,$version_max,'>') || version_compare($current_version,$version_min,'<')) {
						$other_module=new DBObj_org_lucterios_updates_ModulesToUpgrade;
						$other_module->module=$extname;
						$other_module->find();
						if ($other_module->fetch()) {
							$depend_list=$other_module->getDependDesc();
							foreach($depend_list as $depend_item) {
								if ($depend_item[0]==$module->module) {
									$other_version_max=$depend_item[1];
									$other_version_min=$depend_item[2];
								}
							}
							if (version_compare($current_version,$other_version_max,'>') || version_compare($current_version,$other_version_min,'<'))
								$modif=true;
						}
						else
							$modif=true;
						if ($modif)
							$module->delete();
					}
				}
			}
		}
	}
}
//@CODE_ACTION@
}

?>
