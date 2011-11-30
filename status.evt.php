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
// --- Last modification: Date 03 February 2010 9:11:59 By  ---

function org_lucterios_updates_APAS_status(&$xfer_result)
{
//@CODE_ACTION@

	$lab=new Xfer_Comp_LabelForm('updatestitle');
	$lab->setValue('{[center]}{[bold]}{[underline]}Mises à jour{[/underline]}{[/bold]}{[/center]}');
	$lab->setLocation(0,100,4);
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
			$btn->setLocation(0,102,4);
			$btn->setAction($module->NewAction('Mise à jours','update.png','SelectionUpgrade',FORMTYPE_MODAL,CLOSE_NO));
			$xfer_result->addComponent($btn);
		}
		else
			$msg = "{[center]}Votre logiciel est à jour.{[/center]}";
	}
	$updatelbl=new Xfer_Comp_LabelForm('updatelbl');
	$updatelbl->setLocation(0,101,4);
	$updatelbl->setValue($msg);
	$xfer_result->addComponent($updatelbl);

	$lab=new Xfer_Comp_LabelForm('updatesend');
	$lab->setValue('{[center]}{[hr/]}{[/center]}');
	$lab->setLocation(0,103,4);
	$xfer_result->addComponent($lab);
//@CODE_ACTION@
}
?>
