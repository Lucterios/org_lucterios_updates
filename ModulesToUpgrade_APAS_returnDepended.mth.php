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
// --- Last modification: Date 04 March 2009 19:49:11 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@
//@PARAM@ 

function ModulesToUpgrade_APAS_returnDepended(&$self)
{
//@CODE_ACTION@
$pos_p=strpos($self->version,'.');
$pos_p=strpos($self->version,'.',$pos_p+1);
$versMod=substr($self->version,0,$pos_p);
global $rootPath;
if(!isset($rootPath))
	$rootPath = "";

$list_depended=array();
$mod=new DBObj_org_lucterios_updates_ModulesToUpgrade;
$mod->find();
while ($mod->fetch())
{
	$res=false;
	$depents=$mod->getDependDesc();
	foreach($depents as $depent)
		if ((count($depent)==4) && ($depent[0]==$self->module))
		{
			$versMax=$depent[1];
			$versMin=$depent[2];
			$res=((version_compare($versMod,$versMax)<=0) && (version_compare($versMod,$versMin)>=0));
			/*if ($res && ($depent[3]=='o'))
			{
				$res=false;
				$ext=new Extension($self->module,Extension::getFolder($self->module,$rootPath));
				if (($ext->getPHPVersion()=='0.0.0.0') || ($ext->isVersionsInRange($versMax,$versMin)))
					$res=true;
			}*/
			break;
		}
	if ($res)
		$list_depended[]=$mod->module;
}
return $list_depended;
//@CODE_ACTION@
}

?>
