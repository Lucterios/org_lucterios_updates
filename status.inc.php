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
//  // library file write by SDK tool
// --- Last modification: Date 13 January 2009 22:57:13 By  ---

//@BEGIN@
function org_lucterios_updates_status(&$xfer_result)
{
	$lab=new Xfer_Comp_LabelForm('updatestitle');
	$lab->setValue("{[center]}{[italc]}Mises à jour{[/italc]}{[/center]}");
	$lab->setLocation(0,100,2);
	$xfer_result->addComponent($lab);

	require_once "extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php";
	$module=new DBObj_org_lucterios_updates_ModulesToUpgrade;
	$res = $module->refresh();
	if($res==false) $msg = "Aucun serveurs de mises à jour ne réponds.";
	else {
		$res = false;
		$module=new DBObj_org_lucterios_updates_ModulesToUpgrade;
		$module->nouveau='n';
		if ($module->find()>0) {
			$msg = "{[center]}{[font color='red']}Des modules peuvent être mis à jour.{[/font]}{[/center]}";
			$btn=new Xfer_Comp_Button('updatebtn');
			$btn->setLocation(0,102,2);
			$btn->setAction($module->NewAction('Mise à jours','update.png','SelectionUpgrade',FORMTYPE_MODAL,CLOSE_YES));
			$xfer_result->addComponent($btn);
		}
		else
			$msg = "{[center]}Votre logiciel est à jour.{[/center]}";
	}
	$updatelbl=new Xfer_Comp_LabelForm('updatelbl');
	$updatelbl->setLocation(0,101,2);
	$updatelbl->setValue($msg);
	$xfer_result->addComponent($updatelbl);
}
//@END@
?>
