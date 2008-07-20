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
// --- Last modification: Date 04 March 2008 22:25:13 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@
//@PARAM@ 

function ModulesToUpgrade_APAS_getDependDesc(&$self)
{
//@CODE_ACTION@
$dep=$self->dependance;
$depents=array();
while (($pos_space=strpos($dep," "))!==false)
{
	$depents[]=split(';',substr($dep,0,$pos_space));
	$dep=substr($dep,$pos_space+1);
}
//echo "<!-- B module=".$self->module." - dep=".$self->dependance." - depends=".print_r($depents,true)." -->\n";
return $depents;
//@CODE_ACTION@
}

?>
