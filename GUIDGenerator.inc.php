<?php

// FORMAT : CheckSum(4)-IP(8)-TIME(8)-µS(4)-Random(4)
function uuid()
{
    $t=explode(" ",microtime());
    $ip=clientIPToHex();
    $unixtime=substr("00000000".dechex($t[1]),-8);
    $micro=substr("0000".dechex(round($t[0]*65536)),-4);
    $rand=mt_rand(0,0xffff);
    $sum=getSum(hexdec($ip),$t[1],$t[0],$rand);
    return sprintf('%04x-%08s-%08s-%04s-%04x',$sum,$ip,$unixtime,$micro,$rand);
}

function uuidDecode($uuid) {
    $rez=Array();
    $u=explode("-",$uuid);
    if(is_array($u) && (count($u)==5)) {
   		$ip=clientIPFromHex($u[1]);
    	$unixtime=hexdec($u[2]);
    	$micro=hexdec($u[3])/65536;
    	$rand=hexdec($u[4]);
    	$sum=hexdec($u[0]);
    	$new_sum=getSum(hexdec($u[1]),$unixtime,$micro,$rand);
    	if ($new_sum==$sum) $check=1; else $check=0;
       	$rez=Array('sum'=>$sum,'ip'=>$ip,'unixtime'=>$unixtime,'micro'=>$micro,'rand'=>$rand,'check'=>$check);
    }
    return $rez;
}

function clientIPToHex($ip="") {
    $hex="";
    if($ip=="") $ip=getEnv("SERVER_ADDR");
    $part=explode('.', $ip);
    for ($i=0; $i<=count($part)-1; $i++) {
        $hex.=substr("0".dechex($part[$i]),-2);
    }
    return $hex;
}

function clientIPFromHex($hex) {
    $ip="";
    if(strlen($hex)==8) {
        $ip.=hexdec(substr($hex,0,2)).".";
        $ip.=hexdec(substr($hex,2,2)).".";
        $ip.=hexdec(substr($hex,4,2)).".";
        $ip.=hexdec(substr($hex,6,2));
    }
    return $ip;
}

function getSum($ip,$unixtime,$micro,$rand){
	$sum=($ip % 256)+($unixtime % 256)+($micro % 256)+($rand % 256);
	return ($sum % 256);
}

?>