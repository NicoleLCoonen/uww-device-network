<?php require_once('..\Private\Connect\initialize.php') ;

	  require_once("..\Private\DataProcessing\adminFunctions.php");

	// $adminSet = get_admins();
?>
<!doctype html>

<head>
	<title>Andersen Device Network - Admin</title>
	<meta  name="viewport" content="width=device-width, initial-scale=1.0" >
	<meta charset="utf-8" lang ="en-us">
	<link rel='stylesheet' href='..\Private\adminStyling.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	
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
					<td><?php //echo(full_name($admin)); ?></td>
					<td><?php //echo($admin["Email"]); ?></td>
					<td>
						<button type='button' class='edit'>Edit</button>
						<button type='button' class='delete'>Delete</button>
					</td>
				</tr>
			<?php
					};	
				  };
			?>
			
		</table>
		
		<form id='adminForm' method='post' action='<?php echo(url_for('admin.php'))?>' >
			<div>
				<label for='fName'>First Name</label>
				<input type='text' name='First_Name' required='true'></input>
			</div>	
			<div>
				<label for='LName'>Last Name</label>
				<input type='text' name='Last_Name' required='true'></input>
			</div>	
			<div>	
				<label for='email' required='true'>Email</label>
				<input type='email' name='Email'></input>
			</div>	
			
			<div>
				<label for='dept'>Department</label>
				<select name='dept'>
					<option value='1'>Access Services</option>
					<option value='2'>Reference</option>
					<option value='3'>Systems and Technical Services</option>
					<option	value='4'>Administration</option>
					<option	value='5'>Archives and Special Collections</option>
				</select>	
			</div>	
			<div>
				<label for='adminStatus'>Confer Admin Privileges:</label>
				<input type='checkbox' name='adminStatus'></input>
			</div>		
				<button type='submit'>Submit</button>
		
		</form>
		
		<?php
			if(is_post_request()){
				/*$errors = validate_admin($_POST);
				$pwd = validate_password($_POST['Password']);
				print_r($pwd);*/
				
			
				
			};
		?>
	
	</main>

</body>