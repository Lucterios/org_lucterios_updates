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
//  // Action file write by SDK tool
// --- Last modification: Date 29 November 2008 0:11:31 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@
//@XFER:custom
require_once('CORE/xfer_custom.inc.php');
//@XFER:custom@


//@DESC@Selectionner les mise à jours
//@PARAM@ 


//@LOCK:0

function ModulesToUpgrade_APAS_SelectionUpgrade($Params)
{
$self=new DBObj_org_lucterios_updates_ModulesToUpgrade();
try {
$xfer_result=&new Xfer_Container_Custom("org_lucterios_updates","ModulesToUpgrade_APAS_SelectionUpgrade",$Params);
$xfer_result->Caption="Selectionner les mise à jours";
//@CODE_ACTION@
$img=new  Xfer_Comp_Image("img");
$img->setLocation(0,0);
$img->setValue("update.png");
$xfer_result->addComponent($img);
$lbl=new  Xfer_Comp_LabelForm("titre");
$lbl->setLocation(1,0,3);
$lbl->setValue("{[newline]}{[center]}{[bold]}Lister des modules à télécharger{[/bold]}{[/center]}");
$xfer_result->addComponent($lbl);

$res=$self->refresh();
$PosY=2;

if ($res) {
	$Current_Nouveau='';
	$module=new DBObj_org_lucterios_updates_ModulesToUpgrade;
	$module->etat=0;
	$module->orderBy('nouveau');
	if ($module->find()>0)
	{
		while($module->fetch())
		{
			$lbl=new Xfer_Comp_LabelForm("Space_".$module->module);
			$lbl->setLocation(1,$PosY++,3);
			$lbl->setValue('');
			$lbl->setSize(10,10);
			$xfer_result->addComponent($lbl);

			if ($module->nouveau!=$Current_Nouveau)
			{
				$Current_Nouveau=$module->nouveau;
				if ($Current_Nouveau=='n')
					$xfer_result->newTab("Modules à mettre à jours");
				else
					$xfer_result->newTab("Nouveaux modules");
				/*$lbl=new Xfer_Comp_LabelForm("Nouveau_".$Current_Nouveau);
				$lbl->setLocation(0,$PosY++,4);
				if ($Current_Nouveau=='n')
					$lbl->setValue('{[center]}{[underline]}Modules à mettre à jours{[/underline]}{[/center]}');
				else
					$lbl->setValue('{[center]}{[underline]}Nouveaux modules{[/underline]}{[/center]}');
				$xfer_result->addComponent($lbl);*/
			}

			$check=new Xfer_Comp_Check($module->module);
			$check->setLocation(0,$PosY);
			$check->setValue($module->nouveau=='n');
			$depO=$module->returnDepend();
			$depN=$module->returnDepended();
			$depI=$module->returnNoCompatible();

			$script="var type=current.getValue();
var new_text1='<CHECK><![CDATA[1]]></CHECK>';
var new_text0='<CHECK><![CDATA[]]></CHECK>';
if (type=='o')
{";

			foreach($depO as $dep)
				$script.="	parent.get('$dep').setValue(new_text1);\n";
			foreach($depI as $dep)
				$script.="	parent.get('$dep').setValue(new_text0);\n";

			$script.="}
else
{";

			foreach($depN as $dep)
				$script.="	parent.get('$dep').setValue(new_text0);\n";

			$script.="}";
			$check->JavaScript = $script;
			$xfer_result->addComponent($check);

			$lbl=new Xfer_Comp_LabelForm("Title_".$module->module);
			$lbl->setLocation(1,$PosY);
			$lbl->setValue("{[bold]}".$module->titre."{[/bold]}");
			$xfer_result->addComponent($lbl);

			$lbl=new Xfer_Comp_LabelForm("Version_".$module->module);
			$lbl->setLocation(2,$PosY);
			$lbl->setValue($module->getVersionToCompare());
			$xfer_result->addComponent($lbl);

			$lbl=new Xfer_Comp_LabelForm("Taille_".$module->module);
			$lbl->setLocation(3,$PosY);
			$lbl->setValue($module->getTaille());
			$xfer_result->addComponent($lbl);

			$lbl=new Xfer_Comp_LabelForm("Info_".$module->module);
			$lbl->setLocation(1,$PosY+1,3);
			$lbl->setValue("{[italic]}".$module->information."{[/italic]}");
			$xfer_result->addComponent($lbl);

			$PosY=$PosY+2;
		}
		$xfer_result->m_context['erreur']='';
		$xfer_result->addAction($self->NewAction('_Installer','ok.png','Installation',FORMTYPE_MODAL,CLOSE_YES));
	}
	else
	{
		$lbl=new Xfer_Comp_LabelForm("OK");
		$lbl->setLocation(0,$PosY+1,4);
		$lbl->setValue("{[center]}Aucun mise à jours disponible.{[newline]}Votre logiciel est à jours.{[/center]}");
		$xfer_result->addComponent($lbl);
	}
}
else
{
	$lbl=new Xfer_Comp_LabelForm("Erreur");
	$lbl->setLocation(0,$PosY+1,4);
	$lbl->setValue("{[center]}Aucun serveur de mise à jours de réponds.{[newline]}Vérifiez votre connexion réseau et vos paramétrages.{[/center]}");
	$xfer_result->addComponent($lbl);
}
$xfer_result->addAction(new Xfer_Action("_Annuler", "close.png"));
//@CODE_ACTION@
}catch(Exception $e) {
	throw $e;
}
return $xfer_result;
}

?>
