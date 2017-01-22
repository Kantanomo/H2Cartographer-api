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
    <div class="col-md-6" style="margin:0 auto;" >
        <div id="logbox"  >
            <form id="signup" method="post" action="register.php" >
                <h1>Create an Account</h1>
				<input name="user[email]" type="email" placeholder="Email address" class="input pass"/>
				<input name="user[name]"  maxlength="15"  type="text" placeholder="Enter a username" class="input pass"/>
                <input name="user[password]" type="password" placeholder="Choose a password" required="required" class="input pass"/>
                <input type="submit" value="Create Account" class="inputButton"/>
				<input type="button" class="inputButton" value="Recover Account" onclick="window.location = 'http://69.195.136.203/H2Cartographer/api/recover.php'"></input>
            </form>
        </div>
    </div>
    <!--col-md-6-->


</div>
</div>

</body>
</html>
