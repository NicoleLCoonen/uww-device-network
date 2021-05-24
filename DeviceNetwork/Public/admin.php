<?php require_once('..\Private\Connect\initialize.php') ;

	  require_once("..\Private\DataProcessing\adminFunctions.php");

	 $adminSet = get_admins();
?>


<!doctype html>

<head>
	<title>Andersen Device Network - Admin</title>
	<meta  name="viewport" content="width=device-width, initial-scale=1.0" >
	<meta charset="utf-8" lang ="en-us">
	<link rel='stylesheet' href='../Private/DNPStyling.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src='../Private/DNP.js'> </script>
</head>

<body>
	<header>	
	</header>
	
	<main>
		<table>
			<th>Name</th>
			<th>Email</th>
			<th>Options</th>
			<?php if(isset($adminSet)){
				
					
					while($admin = mysqli_fetch_assoc($adminSet)){
						
			?>			
				<tr>
					<td><?php echo(full_name($admin)); ?></td>
					<td><?php echo($admin["Email"]); ?></td>
					<td>
						<button type='button' id='edit'>Edit</button>
						<button type='button' id='delete'>Delete</button>
					</td>
				</tr>
			<?php
					};	
				  };
			?>
			
		</table>
		
		<form id='adminForm' method='post' action='<?php echo(url_for('admin.php'))?>' >
			<label for='fName'>First Name</label>
			<input type='text' name='First_Name' required='true'></input>
			<label for='LName'>Last Name</label>
			<input type='text' name='Last_Name' required='true'></input>
			<label for='email' required='true'>Email</label>
			<input type='email' name='Email'></input>
			<label for ='uName'>Username</label>
			<input type='text' name='Usernameame'required='true'></input>
			<label for='pwd'>Password</label>
			<input type='password' name='Password' required='true'></input>
			<label for='confirmPwd'>Confirm Password</label>
			<input type='password' name='Confirm_Password' required='true'></input>
			
			<button type='submit'>Submit</button>
		
		</form>
		
		<?php
			if(is_post_request()){
				$errors = validate_admin($_POST);
				$pwd = validate_password($_POST['Password']);
				print_r($pwd);
				
			
				
			};
		?>
	
	</main>

</body>