<?php
// Method file write by SDK tool
// --- Last modification: Date 05 June 2008 23:16:12 By  ---

require_once('CORE/xfer_exception.inc.php');
require_once('CORE/rights.inc.php');

//@TABLES@
require_once('extensions/org_lucterios_updates/UpdateServers.tbl.php');
//@TABLES@

//@DESC@
//@PARAM@ 

function UpdateServers_APAS_accessible(&$self)
{
//@CODE_ACTION@
require_once("HTTP/Request.php");
$updateserver = 'http://'.$self->adresse."/actions/liste.php";
$Rep = file($updateserver);
return (($Rep !== false) && ( count($Rep)>0));
//@CODE_ACTION@
}

?>
