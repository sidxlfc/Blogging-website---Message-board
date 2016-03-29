<html>
<head><title>Message Board</title>
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/style.css">
  <style>

    div.parent
    {
      background-color:magenta;
    }

    div.up 
    {  
      background-color:lightblue;
      position: relative;
    }
    
    div.down 
    { 
      background-color:#FF2365; 
    }

    h2
    {
      text-align: center;
      background-color: cyan;
    }

  </style>
</head>
<body>
<?php
session_start();
  if(isset($_GET['username']))
  {
    $_SESSION['username'] = $_GET['username'];
    $uname = $_SESSION['username'];
  }
header('Content-Type: text/html');
$uname = $_SESSION['username'];
print "<h2>Welcome " . $uname . "</h2>";

try
{
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}

catch (PDOException $e) 
{
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}

  if(isset($_GET['reply']))
  {
    $id = $_GET['reply'];
    $msg = $_POST['message'];
    try
    {
      $stmt = $dbh->prepare("insert into posts (id,replyto,postedby,datetime,message) values (:id, :replyto, :postedby, :datetime, :message)");
      $stmt->bindParam(':id', uniqid());
      $stmt->bindParam(':replyto', $id);
      $stmt->bindParam(':postedby', $uname);
      $stmt->bindParam(':datetime', date('Y-m-d H:i:s'));
      $stmt->bindParam(':message', $msg);
      $stmt->execute();
      unset($_GET['reply']);
      unset($_POST['message']);
    }

    catch (PDOException $e)
    {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }  
  }

?>

<form action = "board.php" method = "POST">
  <input name = "logout" type = "submit" value = "Logout" />
 </form>

<?php
    if(isset($_POST['logout']))
    {
      unset($_SESSION['username']);
      header('Location: http://localhost:8080/project4/login.php');
    }
?>

<form action = 'board.php' method = 'POST'>
  <label for = 'message'></label>
  <textarea name = 'message' id = 'message' rows = '4' cols = '50' placeholder="Type your message here..."></textarea>
  <br/>
  <input type = 'submit' value = 'New Post'/>
  <hr/>
<?php

  $uname = $_SESSION['username'];
  if (isset($_POST['message']) && !isset($_GET['reply']))
  {
    $msg = $_POST['message'];
    try
    {
      $stmt = $dbh->prepare("insert into posts (id,postedby,datetime,message) values (:id, :postedby, :datetime, :message)");
      $stmt->bindParam(':id', uniqid());
      //$stmt->bindParam(':replyto', null);
      $stmt->bindParam(':postedby', $uname);
      $stmt->bindParam(':datetime', date('Y-m-d H:i:s'));
      $stmt->bindParam(':message', $msg);
      $stmt->execute();
      unset($_POST['message']);
      //print_r($stmt);
    }

    catch (PDOException $e)
    {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  }

try 
{
  $posts = $dbh->prepare("select * from posts, users where posts.postedby = users.username order by datetime desc");
  $posts->execute();

 while($p = $posts->fetch())
  {
    print "<div class='container-fluid'>";
    print '<div class="up"><i>' . $p['id'] . "&ensp;" . $p['username'] . "&ensp;" . $p['fullname'] . "&ensp;" . $p['replyto'] . "&ensp;" . $p['datetime'] . "</i></div>";
    print '<div class="down">' . $p['message'] . "</div>";
    print "<button type = 'submit' formaction = 'board.php?reply=" . $p['id'] . "'>Reply</button>";
    print "</div>";
    print "<hr/>";
  }
  //print "</table>";
} 
catch (PDOException $e) 
{
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
?>

</form>

</body>
</html>