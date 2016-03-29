<html>
<head>
	<title>Login Page</title>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>

	<br/>
	<br/>
	<br/>
	<br/>
	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Login</div>
				<div class="panel-body">

	<form class="form-horizontal" role="form" action = "login.php" method = "POST">
		<div class="form-group"><div class="col-md-6"><label class="col-md-4 control-label">Username</label><input name = "username" type = "text"/></div></div>
		<div class="form-group"><div class="col-md-6"><label class="col-md-4 control-label">Password</label><input name = "password" type = "password"/></div></div>
		<input type = "submit" value = "Submit" class="btn btn-primary" style="margin-right: 15px;"/>
	</form>

				</div>
			</div>
		</div>
	</div>
	</div>

	<?php

		if(!empty($_POST['username']) && !empty($_POST['password']))
		{
			$uname = $_POST['username']; 
			$pass = $_POST['password'];

			//print $uname . $pass;

			$pass = md5($pass);
			//print $uname . $pass;

			try
			{
				$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	  			
	  			$dbh->beginTransaction();	  
			  	$stmt = $dbh->prepare("select * from users where username='$uname' and password='$pass'");
			  	//$stmt = $dbh->prepare('select * from users');
			  	
			  	$stmt->execute();
			  	
			  	while ($row = $stmt->fetch()) 
  				{
    				print (String)$row[2];
    				header('Location: http://localhost:8080/project4/board.php?username=' . $uname);
    				exit();
  				}
			}

			catch (PDOException $e) 
			{
			  print "Error!: " . $e->getMessage() . "<br/>";
			  die();
			}		  	
		}
	?>
	
</body>
</html>