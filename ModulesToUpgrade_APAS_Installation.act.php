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
// Action file write by Lucterios SDK tool

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/UpdateServers.tbl.php');
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@
//@XFER:custom
require_once('CORE/xfer_custom.inc.php');
//@XFER:custom@


//@DESC@Cycle d`installation des mises à jour
//@PARAM@ erreur
//@PARAM@ status=0


//@LOCK:0

function ModulesToUpgrade_APAS_Installation($Params)
{
if (($ret=checkParams("org_lucterios_updates", "ModulesToUpgrade_APAS_Installation",$Params ,"erreur"))!=null)
	return $ret;
$erreur=getParams($Params,"erreur",0);
$status=getParams($Params,"status",0);
$self=new DBObj_org_lucterios_updates_ModulesToUpgrade();
try {
$xfer_result=new Xfer_Container_Custom("org_lucterios_updates","ModulesToUpgrade_APAS_Installation",$Params);
$xfer_result->Caption="Cycle d`installation des mises à jour";
//@CODE_ACTION@
global $SECURITY_LOCK;
$SECURITY_LOCK->open(true);

$img = new Xfer_Comp_Image("img");
$img->setLocation(0,0);
$img->setValue("update.png");
$xfer_result->addComponent($img);
$lbl = new Xfer_Comp_LabelForm("titre2");
$lbl->setLocation(1,0,4);
$lbl->setValue("{[center]}{[bold]}Téléchargement et installation des modules{[/bold]}{[/center]}");
$xfer_result->addComponent($lbl);
$lbl = new Xfer_Comp_LabelForm("warning");
$lbl->setLocation(0,1,5);
$lbl->setValue("{[center]}{[italic]}Merci de bien vouloir patienter...{[/italic]}{[/center]}");
$xfer_result->addComponent($lbl);
$PosY = 3;
$change_status = false;
switch($status) {
case 0:
	$UpdateServer=new DBObj_org_lucterios_updates_UpdateServers;
	$UpdateServer->clearDateUpdate();
	$module = new DBObj_org_lucterios_updates_ModulesToUpgrade;
	$module->etat = 0;
	$module->find();
	while($module->fetch())if($Params[$module->module] == 'o') {
		$module_upgrades = new DBObj_org_lucterios_updates_ModulesToUpgrade;
		$module_upgrades->get($module->id);
		$module_upgrades->etat = 1;
		$module_upgrades->update();
	}
	$xfer_result->m_context = array('status' => 1);
	break;
case 1:
	$change_status = true;
	$module = new DBObj_org_lucterios_updates_ModulesToUpgrade;
	$module->etat = 2;
	$module->find();
	if($module->fetch()) {
		$ret = $module->downLoad();
		if( is_string($ret))$erreur .= $ret."{[newline]}";
		$change_status = false;
	}
	$module = new DBObj_org_lucterios_updates_ModulesToUpgrade;
	$module->etat = 1;
	$module->find();
	if($module->fetch()) {
		$module_upgrades = new DBObj_org_lucterios_updates_ModulesToUpgrade;
		$module_upgrades->get($module->id);
		$module_upgrades->etat = 2;
		$module_upgrades->update();
		$change_status = false;
	}
	if($change_status)$xfer_result->m_context['status'] = 2;
	break;
case 2:
	List($list,$ret) = $self->installModule();
	if(! is_array($list))$erreur .= $ret."{[newline]}";
	$xfer_result->m_context['status'] = 3;
	$status = 3;
	break;
case 3:
	$ret = $self->checkModules();
	if($ret!=null)
		$erreur .= $ret."{[newline]}";
	$xfer_result->m_context['status'] = 4;
	$status = 4;
	break;
}
$module = new DBObj_org_lucterios_updates_ModulesToUpgrade;
$module->find();
while($module->fetch())if($module->etat != 0) {
	$lbl = new Xfer_Comp_LabelForm("Space2_".$module->module);
	$lbl->setLocation(0,$PosY,2);
	$lbl->setValue("");
	$lbl->setSize(10,10);
	$xfer_result->addComponent($lbl);
	$lbl = new Xfer_Comp_LabelForm("Title2_".$module->module);
	$lbl->setLocation(1,$PosY+1);
	$lbl->setValue("{[bold]}".$module->titre."{[/bold]}");
	$xfer_result->addComponent($lbl);
	$lbl = new Xfer_Comp_LabelForm("Version2_".$module->module);
	$lbl->setLocation(2,$PosY+1);
	$lbl->setValue($module->getVersionToCompare());
	$xfer_result->addComponent($lbl);
	$lbl = new Xfer_Comp_LabelForm("Etat2_".$module->module);
	$lbl->setLocation(3,$PosY+1);
	$lbl->setValue("{[center]}{[italic]}".$module->getField('etat')."{[/italic]}{[/center]}");
	$xfer_result->addComponent($lbl);
	$lbl = new Xfer_Comp_LabelForm("Taille2_".$module->module);
	$lbl->setLocation(4,$PosY+1);
	$lbl->setValue($module->getTaille());
	$xfer_result->addComponent($lbl);
	$PosY = $PosY+2;
}
$lbl_err = new Xfer_Comp_LabelForm("ErrorTxt");
$lbl_err->setLocation(0,$PosY+5,5);
$lbl_err->setValue("{[center]}{[font color='red']}$erreur{[/font]}{[/center]}");
$lbl_err->setSize(50,750);
$xfer_result->addComponent($lbl_err);
$text = 'Téléchargement des modules';
if($change_status)
	$text = 'Installation des modules';
if($status != 4) {
	if($status == 3)
		$text = 'Controles des modules';
	$btn = new Xfer_Comp_Button("Next");
	$btn->setLocation(0,$PosY+10,5);
	$btn->setAction($self->NewAction($text,'','Installation', FORMTYPE_REFRESH, CLOSE_NO));
	$btn->JavaScript = "
	parent.refresh();
	";
	$xfer_result->addComponent($btn);
}
else {
	$text = 'Téléchargement et installation terminés';
	$lbl = new Xfer_Comp_LabelForm("info");
	$lbl->setLocation(0,$PosY+10,5);
	$lbl->setValue("{[center]}$text{[/center]}");
	$xfer_result->addComponent($lbl);
}
$xfer_result->m_context['erreur'] = $erreur;
if($status == 4)
	$xfer_result->addAction( new Xfer_Action('_Fermer','ok.png','CORE','menu', FORMTYPE_MODAL, CLOSE_YES));
else
	$xfer_result->addAction( new Xfer_Action('_Annuler','cancel.png','','', FORMTYPE_MODAL, CLOSE_YES));
$SECURITY_LOCK->close();
//@CODE_ACTION@
}catch(Exception $e) {
	throw $e;
}
return $xfer_result;
}

?>
