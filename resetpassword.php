<?php
@$recoveryKey = $_GET['recoveryKey'];
if(empty($recoveryKey))
	$recoveryKey = $_POST['recoveryKey'];
@$password = $_POST['user']['password'];
@$validRecoveryKey = false;
@$passwordChanged = false;
if(isset($recoveryKey)){
	$conn = mysql_connect('127.0.0.1','root','PlatinumPlatinum1!');
	mysql_select_db('H2Cartographer');
	$exists_query = mysql_query("SELECT id FROM user_recovery WHERE recovery_key='".mysql_real_escape_string($recoveryKey)."';") or die("Exists 1:".mysql_error());
	if(mysql_num_rows($exists_query) == 1){
		$result = mysql_fetch_row($exists_query);
		$user_id = mysql_real_escape_string($result[0]);
		$validRecoveryKey = true;
		if(!empty($password)){
			$pass = md5($password);
			$update_query = mysql_query("UPDATE user SET password = '" . $pass . "' WHERE id = '" . $user_id  . "';") or die(mysql_error());
			if($update_query){
				$passwordChanged = true;
				$remove_query = mysql_query("DELETE FROM user_recovery WHERE id = '" . $user_id . "';") or die(mysql_error());
			}
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
    <div class="col-md-6" style="margin:0 auto;">
        <div id="logbox"  >
			<?php
				if($validRecoveryKey) {
			?>
				<?php
					if($passwordChanged) {
				?>
					<h1>Password reset!</h1>
				<?php 
					} else {
				?>
					<form id="passwordreset" method="post" action="resetpassword.php" >
						<h1>Reset password</h1>
						<input name="user[password]" type="password" placeholder="Choose a password" required="required" class="input pass"/>
						<input name="recoveryKey" type="hidden" value="<?php echo $recoveryKey ?>"/>
						<input type="submit" value="Set Password" class="inputButton"/>
					</form>
				<?php
					}
				?>
			<?php 
				} else {
			?>
				Invalid Recovery Key.
			<?php
				} 
			?>
        </div>
    </div>
</div>
</div>

</body>
</html>
