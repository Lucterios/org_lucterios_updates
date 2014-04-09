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
// --- Last modification: Date 02 March 2008 13:43:46 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/ModulesToUpgrade.tbl.php');
//@TABLES@
//@XFER:acknowledge
require_once('CORE/xfer.inc.php');
//@XFER:acknowledge@


//@DESC@Module à télécharger
//@PARAM@ 
//@INDEX:module

//@TRANSACTION:

//@LOCK:1

function ModulesToUpgrade_APAS_ATelecharger($Params)
{
$self=new DBObj_org_lucterios_updates_ModulesToUpgrade();
$module=getParams($Params,"module",-1);
if ($module>=0) $self->get($module);

$self->lockRecord("ModulesToUpgrade_APAS_ATelecharger");

global $connect;
$connect->begin();
try {
$xfer_result=new Xfer_Container_Acknowledge("org_lucterios_updates","ModulesToUpgrade_APAS_ATelecharger",$Params);
$xfer_result->Caption="Module à télécharger";
//@CODE_ACTION@
if (($self->etat==0) || ($self->etat==3))
{
	$self->etat=1;
	$self->update();
}
//@CODE_ACTION@
	$connect->commit();
	$self->unlockRecord("ModulesToUpgrade_APAS_ATelecharger");
}catch(Exception $e) {
	$connect->rollback();
	$self->unlockRecord("ModulesToUpgrade_APAS_ATelecharger");
	throw $e;
}
return $xfer_result;
}

?>
