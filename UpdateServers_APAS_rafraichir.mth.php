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
// --- Last modification: Date 28 February 2011 20:24:14 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
require_once('extensions/org_lucterios_updates/UpdateServers.tbl.php');
//@TABLES@

//@DESC@Télécharger la liste des modules d'un serveur
//@PARAM@ 

function UpdateServers_APAS_rafraichir(&$self)
{
//@CODE_ACTION@
$updateserver='http://'.$self->adresse."/actions/liste.php";
$sec = microtime(true);
$Rep = file($updateserver);
$duration = microtime(true)-$sec;
if(($Rep!==false) && (count($Rep)>0))
{
	$Response = implode("", $Rep);
	$Response = str_replace(array("\t","\n"),'',$Response);

	require_once("CORE/XMLparse.inc.php");
	$p = new COREParser();
	$p->setInputString($Response);
	$p->parse();
	$LIST = $p->getResult();
	if (get_class($LIST)!='XmlElement')
		return false;
	$Extension_childs = $LIST->getChildsByTagName("MODULE");
	foreach($Extension_childs as $Extension_child)
	{
		$module=$Extension_child->getAttributeValue("module");
		$famille=$Extension_child->getAttributeValue("famille");
		if ($famille=="applis")
			$extname="applis";
		else
			$extname=$module;
		require_once "CORE/extensionManager.inc.php";
		$dir=Extension::getFolder($extname,"",$famille=="client");
		$ext=new Extension($extname,$dir);
		if ((($famille!="applis") || ($ext->Appli==$module)) && (($module!='SDK') || is_dir('SDK')))
		{
			$vmax=$Extension_child->getAttributeValue("vmax");
			$vmin=$Extension_child->getAttributeValue("vmin");
			$vrel=$Extension_child->getAttributeValue("vrel");
			$vbuild=$Extension_child->getAttributeValue("vbuild");
			$versDist="$vmax.$vmin.$vrel.$vbuild";
			$Module_to_update=new DBObj_org_lucterios_updates_ModulesToUpgrade;
			$Module_to_update->module=$module;
			if ($Module_to_update->find()) {
				$Module_to_update->fetch();
				$diff=version_compare($versDist,$Module_to_update->version);
				$valid=(($diff>0) || (($diff==0) && ($Module_to_update->debit > $duration)));
			}
			else
			{
				if ($famille=='client')
					$valid=(version_compare($versDist,$ext->getPHPVersion())>0);
				else
					$valid=(version_compare($versDist,$ext->getDBVersion())>0);
			}
			$is_new=($ext->getPHPVersion()=='0.0.0.0');
			if ($valid) {
				$dir="usr/org_lucterios_updates/";
				$lpk_file=$dir.$module.".lpk";
				$setup_file=$dir.$module."_setup.inc.php";
				if (is_file($lpk_file)) unlink($lpk_file);
				if (is_file($setup_file)) unlink($setup_file);
				$Module_to_update->module=$module;
				$Module_to_update->titre=utf8_decode($Extension_child->getCDataOfChild('TITRE'));
				$Module_to_update->version=$versDist;
				$info=utf8_decode($Extension_child->getCDataOfChild('DESCRIPTION'))."{[newline]}";
				$info.=utf8_decode($Extension_child->getCDataOfChild('MAIL'))." - ";
				$info.=utf8_decode($Extension_child->getCDataOfChild('WEB'))."{[newline]}";
				$info.=utf8_decode($Extension_child->getCDataOfChild('COPYRIGHT'));
				if ($Extension_child->getAttributeValue("libre")=='o')
					$info.=" - Libre";
				else
					$info.=" - Commercial";
				$Module_to_update->information=$info;
				$Module_to_update->famille=$famille;
				$Module_to_update->serveur=$self->id;
				$Module_to_update->debit=$duration;
				$Module_to_update->nouveau=$is_new?'o':'n';
				$Module_to_update->taille=((float)$Extension_child->getAttributeValue("size"))/1024;
				$deps = $Extension_child->getChildsByTagName("DEP");
				$dep_text="";
				foreach($deps as $dep)
				{
					$dep_text.=$dep->getAttributeValue("dep").';';
					$dep_text.=$dep->getAttributeValue("vmax").'.'.$dep->getAttributeValue("ssvmax").';';
					$dep_text.=$dep->getAttributeValue("vmin").'.'.$dep->getAttributeValue("ssvmin").';';
					$dep_text.=$dep->getAttributeValue("optionnel").' ';
				}
				$Module_to_update->etat=0;
				$Module_to_update->dependance=$dep_text;
				if ($Module_to_update->id>0)
					$Module_to_update->update();
				else
					$Module_to_update->insert();
			}
		}
	}
	return true;
} else
	return false;
//@CODE_ACTION@
}

?>
