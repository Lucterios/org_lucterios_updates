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
// Test file write by SDK tool
// --- Last modification: Date 11 November 2011 19:15:16 By  ---


//@TABLES@
//@TABLES@

//@DESC@
//@PARAM@ 

function org_lucterios_updates_ImportExtension(&$test)
{
//@CODE_ACTION@
$rep=$test->CallAction("org_lucterios_updates","UpdateServers_APAS_List",array(),"Xfer_Container_Custom");
$test->assertEquals(1,count($rep->m_actions),"nb actions 1");
$test->assertEquals(4,$rep->getComponentCount());
$comp1=$rep->getComponents("UpdateServers");
$test->assertClass("Xfer_Comp_Grid",$comp1,"UpdateServers");
$test->assertEquals(2,count($comp1->m_headers),"headers");
$test->assertEquals(3,count($comp1->m_actions),"actions");
$test->assertEquals(1,count($comp1->m_records),"records");
$headers=array_keys($comp1->m_headers);
$test->assertEquals("adresse",$headers[0],print_r($comp1->m_headers,true));
$test->assertEquals("actif",$headers[1]);
$test->assertEquals("update.lucterios.org",$comp1->m_records["1"]["adresse"]);
$test->assertEquals("Oui",$comp1->m_records["1"]["actif"]);
unset($rep);unset($comp1);

$rep=$test->CallAction("org_lucterios_updates","UpdateServers_APAS_AddModify",array(),"Xfer_Container_Custom");
$test->assertEquals(5,$rep->getComponentCount());
$test->assertEquals(2,count($rep->m_actions),"nb actions 2");
$comp1=$rep->getComponents("adresse");
$test->assertClass("xfer_comp_edit",$comp1,"adresse");
$comp2=$rep->getComponents("actif");
$test->assertClass("xfer_comp_check",$comp2,"actif");
unset($rep);unset($comp1);unset($comp2);

$rep=$test->CallAction("org_lucterios_updates","UpdateServers_APAS_AddModifyAct",array('adresse'=>'projets.lucterios.org/lucteriosUpdates','actif'=>'o'),"Xfer_Container_Acknowledge");
unset($rep);

$rep=$test->CallAction("org_lucterios_updates","UpdateServers_APAS_List",array(),"Xfer_Container_Custom");
$comp1=$rep->getComponents("UpdateServers");
$test->assertEquals(2,count($comp1->m_records),"records");
$test->assertEquals("projets.lucterios.org/lucteriosUpdates",$comp1->m_records["100"]["adresse"]);
$test->assertEquals("Oui",$comp1->m_records["100"]["actif"]);
unset($rep);unset($comp1);

$rep=$test->CallAction("org_lucterios_updates","ModulesToUpgrade_APAS_SelectionUpgrade",array(),"Xfer_Container_Custom");
$comp1=$rep->getComponents("PourTest");
$test->assertClass("xfer_comp_check",$comp1,"PourTest");
unset($rep);unset($comp1);

$rep=$test->CallAction("org_lucterios_updates","ModulesToUpgrade_APAS_Installation",array("PourTest"=>'o','erreur'=>''),"Xfer_Container_Custom");
$test->assertEquals('',$rep->m_context['erreur'],'erreur A '.print_r($rep->m_context,true));
$test->assertEquals(1,$rep->m_context['status'],'status A');
$comp1=$rep->getComponents("Next");
$test->assertClass("xfer_comp_button",$comp1,"Next A");
unset($rep);unset($comp1);

$rep=$test->CallAction("org_lucterios_updates","ModulesToUpgrade_APAS_Installation",array("PourTest"=>'o','erreur'=>'','status'=>1),"Xfer_Container_Custom");
$test->assertEquals('',$rep->m_context['erreur'],'erreur B');
$test->assertEquals(1,$rep->m_context['status'],'status B');
$comp1=$rep->getComponents("Next");
$test->assertClass("xfer_comp_button",$comp1,"Next B");
unset($rep);unset($comp1);

$rep=$test->CallAction("org_lucterios_updates","ModulesToUpgrade_APAS_Installation",array("PourTest"=>'o','erreur'=>'','status'=>1),"Xfer_Container_Custom");
$test->assertEquals('',$rep->m_context['erreur'],'erreur C');
$test->assertEquals(1,$rep->m_context['status'],'status C');
$comp1=$rep->getComponents("Next");
$test->assertClass("xfer_comp_button",$comp1,"Next C");
unset($rep);unset($comp1);

$rep=$test->CallAction("org_lucterios_updates","ModulesToUpgrade_APAS_Installation",array("PourTest"=>'o','erreur'=>'','status'=>1),"Xfer_Container_Custom");
$test->assertEquals('',$rep->m_context['erreur'],'erreur D');
$test->assertEquals(2,$rep->m_context['status'],'status D');
$comp1=$rep->getComponents("Next");
$test->assertClass("xfer_comp_button",$comp1,"Next D");
unset($rep);unset($comp1);

$rep=$test->CallAction("org_lucterios_updates","ModulesToUpgrade_APAS_Installation",array("PourTest"=>'o','erreur'=>'','status'=>2),"Xfer_Container_Custom");
$test->assertEquals('',$rep->m_context['erreur'],'erreur E');
$test->assertEquals(3,$rep->m_context['status'],'status E');
$comp1=$rep->getComponents("Next");
$test->assertClass("xfer_comp_button",$comp1,"Next E");
unset($rep);unset($comp1);

$rep=$test->CallAction("org_lucterios_updates","ModulesToUpgrade_APAS_Installation",array("PourTest"=>'o','erreur'=>'','status'=>4),"Xfer_Container_Custom");
$test->assertEquals('',$rep->m_context['erreur'],'erreur F');
$test->assertEquals(4,$rep->m_context['status'],'status F');
$comp1=$rep->getComponents("Next");
$test->assertEquals(null,$comp1,"Next F");
unset($rep);unset($comp1);

$test->assertEquals(true,is_dir("extensions/PourTest"),"extensions/PourTest");
$test->assertEquals(true,is_file("extensions/PourTest/config.evt.php"),"config.evt.php");
$test->assertEquals(true,is_file("extensions/PourTest/postInstall.inc.php"),"postInstall.inc.php");
$test->assertEquals(true,is_file("extensions/PourTest/setup.inc.php"),"setup.inc.php");
$test->assertEquals(true,is_file("extensions/PourTest/status.evt.php"),"status.evt.php");
$test->assertEquals(true,is_dir("extensions/PourTest/images"),"extensions/PourTest/images");
$test->assertEquals(true,is_file("extensions/PourTest/images/gimp.png"),"gimp.png");
$test->assertEquals(true,is_file("extensions/PourTest/images/blender.png"),"blender.png");
$test->assertEquals(true,is_file("extensions/PourTest/images/adobe.pdf.png"),"adobe.pdf.png");
$test->assertEquals(true,is_dir("extensions/PourTest/help"),"extensions/PourTest/help");
$test->assertEquals(true,is_file("extensions/PourTest/help/help.xhlp"),"help.xhlp");
$test->assertEquals(true,is_file("extensions/PourTest/help/menu.hlp.php"),"menu.hlp.php");
$test->assertEquals(true,is_file("extensions/PourTest/help/presence_online.png"),"presence_online.png");

$rep=$test->CallAction("org_lucterios_updates","UpdateServers_APAS_Del",array('CONFIRME'=>'YES','UpdateServers'=>100),"Xfer_Container_Acknowledge");
$rep=$test->CallAction("org_lucterios_updates","UpdateServers_APAS_List",array(),"Xfer_Container_Custom");
$comp1=$rep->getComponents("UpdateServers");
$test->assertEquals(1,count($comp1->m_records),"records");
unset($rep);unset($comp1);
//@CODE_ACTION@
}

?>
