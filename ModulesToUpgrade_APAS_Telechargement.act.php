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
// --- Last modification: Date 02 March 2008 13:35:08 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@
//@XFER:acknowledge
require_once('CORE/xfer.inc.php');
//@XFER:acknowledge@


//@DESC@Téléchargement des modules
//@PARAM@ 


//@LOCK:0

function ModulesToUpgrade_APAS_Telechargement($Params)
{
$self=new DBObj_org_lucterios_updates_ModulesToUpgrade();
try {
$xfer_result=&new Xfer_Container_Acknowledge("org_lucterios_updates","ModulesToUpgrade_APAS_Telechargement",$Params);
$xfer_result->Caption="Téléchargement des modules";
//@CODE_ACTION@
$self->etat=1;
$self->find();
while ($self->fetch())
{
	$self->etat=2;
	$self->update();
}

$msg="";
$update=new DBObj_org_lucterios_updates_ModulesToUpgrade;
$update->etat=2;
$update->find();
while ($update->fetch()) {
	$ret=$update->downLoad();
	if (is_string($ret))
		$msg.=$ret."{[newline]}";
}
if ($msg!="")
	$xfer_result->message($msg);
//@CODE_ACTION@
}catch(Exception $e) {
	throw $e;
}
return $xfer_result;
}

?>
