<?php
include("conf.php");
$conn = mysql_connect('127.0.0.1','root','PlatinumPlatinum1!');
mysql_select_db('H2Cartographer');


@$email = mysql_real_escape_string($_POST['email']);
@$pass = mysql_real_escape_string($_POST['pass']);
@$token = mysql_real_escape_string($_POST['token']);
@$tokenServer = mysql_real_escape_string($_GET['token']);
@$username = mysql_real_escape_string($_POST['user']);
@$launcher = mysql_real_escape_string($_POST['launcher']);
@$launcherServer = mysql_real_escape_string($_GET['launcher']);
@$serial = mysql_real_escape_string($_POST['serial']);
function register($username,$pass,$email)
{
	if(!empty($email) && !empty($pass) && !empty($username))
	{
		$email_exists_query = mysql_query("SELECT id FROM user WHERE email='".mysql_real_escape_string($email)."';") or die("Exists 1:".mysql_error());

		  $username_exists_query = mysql_query("SELECT id FROM user WHERE username='".mysql_real_escape_string($username)."';") or die("Exists 2:".mysql_error());

		  $email_exists = mysql_num_rows($email_exists_query);
		  $username_exists = mysql_num_rows($username_exists_query);

		if($email_exists == 0 && $username_exists == 0)
		{
			mysql_query("INSERT INTO user  (username,password,email, banned) VALUES ('".mysql_real_escape_string($username)."','".md5($pass)."','".mysql_real_escape_string($email)."',0);") or die("Insert:".mysql_error());
			return "1";
		}
	}

	return "0";
}


function username_exists($username) {
	$username = mysql_real_escape_string($username);
	$query = mysql_query("SELECT id FROM user WHERE username='".$username."';");
	if(mysql_num_rows($query) == 0)
		return "0";
	else
		return "1";
}
function check_ban($user_id, $serial){
	$serial = mysql_real_escape_string($serial);
	$queryString = "SELECT `banned` from `user` INNER JOIN `user_machine` ON `id` = `user_machine`.`user_id` INNER JOIN `machine` ON  `user_machine`.`machine_id` = `machine`.`id` where `machine`.`serial` = '" . $serial . "' and `user`.id = '" . $user_id . "'";
	$query = mysql_query($queryString) or die(mysql_error());
	if(mysql_num_rows($query) != 0) //User is associated with the serial
	{
		$result = mysql_fetch_row($query);
		if($result[0] == 1) //User is banned
		{
			return true;
		}
		else { //Check if other accounts are banned
			$queryString = "SELECT `banned` from `user` INNER JOIN `user_machine` ON `id` = `user_machine`.`user_id` INNER JOIN `machine` ON  `user_machine`.`machine_id` = `machine`.`id` where `machine`.`serial` = '" . $serial . "' and `user`.banned = '1'";
			$query = mysql_query($queryString); 
			
			if(mysql_num_rows($query) != 0)
			{
				$queryString = "UPDATE `user` set `banned` = '1' where `id` = '" . $user_id . "';";
				mysql_query($queryString);
				return true;
			}
			else
				return false;
		}
	}
	else
	{
		$queryString = "SELECT id FROM machine where `serial` = '" . $serial . "';";
		$query = mysql_query($queryString) or die(mysql_error());
		if(mysql_num_rows($query) == 0) //Machine Serial is new
		{
			$queryString = "INSERT INTO `machine` (`id`, `serial`) VALUES (NULL, '" . $serial . "');";
			$query = mysql_query($queryString) or die(mysql_error());
			return check_ban($user_id, $serial); //recall function now that the machine id exists
		}
		else //Machine Serial already exists associate with user
		{
			$result = mysql_fetch_row($query);
			$machine_id = mysql_real_escape_string($result[0]);
			$queryString = "INSERT INTO user_machine (`user_id`, `machine_id`) VALUES(" . $user_id . ',' . $machine_id . ");";
			$query = mysql_query($queryString);
			return check_ban($user_id, $serial); //recall function now that the user is associated 
		}
	}
}
function generate_token($username)
{
	$username = mysql_real_escape_string($username);
	$token = md5(mt_rand().$username.time());
	$query = mysql_query("UPDATE user SET login_token='".$token."' WHERE username='".$username."';") or die(mysql_error());
	return $token;
}

function token_login($token, $serial)
{
	$token = mysql_real_escape_string($token);
	$serial = mysql_real_escape_string($serial);
	if(empty($token))
		return "0";

	$query = mysql_query("SELECT id FROM user WHERE login_token='".$token."';") or die(mysql_error());
	if(mysql_num_rows($query) == 0)
		return "failed";
	else 
	{
		$result = mysql_fetch_row($query);
		$user_id = mysql_real_escape_string($result[0]);
		if(check_ban($user_id, $serial))
			return "banned";
		else
			return "success";
	}
}
function server_token_login($token)
{
	$token = mysql_real_escape_string($token);
	if(empty($token))
		return "failed";

	$query = mysql_query("SELECT id FROM user WHERE login_token='".$token."' and banned='0';") or die(mysql_error());
	if(mysql_num_rows($query) == 0)
		return "failed";
	else 
	{
		$result = mysql_fetch_row($query);
		$user_id = mysql_real_escape_string($result[0]);
		return "success";
	}
}
function login($username,$pass, $serial)
{
	$username = mysql_real_escape_string($username);
	$pass = mysql_real_escape_string($pass);
	$serial = mysql_real_escape_string($serial);
	$query = mysql_query("SELECT id FROM user WHERE username='".$username."' AND password='".md5($pass)."';") or die(mysql_error());
	
	if(mysql_num_rows($query) == 0)
		return "failed";
	else
	{
		$result = mysql_fetch_row($query);
		$user_id = mysql_real_escape_string($result[0]);
		if(check_ban($user_id, $serial))
			return "banned";
		else
			return "success";
	}
}
if($launcher == 1)
{
	if(!empty($username) && empty($pass) && empty($email))
		die(username_exists($username));
	if(!empty($username) && !empty($pass) && !empty($serial) && empty($email))
	{
		$result = login($username, $pass, $serial);
		if($result == "success")
		{
			die(generate_token($username));
		}
		else if ($result == "banned")
		{
			die("banned");
		}
		else if($result == "failed")
		{
			die("0");
		}
		else
		{
			die("0");
		}
	}

	if(!empty($username) && !empty($pass) && !empty($email))
		die(register($username,$pass,$email));

	if(!empty($token) && !empty($serial))
	{
		$result = token_login($token, $serial);
		if($result == "success")
		{
			die("1");
		}
		else if ($result == "banned")
		{
			die("banned");
		}
		else if($result == "failed")
		{
			die("0");
		}
		else
		{
			die("0");
		}
	}
}

if(empty($launcherServer))
{

	$response = array();

	if( server_token_login($tokenServer) == "failed" )
	{
		$response = array("status"=>"fail","email"=>$email,"pass"=>$pass);

		$fh = fopen("user_data.log","a");
		fwrite($fh,"token: ".$tokenServer."\n");
		fclose($fh);

		die(json_encode($response));
	}else{

		$user_data = mysql_query("SELECT id, username FROM user WHERE login_token='".$tokenServer."'") or die(mysql_error());
		$user = mysql_fetch_object($user_data);

		/* $response = array("status"=>"success",
		"username"=>$user->username,
		"xuid"=>formatXUID($user->id),
		"saddr"=>formatSecure($user->id),
		"abenet"=>formatABNet($user->id),
		"abonline"=>formatABOnline($user->id),
		"id"=>$user->id); */
		$response = array("status"=>"success",
		"username"=>$user->username,
		"id"=>$user->id);
		die(json_encode($response));
	}
}
function formatSecure($uid)
{
	$tpad = str_pad(dechex($uid), 4,"0", STR_PAD_LEFT);
	$ipos = strlen(str_pad(hexdec($tpad[0] . $tpad[1]), 1,"0", STR_PAD_LEFT));
	$tpad = str_pad(hexdec($tpad[0] . $tpad[1]), 1,"0", STR_PAD_LEFT) . str_pad(hexdec($tpad[2] . $tpad[3]), 1,"0", STR_PAD_LEFT);
	return strval(ip2long("0." . substr_replace($tpad, ".", $ipos,0 ) . ".0"));
}
function formatXUID($uid)
{
	return str_pad(dechex($uid), 16, "0", STR_PAD_LEFT);
}
function formatABNet($uid)
{
	return str_pad(dechex($uid), 12, "0", STR_PAD_LEFT);
}
function formatABOnline($uid)
{
	return str_pad(dechex($uid), 40, "0", STR_PAD_LEFT);
}
?>
