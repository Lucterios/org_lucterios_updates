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
//@TABLES@
//@XFER:custom
require_once('CORE/xfer_custom.inc.php');
//@XFER:custom@


//@DESC@Serveurs de mise à jour
//@PARAM@ 


//@LOCK:0

function UpdateServers_APAS_List($Params)
{
$self=new DBObj_org_lucterios_updates_UpdateServers();
try {
$xfer_result=&new Xfer_Container_Custom("org_lucterios_updates","UpdateServers_APAS_List",$Params);
$xfer_result->Caption="Serveurs de mise à jour";
//@CODE_ACTION@
$img=new  Xfer_Comp_Image("img");
$img->setLocation(0,0);
$img->setValue("updateParam.png");
$xfer_result->addComponent($img);
$lbl=new  Xfer_Comp_LabelForm("titre");
$lbl->setLocation(1,0);
$xfer_result->addComponent($lbl);
$lbl->setValue("{[center]}{[bold]}Liste des serveurs de mise à jour{[/bold]}{[/center]}");
$self->find();
$grid = $self->getGrid("UpdateServers");
$grid->setLocation(0,1,2);
$xfer_result->addComponent($grid);
$lbl=new Xfer_Comp_LabelForm("nb");
$lbl->setLocation(0, 2,2);
$lbl->setValue("Nombre affichés : ".count($grid->m_records));
$xfer_result->addComponent($lbl);
$xfer_result->addAction(new Xfer_Action("_Fermer", "close.png"));
//@CODE_ACTION@
}catch(Exception $e) {
	throw $e;
}
return $xfer_result;
}

?>
