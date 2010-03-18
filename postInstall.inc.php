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
//  // library file write by SDK tool
// --- Last modification: Date 17 March 2010 23:42:27 By  ---

//@BEGIN@
function install_org_lucterios_updates($ExensionVersions) {
	$text = "";
	require_once"CORE/extension_params.tbl.php";
	$params = new DBObj_CORE_extension_params;
	$params->extensionId = 'org_lucterios_updates';
	$params->paramName = 'GUID';
	$params->find(false);
	if($params->fetch()) {
		require_once"extensions/org_lucterios_updates/GUIDGenerator.inc.php";
		$decode = uuidDecode($params->value);
		if(($params->value == '') || !$decode['check']) {
			$params->value = uuid();
			$params->update();
		}
	}
	return $text;
}
//@END@
?>
