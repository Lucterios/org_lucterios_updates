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
// --- Last modification: Date 04 March 2009 19:45:31 By  ---

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

	if (!modif) {
		global $rootPath;
		if(!isset($rootPath))
			$rootPath = "";
		require_once('CORE/extensionManager.inc.php');
		$ext_names=getExtensions($rootPath);
		foreach($ext_names as $extname=>$extpath) {
			require($extpath."setup.inc.php");
			foreach($depencies as $depency) {
				$version_max=$depency->version_majeur_max.".".$depency->version_mineur_max;
				$version_min=$depency->version_majeur_min.".".$depency->version_mineur_min;
				$module=new DBObj_org_lucterios_updates_ModulesToUpgrade;
				$module->module=$depency->name;
				if ($module->find() && $module->fetch()) {
					if (version_compare($module->version,$version_max,'>') || version_compare($module->version,$version_min,'<')) {
						$module->delete();
						$modif=true;
					}
				}
			}
		}
	}
}
//@CODE_ACTION@
}

?>
