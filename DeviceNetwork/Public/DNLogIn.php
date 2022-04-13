<?php require_once("../Private/Connect/initialize.php");
	 // require_once("../Private/DataProcessing/adminFunctions.php");
	  	
?>

<!doctype html>
<head>
	<title>Andersen Device Network - Log In </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
	<link rel='stylesheet' href='../Private/Styling/portViewStyling.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src=''> </script>
</head>

<body>
	<main>
		<form method="post" action="<?php echo(url_for('DNLogIn.php'))?>">
			<span>
				<label>NetID: </label>
				<input type='text' id='username' name='username'></input>
			</span>
			<span>
				<label>Password:</label>
				<input type='password' id='pwd' name='pwd'></input>
			</span>	
			<button type='submit'> </button>
		</form>
		
		<?php
			if(is_post_request()){
				if(isset($_POST['username']) && isset($_POST['pwd'])){
					$username = $_POST['username'];
					$password = $_POST['pwd'];
					
					// Write hashing here? 
					
					$sql = "SELECT * FROM admins WHERE username='" . $username . "'";
					$sql .= "AND password='" . $password . "'";
					
					if(mysqli_query($db, $sql)){
						
					}else{
						
					};
							
				}else{
					echo("You must provide a valid username and password.");
				};
			}
		?>
	</main>
</body>