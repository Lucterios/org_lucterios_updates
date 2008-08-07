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
// --- Last modification: Date 05 August 2008 21:36:05 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('CORE/extension_params.tbl.php');
require_once('extensions/org_lucterios_updates/UpdateServers.tbl.php');
//@TABLES@

//@DESC@Vide la date
//@PARAM@ 

function UpdateServers_APAS_clearDateUpdate(&$self)
{
//@CODE_ACTION@
$params = new DBObj_CORE_extension_params;
$params->extensionId = 'org_lucterios_updates';
$params->paramName = 'DateLastRefresh';
$params->find();
$params->fetch();
$params->value = '';
$params->update();
//@CODE_ACTION@
}

?>
