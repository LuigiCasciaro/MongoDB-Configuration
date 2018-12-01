<?php
	require 'vendor/autoload.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title> MongoTestSite </title>
	  <style>
	    body{ background-color:powderblue; }
      .container{
        width:55%;
        border:solid 1px #222;
        padding-left:45%;
        padding-top:20px;
        padding-bottom:20px;
      }
  
  </style>
	</head> 
	<body style="padding:15px;">
			<p style="text-align:center; font-size:30px; font-weight:bold">  Welcome </p>
	<?php
		session_start();
		$pageToShow = isset($_SESSION['userLoggedMD5']) ? "logout" : "login";
		$user = "";
		$pass = ""; 
		$action = "";
		if(isset($_SERVER['REQUEST_METHOD'])) {
      if(isset($_POST["action"])) {
        $action = $_POST["action"];
        if($action == "login" || $action  == "logout") {
          // in both login and logout calls the old session expires  
          unset($_SESSION['userLoggedMD5']);
          $pageToShow = "login";
          // login
          if($action == "login") {
            if(isset($_POST["user-log"]) && isset($_POST["pass-log"])) {
              $user=$_POST["user-log"];
              $pass=$_POST["pass-log"];
              try {
		            $client = new MongoDB\Client;
	              $collection = $client->test->users;
	              $query = 'function() { return this.user == hex_md5("'.$user.'") && this.pass == hex_md5("'.$pass.'")}'; //aa')  || sleep(19999) && md5('aaa
                $result = $collection->findOne(array('$where' => $query));
                if($result != null) {
                  if($result["user"] != null) {
                    $_SESSION['userLoggedMD5'] = $user;
                    $pageToShow="logout";
                  }
                }
	            } catch (Exception $e) {}
            }
          }
        }
      }
    }
		if($pageToShow == "login") {
		?>
			<div class="container">
   			<p style="margin-top=15px; margin-bottom:35px; font-size:20px;"> Login: </p>
       	<form action="loginMD5.php" method="post">
      		Username:</br> <input type="text" name="user-log"/><br></br>
      		Password:</br> <input type="password" name="pass-log"/><br></br>
        	<input type="submit" style="margin-top:20px; margin-left:60px;" name="action" value="login"/>
      	</form>
   	 	</div>
	  <?php 
	  } else { ?>
		<div class="container">
			<h2> Logged in user: <?php echo $_SESSION['userLoggedMD5']?> </h2>
     	<form action="loginMD5.php" method="post">
    	  <input type="submit" style="margin-top:20px; margin-left:60px; display:block" name="action" value="logout"/>
    	</form>
 	 	</div>
		<?php
		}
	?>
	</body>
</html>
