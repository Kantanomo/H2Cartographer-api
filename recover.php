<?php 
@$email = $_POST['user']['email'];
@$recovery_attempt_success = false;
@$recovery_page = "http://69.195.136.203/H2Cartographer/api/resetpassword.php?recoveryKey=";
function guidv4()
{
    if (function_exists('com_create_guid') === true)
        return trim(com_create_guid(), '{}');

    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
if(!empty($email))
{
	$conn = mysql_connect('127.0.0.1','root','PlatinumPlatinum1!');
	mysql_select_db('H2Cartographer');

	$exists_query = mysql_query("SELECT username, id FROM user WHERE email='".mysql_real_escape_string($email)."';") or die("Exists 1:".mysql_error());

	if(mysql_num_rows($exists_query) == 1)
	{
		$result = mysql_fetch_row($exists_query);
		$user_id = mysql_real_escape_string($result[1]);
		$username = mysql_real_escape_string($result[0]);
		$recoveryKey = md5(guidv4() . $user_id);
		$insert_query = mysql_query("INSERT INTO `user_recovery` values('" . $user_id . "','" . $recoveryKey . "');") or die(mysql_error());
		if(!$insert_query){
			$recovery_attempt_success = false;
		} else {
			$recovery_attempt_success = true;
			$headers = "From: " . strip_tags("admin@h2pc.org") . "\r\n";
			$headers .= "Reply-To: ". strip_tags("admin@h2pc.org") . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			mail($email, "Account Recovery: " . $username, "<a href=\"" . $recovery_page . $recoveryKey . "\">Click this to reset your password</a>", $headers);
		}
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Cartographer Account System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="site.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Project Cartographer</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
	<h1>Servers Online: 0 ( Ignore this it doesn't work yet)</h1>
    </div>
  </div>
</nav>


  
<div>
<div class="container" >
    <div class="col-md-6" style="margin: 0 auto;" >
        <div id="logbox"  >
			<?php 
				if(!$recovery_attempt_success) {
			?>
				<form id="recovery" method="post" action="recover.php" >
					<h1>Recover Account</h1>
					<input name="user[email]" type="email" placeholder="Email address" class="input pass"/>
					<input type="submit" value="Recover Account" class="inputButton"/>
				</form>
			<?php
				} else {
			?>
				<h1>Recovery Email Sent to: <?php echo $email; ?></h1>
			<?php
				}
			?>
        </div>
    </div>
    <!--col-md-6-->


</div>
</div>

</body>
</html>
