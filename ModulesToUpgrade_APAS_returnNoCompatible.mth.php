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
// --- Last modification: Date 28 November 2008 12:27:44 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@
//@PARAM@ 

function ModulesToUpgrade_APAS_returnNoCompatible(&$self)
{
//@CODE_ACTION@
$list=array();

if ($self->famille!='') {
	$deps_self=$self->returnDepend();

	$Mod=new DBObj_org_lucterios_updates_ModulesToUpgrade;
	$Mod->famille=$self->famille;
	$Mod->find();
	while ($Mod->fetch())
		if ($Mod->module!=$self->module) {
			$ret=false;
			foreach($deps_self as $dep_self)
				if ($Mod->module==$dep_self)
					$ret=true;
			if (!$ret) {	
				$deps_mod=$Mod->returnDepend();
				foreach($deps_mod as $dep_mod)
					if ($self->module==$dep_mod)
						$ret=true;
			}
			if (!$ret)
				$list[]=$Mod->module;
		}
}
//echo "<!-- mod=".$self->module." - list=".print_r($list,true)." -->\n";
return $list;
//@CODE_ACTION@
}

?>
