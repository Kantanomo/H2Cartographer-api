<?php
$email = $_POST['user']['email'];
$pass = $_POST['user']['password'];
$username = $_POST['user']['name'];
$register_success = false;
if(!empty($email) && !empty($pass) && !empty($username))
{
	$conn = mysql_connect('127.0.0.1','root','PlatinumPlatinum1!');
	mysql_select_db('H2Cartographer');

	$exists_query = mysql_query("SELECT username FROM user WHERE username='".mysql_real_escape_string($username)."' or email='".mysql_real_escape_string($email)."';") or die("Exists 1:".mysql_error());

	if(mysql_num_rows($exists_query) == 0)
	{
		mysql_query("INSERT INTO user  (username,password,email) VALUES ('".mysql_real_escape_string($username)."','".md5($pass)."','".mysql_real_escape_string($email)."');") or die("Insert:".mysql_error());
		$register_success = true;
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
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
     
      <center><a class="navbar-brand" href="#">Project Cartographer</a></center>
	  
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      
    </div>
  </div>
</nav>


  
<div>
<div>
<div class="container" >
    <div class="col-md-11 col-sm-4" >
        <div id="logbox"  >
                <h1>Registration</h1>
				<?php
					if($register_success == true)
					{
					?>
						Registration was succesfully completed!<br>
					<center>
						You're going to need to update this info in the launcher now!<br><br>
						Do not share this login information it will be used for statistics tracking and other things in the future, and sharing the information will cause yours and others games not to work properly.<br><br>
						<br>
						<h1>Disclaimer</h1>
						This is a modification, this is not an official release of software.<br><br>We are not responsible for any damage, frustration, or pain this may cause you ;).<br><br>That incudes from losing endless games.
						<br><br><br>

						<h1><b><font color="red">!WARNING!</font></b></h1><br>Modding and hacking are strictly prohibited while playing on Project Cartographer.<br>Unless it's otherwise allowed by the server you're playing on,<br><br>Doing so <b>WILL</b> result in your account being banned if it continues to happen we will be forced to ban your IP and possibly your IP range as well.</center>
					<?php
					}else{
					?>
					<center><font color="red">REGISTRATION FAILED!</font></center>
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


