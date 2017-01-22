<?php


function generate_user_data()
{
	$secure = "0.".rand(0,255).".".rand(0,255).".0";
	$secure_long = ip2long($secure);
	$xuid = rand(pow(10,16-1), pow(10,16)-1);
	$abEnet = "";
	$abOnline = "";

	for($i=0;$i<6;$i++)
	{
        	$abEnet.=chr(rand(0,255));
	}

	for($i=0;$i<20;$i++)
	{
        	$abOnline.=chr(rand(0,255));
	}

	return array("secure"=>$secure_long,"abEnet"=>$abEnet,"abOnline"=>$abOnline,"xuid"=>$xuid);

}

?>
