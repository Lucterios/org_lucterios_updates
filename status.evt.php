<?php
// This file is part of Lucterios/Diacamma, a software developped by 'Le Sanglier du Libre' (http://www.sd-libre.fr)
// thanks to have payed a retribution for using this module.
// 
// Lucterios/Diacamma is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
// 
// Lucterios/Diacamma is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with Lucterios; if not, write to the Free Software
// Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
// Event file write by Lucterios SDK tool

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
//@TABLES@

//@DESC@
//@PARAM@ xfer_result

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
	if($res==false) $msg = "Aucun serveur de mise à jour ne répond.";
	else {
		$res = false;
		$module=new DBObj_org_lucterios_updates_ModulesToUpgrade;
		$module->nouveau='n';
		if ($module->find()>0) {
			$msg = "{[center]}{[font color='red']}Des modules peuvent être mis à jour.{[/font]}{[/center]}";
			$btn=new Xfer_Comp_Button('updatebtn');
			$btn->setLocation(0,102,4);
			$btn->setAction($module->NewAction('Mises à jour','update.png','SelectionUpgrade',FORMTYPE_MODAL,CLOSE_NO));
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
