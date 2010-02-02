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
// --- Last modification: Date 02 February 2010 2:02:58 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@Controle finales des modules
//@PARAM@ 

function ModulesToUpgrade_APAS_checkModules(&$self)
{
//@CODE_ACTION@
global $rootPath;
if(!isset($rootPath))
	$rootPath = "";
require_once "CORE/extensionManager.inc.php";
require_once "CORE/dbcnx.inc.php";
global $connect;

$ret="";

$ext_list = getExtensions($rootPath);
foreach($ext_list as $name => $dir)
	$set_of_ext[] = new Extension($name,$dir);
$set_of_ext = sortExtension($set_of_ext);
$ExtensionDescription = array();
foreach($set_of_ext as $ext) {
	$ret.=$ext->Dir."|";
	$ext->upgradeContraintsTable();
	$ext->checkStorageFunctions();
	$ext->postInstall();
}

return null;
//@CODE_ACTION@
}

?>
