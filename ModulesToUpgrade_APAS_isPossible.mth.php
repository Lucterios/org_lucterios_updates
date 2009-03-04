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
// --- Last modification: Date 04 March 2009 19:46:53 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@Verifie si ce module peut s'installer (suivant dépendance)
//@PARAM@ 

function ModulesToUpgrade_APAS_isPossible(&$self)
{
//@CODE_ACTION@
$res=true;
$depents=$self->getDependDesc();

//echo "<!-- ### module=".$self->module." - famille=".$self->famille." deps=".print_r($depents,true)." -->\n";

require_once "CORE/extensionManager.inc.php";
foreach($depents as $depent)
if (count($depent)==4)
{
	$mod_dep=$depent[0];
	$versMax=$depent[1];
	$versMin=$depent[2];
	$ext=new Extension($mod_dep,Extension::getFolder($mod_dep));
	if (!$ext->isVersionsInRange($versMax,$versMin))
	{
		$mod=new DBObj_org_lucterios_updates_ModulesToUpgrade;
		$mod->module=$mod_dep;
		$mod->find();
		if ($mod->fetch())
		{
			$pos_p=strpos($mod->version,'.');
			$pos_p=strpos($mod->version,'.',$pos_p+1);
			$versMod=substr($mod->version,0,$pos_p);
			if ((version_compare($versMod,$versMax)>0) || (version_compare($versMod,$versMin)<0))
				$res=false;
			//echo "<!-- mod=$mod_dep - versMax=$versMax -	versMin=$versMin - versMod=$versMod ($res) -->\n";
		}
		else if ($depent[3]=='n')
			$res=false;
	}
}
if ($res && ($self->famille!='')) {
	global $rootPath;
	if(!isset($rootPath))
		$rootPath = "";
	$list_ext=getExtensions($rootPath);
	foreach($list_ext as $extName=>$extDir)
	{
		$ext=new Extension($extName,$extDir);
		//echo "<!-- ext=".$ext->Name." - Fam=".$ext->famille." -->\n";
		if (($self->famille!='applis') && ($ext->Name!=$self->module) && ($ext->famille==$self->famille))
		{
			$res=false;
			foreach($depents as $depent)
			{
				if ($depent[0]==$ext->Name)
					$res=true;
			}
			if (!$res) break;
		}
	}
}
//echo "<!-- ### module=".$self->module." - famille=".$self->famille." deps=".print_r($depents,true)." ==> IsPossible=$res -->\n";
return $res;
//@CODE_ACTION@
}

?>
