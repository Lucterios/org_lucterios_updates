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
//  // table file write by SDK tool
// --- Last modification: Date 01 March 2008 16:33:55 By  ---

require_once('CORE/DBObject.inc.php');

class DBObj_org_lucterios_updates_UpdateServers extends DBObj_Basic
{
	var $tblname="UpdateServers";
	var $extname="org_lucterios_updates";
	var $__table="org_lucterios_updates_UpdateServers";

	var $DefaultFields=array(array('@refresh@'=>true, 'id'=>'1', 'adresse'=>'update.lucterios.org', 'actif'=>'o'));
	var $NbFieldsCheck=1;

	var $adresse;
	var $actif;
	var $__DBMetaDataField=array('adresse'=>array('description'=>'Adresse', 'type'=>2, 'notnull'=>true, 'params'=>array('Size'=>100, 'Multi'=>false)), 'actif'=>array('description'=>'Actif', 'type'=>3, 'notnull'=>false, 'params'=>array()));

	var $__toText='$adresse';
}

?>
