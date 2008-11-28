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
// --- Last modification: Date 28 November 2008 12:20:57 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@
//@PARAM@ Command
//@PARAM@ exclude=''

function ModulesToUpgrade_APAS_returnRecursif(&$self,$Command,$exclude='')
{
//@CODE_ACTION@
$exclude.=" ".$self->module;
$deps=$self->$Command($exclude);
$new_deps=$deps;
foreach($deps as $dep)
	if (strpos($exclude,$dep)===false) {
		$other_mod=new DBObj_org_lucterios_updates_ModulesToUpgrade;
		$other_mod->module=$dep;
		$other_mod->find();
		if ($other_mod->fetch()) {
			$other_deps=$other_mod->returnRecursif($Command, $exclude);
			foreach($other_deps as $other_dep)
				if ((strpos($exclude,$other_dep)===false) && !in_array($other_dep,$new_deps))
					$new_deps[]=$other_dep;
		}
	}
return $new_deps;
//@CODE_ACTION@
}

?>
