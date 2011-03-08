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
// setup file write by SDK tool
// --- Last modification: Date 08 March 2011 8:16:47 By  ---

$extention_name="org_lucterios_updates";
$extention_description="Modules gérant le téléchargement et l'installation d'autres extensions.{[newline]}Il utilise au mieux plusieurs serveurs de mise à jours et gére les dépendances entre les modules.";
$extention_appli="";
$extention_famille="update";
$extention_titre="Mise à jours & installation de modules";
$extension_libre=true;

$version_max=1;
$version_min=2;
$version_release=4;
$version_build=80;

$depencies=array();
$depencies[0] = new Param_Depencies("CORE", 1, 2, 1, 2, false);

$rights=array();
$rights[1] = new Param_Rigth("Paramètrage",85);
$rights[2] = new Param_Rigth("Mettre à jours/Installer",65);

$menus=array();
$menus[0] = new Param_Menu("Mise à jours", "_Extensions (conf.)", "", "", "", 10 , 0, "");
$menus[1] = new Param_Menu("Serveurs de mise à jours", "Mise à jours", "UpdateServers_APAS_List", "updateParam.png", "", 5 , 1, "Gestion des adresses de serveur de mise à jours.");
$menus[2] = new Param_Menu("_Mise à jours et Installation", "Ad_ministration", "ModulesToUpgrade_APAS_SelectionUpgrade", "update.png", "ctrl alt shift I", 10 , 1, "Téléchargement des dernières mises à jour de votre logiciel.");

$actions=array();
$actions[0] = new Param_Action("Module à télécharger", "ModulesToUpgrade_APAS_ATelecharger", 2);
$actions[1] = new Param_Action("Supprimer un module à mettre à jours", "ModulesToUpgrade_APAS_Del", 2);
$actions[2] = new Param_Action("Fiche d'un module à mettre à jours", "ModulesToUpgrade_APAS_Fiche", 2);
$actions[3] = new Param_Action("Cycle d installation des mise a jours", "ModulesToUpgrade_APAS_Installation", 2);
$actions[4] = new Param_Action("Installer les module télécharger", "ModulesToUpgrade_APAS_Installer", 2);
$actions[5] = new Param_Action("Lister des modules à mettre à jours", "ModulesToUpgrade_APAS_List", 2);
$actions[6] = new Param_Action("Rafraichir la liste des modules à mettre à jours", "ModulesToUpgrade_APAS_Rafraichir", 2);
$actions[7] = new Param_Action("Selectionner les mise à jours", "ModulesToUpgrade_APAS_SelectionUpgrade", 2);
$actions[8] = new Param_Action("Téléchargement des modules", "ModulesToUpgrade_APAS_Telechargement", 2);
$actions[9] = new Param_Action("Valider un serveur de mise à jours", "UpdateServers_APAS_AddModifyAct", 1);
$actions[10] = new Param_Action("Ajouter/Modifier un serveur de mise à jours", "UpdateServers_APAS_AddModify", 1);
$actions[11] = new Param_Action("Supprimer un serveur de mise à jours", "UpdateServers_APAS_Del", 1);
$actions[12] = new Param_Action("Serveurs de mise à jours", "UpdateServers_APAS_List", 1);

$params=array();
$params["GUID"] = new Param_Parameters("GUID", "", "GUID", 0, array('Multi'=>false));
$params["DateLastRefresh"] = new Param_Parameters("DateLastRefresh", "", "Date/Heure dernière lecture serveur", 0, array('Multi'=>false));

$extend_tables=array();
$extend_tables["ModulesToUpgrade"] = array("org_lucterios_updates.ModulesToUpgrade","",array("org_lucterios_updates_UpdateServers"=>"serveur",));
$extend_tables["UpdateServers"] = array("org_lucterios_updates.UpdateServers","",array());

?>