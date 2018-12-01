<!--
Javascript injection with where clause:
https://arxiv.org/ftp/arxiv/papers/1506/1506.04082.pdf
https://media.blackhat.com/bh-us-11/Sullivan/BH_US_11_Sullivan_Server_Side_WP.pdf
 -->
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title> Even-Odd </title>
	  <style>
      p {
        font-weight: italic;
        font-size: 28px;
      }
    </style>
	</head> 
	<body style="margin:15px; background-color:powderblue;">
		<p style="text-align:center; font-size:30px; font-weight:bold"> 
      Search By Even or Odd Value
		</p>
		<div style="display:table; margin-left:45%">
     	<form action="search_by_evod_value.php" method="get">
    		<br><br> Insert an integer value:
        <input type="input" name="searchInput"> <br><br>
      	<input type="submit" name="" value="Search"/>
    	</form>
		</div>

	<?php
		require 'vendor/autoload.php';
		if(isset($_SERVER['REQUEST_METHOD'])) {
			try{	
				$method = $_SERVER['REQUEST_METHOD'];
				if ($method == "GET" && isset($_GET['searchInput'])) {
				  $client = new MongoDB\Client;
				  $collection = $client->test->users;
					$value = (int)$_GET["searchInput"];
					$result = $collection->findOne(array("evod"=>$value));
					if($result != null && $result["user"] != null) {?>
    
        <div style="margin:15px;">
        <center>
        <br><br>
        <h2> Search Result: </h2>
        <div style="border-style: solid; border-width: medium; border-color: red; padding: 15px;"> 
          <p> User: <?php echo $result["user"]; ?> </p>
          <p> Associated value: <?php echo $result["evod"];?> </p> 
        </div>
        <?php
					    }
				    }
			    } catch (Exception $e) {}
		    }
	    ?> 
	  </center>
	</body>
</html>
