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
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@
//@XFER:custom
require_once('CORE/xfer_custom.inc.php');
//@XFER:custom@


//@DESC@Lister des modules à mettre à jour
//@PARAM@ 


//@LOCK:0

function ModulesToUpgrade_APAS_List($Params)
{
$self=new DBObj_org_lucterios_updates_ModulesToUpgrade();
try {
$xfer_result=&new Xfer_Container_Custom("org_lucterios_updates","ModulesToUpgrade_APAS_List",$Params);
$xfer_result->Caption="Lister des modules à mettre à jour";
//@CODE_ACTION@
$img=new  Xfer_Comp_Image("img");
$img->setLocation(0,0);
$img->setValue("update.png");
$xfer_result->addComponent($img);
$lbl=new  Xfer_Comp_LabelForm("titre");
$lbl->setLocation(1,0);
$xfer_result->addComponent($lbl);
$lbl->setValue("{[center]}{[bold]}Lister des modules à mettre à jours{[/bold]}{[/center]}");
$self->find();
$grid = $self->getGrid("module");
$grid->setLocation(0,1,2);
$grid->addAction($self->NewAction('A Télécharger','','ATelecharger',FORMTYPE_MODAL,CLOSE_NO,SELECT_SINGLE));
$xfer_result->addComponent($grid);
$lbl=new Xfer_Comp_LabelForm("nb");
$lbl->setLocation(0, 2,2);
$lbl->setValue("Nombre affichés : ".count($grid->m_records));
$xfer_result->addComponent($lbl);
$xfer_result->addAction($self->NewAction('_Rafraichir','','Rafraichir',FORMTYPE_MODAL,CLOSE_NO));
$xfer_result->addAction($self->NewAction('_Télécharger','','Telechargement',FORMTYPE_MODAL,CLOSE_NO,SELECT_NONE));
$xfer_result->addAction($self->NewAction('_Installer','','Installer',FORMTYPE_MODAL,CLOSE_NO,SELECT_NONE));
$xfer_result->addAction(new Xfer_Action("_Fermer", "close.png"));
//@CODE_ACTION@
}catch(Exception $e) {
	throw $e;
}
return $xfer_result;
}

?>
