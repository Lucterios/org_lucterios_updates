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
// --- Last modification: Date 04 March 2009 19:46:25 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@

//@DESC@Retourne la version à installer et celle déja présente
//@PARAM@ 

function ModulesToUpgrade_APAS_getVersionToCompare(&$self)
{
//@CODE_ACTION@
$text=$self->version;
if ($self->nouveau=='n')
{
	global $rootPath;
	if(!isset($rootPath))
		$rootPath = "";
	require_once "CORE/extensionManager.inc.php";
	$is_client=($self->famille=='client');
	$module=$self->module;
	if ($self->famille=='applis') $module='applis';
	$ext=new Extension($module,Extension::getFolder($module,$rootPath,$is_client));
	if ($is_client)
		$vers=$ext->getPHPVersion();
	else
		$vers=$ext->getDBVersion();
	echo "<!-- mod=$self->module - client=$is_client - vers=$vers -->\n";
	$text.=" (actuellement $vers)";
}
else
	$text.=" (non installé)";
return $text;
//@CODE_ACTION@
}

?>
