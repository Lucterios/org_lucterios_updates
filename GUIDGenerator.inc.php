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
// library file write by SDK tool
// --- Last modification: Date 23 September 2011 11:21:12 By  ---

//@BEGIN@

function getSum($ip,$unixtime,$micro,$rand) {
	$ip = hexdec($ip);
	$unixtime = hexdec($unixtime);
	$micro = hexdec($micro)/65536;
	$rand = hexdec($rand);
	$sum = ($ip%256)+($unixtime%256)+($micro%256)+($rand%256);
	$sum = ($sum%256);
	return $sum;
}

function clientIPToHex($ip = "") {
	$hex = "";
	if($ip == "")$ip = getEnv("SERVER_ADDR");
	$part = explode('.',$ip);
	for($i = 0;
	$i<= count($part)-1;
	$i++) {
		$hex .= substr("0". dechex($part[$i]),-2);
	}
	return $hex;
}

function clientIPFromHex($hex) {
	$ip = "";
	if( strlen($hex) == 8) {
		$ip .= hexdec( substr($hex,0,2)).".";
		$ip .= hexdec( substr($hex,2,2)).".";
		$ip .= hexdec( substr($hex,4,2)).".";
		$ip .= hexdec( substr($hex,6,2));
	}
	return $ip;
}
// FORMAT : CheckSum(4)-IP(8)-TIME(8)-µS(4)-Random(4)

function uuid() {
	$t = explode(" ", microtime());
	$ip = clientIPToHex();
	$unixtime = substr("00000000". dechex($t[1]),-8);
	$micro = substr("0000". dechex( round($t[0]*65536)),-4);
	$rand = mt_rand(0,0xffff);
	$ip_txt = sprintf('%08s',$ip);
	$unixtime_txt = sprintf('%08s',$unixtime);
	$micro_txt = sprintf('%04x',$micro);
	$rand_txt = sprintf('%04x',$rand);
	$sum = getSum($ip_txt,$unixtime_txt,$micro_txt,$rand_txt);
	return sprintf('%04x-%s-%s-%s-%s',$sum,$ip_txt,$unixtime_txt,$micro_txt,$rand_txt);
}

function uuidDecode($uuid) {
	$rez = Array();
	$u = explode("-",$uuid);
	if( is_array($u) && ( count($u) == 5)) {
		$sum = hexdec($u[0]) & 0xFF;
		$new_sum = getSum($u[1],$u[2],$u[3],$u[4]);
		$new_sum = hexdec( sprintf('%04x',$new_sum)) & 0xFF;
		if($new_sum == $sum)$check = 1;
		else $check = 0;
		$ip = clientIPFromHex($u[1]);
		$rez = Array('sum' => $sum,'ip' => $ip,'unixtime' => hexdec($u[2]),'micro' => hexdec($u[3])/65536,'rand' => hexdec($u[4]),'check' => $check,'new_sum'=>$new_sum);
	}
	return $rez;
}


//@END@
?>
