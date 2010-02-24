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
// --- Last modification: Date 23 February 2010 12:12:34 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('CORE/extension_params.tbl.php');
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@telechargement d un module
//@PARAM@ 

function ModulesToUpgrade_APAS_downLoad(&$self)
{
//@CODE_ACTION@
if ($self->etat==2)
{
	global $rootPath;
	if(!isset($rootPath))
		$rootPath = "";
	require_once "CORE/extensionManager.inc.php";
	$is_client=($self->famille=='client');
	$dir_module=Extension::getFolder($self->module,$rootPath,$is_client);
	if (is_dir($dir_module))
		$canBeWrite=is_writable($dir_module);
	else {
		$path_parts = pathinfo($dir_module);
		$canBeWrite=is_writable($path_parts['dirname']);
	}
	if (!$canBeWrite) {
		$self->etat=4;
		$self->update();
		return "Erreur : manque droit d'écriture";
	}

	$params=new DBObj_CORE_extension_params;
	$param_val=$params->getParameters('org_lucterios_updates');
	$guid=$param_val['GUID'];
	$newModule=is_dir($dir_module)?'n':'o';
	$UpdateBaseUrl="http://".$self->getField('serveur')->adresse."/actions/down.php?GUID=$guid&NEW=$newModule&module=".$self->module;
	$dir=$rootPath."usr/org_lucterios_updates/";
	if (!is_dir($dir))
		mkdir($dir, 0777);
	if (!is_dir($dir))
		return "Erreur de répertoire de destination ($dir)!";
     	$PackageFileName = $dir.$self->module.".lpk";
     	if (file_exists($PackageFileName) && !unlink($PackageFileName))
		return "Problème de droit d'écriture ($PackageFileName)!";
     else {
		if (($in = fopen($UpdateBaseUrl, "rb")) === false)
			return "Problème de téléchargement de fichier ($UpdateBaseUrl)!";
		if (($out = fopen($PackageFileName, "wb")) === false)
		{
			fclose($in);
			return "Problème de sauvegarde de fichier ($PackageFileName)!";
		}
		$first_line="";
		while($buf = fread($in, "2048"))
		{
			if ($first_line=="")	$first_line=$buf;
			fwrite($out, $buf);
		}
		fclose($in);
		fclose($out);
		if (substr($first_line,0,7)=="<ERROR>")
		{
			$self->etat=4;
			$self->update();
			unlink($PackageFileName);
			require_once("CORE/XMLparse.inc.php");
			$p = new COREParser();
			$p->setInputString($first_line);
			$p->parse();
			$LIST = $p->getResult();
			return "Erreur : ".$LIST->getCData();
		}

		require_once("Archive/Tar.php");
		$tar_object = new Archive_Tar($PackageFileName,'gz');
		if ($self->module=='CORE')
			$tar_object->extractList("CORE/setup.inc.php",$dir,"CORE");
		else	if ($self->famille=='client')
			$tar_object->extractList("setup.inc.php",$dir);
		else
			$tar_object->extractList("/setup.inc.php",$dir);
		if (!is_file($dir."setup.inc.php"))
		{
			$self->etat=4;
			$self->update();
			unlink($PackageFileName);
			return "Erreur : extract setup";
		}
		else
			rename($dir."setup.inc.php",$dir.$self->module."_setup.inc.php");
		require_once "CORE/setup_param.inc.php";
		require_once $dir.$self->module."_setup.inc.php";
		$vers="$version_max.$version_min.$version_release.$version_build";
		if (version_compare($self->version,$vers)!=0)
		{
			$self->etat=4;
			$self->update();

			unlink($PackageFileName);
			unlink($dir.$self->module."_setup.inc.php");
			return "Erreur : mauvaise version";
		}


	}
	$self->etat=3;
	$self->update();
	return true;
}
//@CODE_ACTION@
}

?>
