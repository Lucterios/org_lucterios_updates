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
// --- Last modification: Date 01 March 2008 15:51:11 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/UpdateServers.tbl.php');
//@TABLES@
//@XFER:acknowledge
require_once('CORE/xfer.inc.php');
//@XFER:acknowledge@


//@DESC@Valider un serveur de mise à jours
//@PARAM@ UpdateServers

//@TRANSACTION:

//@LOCK:0

function UpdateServers_APAS_AddModifyAct($Params)
{
if (($ret=checkParams("org_lucterios_updates", "UpdateServers_APAS_AddModifyAct",$Params ,"UpdateServers"))!=null)
	return $ret;
$UpdateServers=getParams($Params,"UpdateServers",0);
$self=new DBObj_org_lucterios_updates_UpdateServers();

global $connect;
$connect->begin();
try {
$xfer_result=&new Xfer_Container_Acknowledge("org_lucterios_updates","UpdateServers_APAS_AddModifyAct",$Params);
$xfer_result->Caption='Valider un serveur de mise à jours';
//@CODE_ACTION@
if($UpdateServers>0)
	$find=$self->get($UpdateServers);
$self->setFrom($Params);
if ($find)
	$self->update();
else
	$self->insert();
$UpdateServer=new DBObj_org_lucterios_updates_UpdateServers;
$UpdateServer->clearDateUpdate();
//@CODE_ACTION@
	$connect->commit();
}catch(Exception $e) {
	$connect->rollback();
	throw $e;
}
return $xfer_result;
}

?>
