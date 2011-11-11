<?php
// 	This file is part of Diacamma, a software developped by "Le Sanglier du Libre" (http://www.sd-libre.fr)
// 	Thanks to have payed a retribution for using this module.
// 
// 	Diacamma is free software; you can redistribute it and/or modify
// 	it under the terms of the GNU General Public License as published by
// 	the Free Software Foundation; either version 2 of the License, or
// 	(at your option) any later version.
// 
// 	Diacamma is distributed in the hope that it will be useful,
// 	but WITHOUT ANY WARRANTY; without even the implied warranty of
// 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// 	GNU General Public License for more details.
// 
// 	You should have received a copy of the GNU General Public License
// 	along with Lucterios; if not, write to the Free Software
// 	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
// 
// 		Contributeurs: Fanny ALLEAUME, Pierre-Olivier VERSCHOORE, Laurent GAY
// table file write by SDK tool
// --- Last modification: Date 11 November 2011 10:54:54 By  ---

require_once('CORE/DBObject.inc.php');

class DBObj_org_lucterios_updates_ModulesToUpgrade extends DBObj_Basic
{
	public $Title="";
	public $tblname="ModulesToUpgrade";
	public $extname="org_lucterios_updates";
	public $__table="org_lucterios_updates_ModulesToUpgrade";

	public $DefaultFields=array();
	public $NbFieldsCheck=1;
	public $Heritage="";
	public $PosChild=-1;

	public $module;
	public $etat;
	public $titre;
	public $version;
	public $information;
	public $taille;
	public $famille;
	public $dependance;
	public $serveur;
	public $debit;
	public $nouveau;
	public $__DBMetaDataField=array('module'=>array('description'=>'Module', 'type'=>2, 'notnull'=>false, 'params'=>array('Size'=>50, 'Multi'=>false)), 'etat'=>array('description'=>'Etat', 'type'=>8, 'notnull'=>false, 'params'=>array('Enum'=>array('Proposé', 'A télécharger', 'En téléchargement', 'A installer', 'Erreur', 'Fin'))), 'titre'=>array('description'=>'Titre', 'type'=>2, 'notnull'=>false, 'params'=>array('Size'=>150, 'Multi'=>false)), 'version'=>array('description'=>'Version', 'type'=>2, 'notnull'=>false, 'params'=>array('Size'=>20, 'Multi'=>false)), 'information'=>array('description'=>'Information', 'type'=>7, 'notnull'=>false, 'params'=>array()), 'taille'=>array('description'=>'Taille', 'type'=>1, 'notnull'=>false, 'params'=>array('Min'=>0, 'Max'=>99999.9, 'Prec'=>3)), 'famille'=>array('description'=>'Famille', 'type'=>2, 'notnull'=>false, 'params'=>array('Size'=>50, 'Multi'=>false)), 'dependance'=>array('description'=>'Dépendances', 'type'=>7, 'notnull'=>false, 'params'=>array()), 'serveur'=>array('description'=>'Serveur', 'type'=>10, 'notnull'=>true, 'params'=>array('TableName'=>'org_lucterios_updates_UpdateServers')), 'debit'=>array('description'=>'Débit (sec)', 'type'=>1, 'notnull'=>false, 'params'=>array('Min'=>0, 'Max'=>60, 'Prec'=>6)), 'nouveau'=>array('description'=>'Nouveau', 'type'=>3, 'notnull'=>false, 'params'=>array()));

	public $__toText='$module [$version]';
}

?>
