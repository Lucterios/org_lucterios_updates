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
// --- Last modification: Date 03 March 2008 0:43:10 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@Voir un module à mettre à jours
//@PARAM@ posX
//@PARAM@ posY
//@PARAM@ xfer_result

function ModulesToUpgrade_APAS_show(&$self,$posX,$posY,$xfer_result)
{
//@CODE_ACTION@
$xfer_result->setDBObject($self,null,true,$posY,$posX);

$lbl=new Xfer_Comp_LabelForm('dependLbl');
$lbl->setValue("{[bold]}Dependant{[/bold]}");
$lbl->setLocation($posX,$posY+10);
$xfer_result->addComponent($lbl);
$edt=new Xfer_Comp_Label('depend');
$fin=implode(';',$self->returnDepend());
$edt->setValue($fin);
$edt->setLocation($posX+1,$posY+10);
$xfer_result->addComponent($edt);

$lbl=new Xfer_Comp_LabelForm('dependedLbl');
$lbl->setValue("{[bold]}Dependu{[/bold]}");
$lbl->setLocation($posX,$posY+11);
$xfer_result->addComponent($lbl);
$edt=new Xfer_Comp_Label('depended');
$fin=implode(';',$self->returnDepended());
$edt->setValue($fin);
$edt->setLocation($posX+1,$posY+11);
$xfer_result->addComponent($edt);

return $xfer_result;
//@CODE_ACTION@
}

?>
