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
// --- Last modification: Date 07 March 2010 23:05:25 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@
//@PARAM@ excludeOptionnal=false
//@PARAM@ sameOrigine=false
//@PARAM@ moduleNoChecked=array()

function ModulesToUpgrade_APAS_returnDepend(&$self,$excludeOptionnal=false,$sameOrigine=false,$moduleNoChecked=array())
{
//@CODE_ACTION@
global $rootPath;
if(!isset($rootPath))
	$rootPath = "";
$depents=$self->getDependDesc();
$first_list_depends=array();
require_once "CORE/extensionManager.inc.php";
foreach($depents as $depent)
	if (count($depent)==4)
	{
		$res=false;
		$mod_dep=$depent[0];
		$versMax=$depent[1];
		$versMin=$depent[2];

		if (!in_array($mod_dep,$moduleNoChecked)) {
			$mod=new DBObj_org_lucterios_updates_ModulesToUpgrade;
			$mod->module=$mod_dep;
			$mod->find();
			if ($mod->fetch())
			{
				$pos_p=strpos($mod->version,'.');
				$pos_p=strpos($mod->version,'.',$pos_p+1);
				$versMod=substr($mod->version,0,$pos_p);
				$res=((version_compare($versMod,$versMax)<=0) && (version_compare($versMod,$versMin)>=0));
				if ($res && ($depent[3]=='o'))
				{
					$res=$excludeOptionnal;
					$ext=new Extension($mod_dep,Extension::getFolder($mod_dep,$rootPath));
					if (($ext->getPHPVersion()!='0.0.0.0') && (!$ext->isVersionsInRange($versMax,$versMin)) && (($mod->nouveau==$self->nouveau) || !$sameOrigine))
						$res=true;
				}
			}
			if ($res)
				$first_list_depends[]=$mod_dep;
		}
	}

$list_depends=array();
foreach($first_list_depends as $mod_dep) {
	$mod=new DBObj_org_lucterios_updates_ModulesToUpgrade;
	$mod->module=$mod_dep;
	$mod->find();
	if ($mod->fetch()) {
		$other_depends=$mod->returnDepend(false,$sameOrigine,array_merge($moduleNoChecked,$first_list_depends));
		$list_depends=array_merge($list_depends,$other_depends);
	}
	$list_depends[]=$mod_dep;
}
$list_depends=array_unique($list_depends);
return $list_depends;
//@CODE_ACTION@
}

?>
