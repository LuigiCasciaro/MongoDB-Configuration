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
      table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
      }
      th, td {
          padding: 15px;
      }
    </style>
	</head> 
	<body style="margin:15px; background-color:powderblue;">
		<p style="text-align:center; font-size:30px; font-weight:bold"> 
      Even and odd search
		</p>
		<div style="display:table; margin-left:45%">
     	<form action="get_users_by_evod.php" method="get">
    		<br><br> Select an option: <br><br>
  		  <input type="radio" name="ev-od" value="0"> User Even <br>
        <input type="radio" name="ev-od" value="1"> User Odd <br><br>
      	<input type="submit" value="search"/>
    	</form>
		</div>
	<?php
		require 'vendor/autoload.php';
		if(isset($_SERVER['REQUEST_METHOD'])) {
			try{	
				$method = $_SERVER['REQUEST_METHOD'];
				if ($method == "GET" && isset($_GET['ev-od'])) {
				  $client = new MongoDB\Client;
				  $collection = $client->test->users;
					$type = $_GET["ev-od"];
					$query = 	'function() {return this.evod%2=='.$type.'}';
					$result = $collection->find(array('$where' => $query));
					if($result != null) {?>
		    <div style="margin-top:50px;">
	        <center>
            <table>
              <tr>
                <th> User </th>
                <th> Number </th>
             </tr>
        <?php 					
					foreach ($result as $entry) {?> 
		      <tr>
		        <td><?php echo $entry['user']; ?> </td>
		        <td><?php echo $entry['evod']; ?> </td>
          </tr>		
        <?php 
                }        			
					    }
				    }
			    } catch (Exception $e) {}
		    }
	    ?> 
	    </table>
	  </center>
	
	</body>
</html>
