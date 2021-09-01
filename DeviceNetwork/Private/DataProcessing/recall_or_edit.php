<?php
	// this code handles the Recall functionality
	if(is_post_request() && isset($_POST['portFloor'])){
		$deviceID = $_POST['deviceID'];
		$deviceName = $_POST['deviceName'];
		$newPort = $_POST['newPort'];
		
		if(isset($_POST['newUser'])){
			$user = $_POST['newUser'];
		};
		
		// Pulls the correct full record from the graveyard 
		// (So we don't have to submit all that data in the form!)
		$sql = "SELECT * FROM graveyard WHERE ID=" . $deviceID ; 
		$sql .= " AND Device_Name='" . $deviceName . "'";
		$result = mysqli_fetch_assoc(mysqli_query($db, $sql)) ;
		//print_r($result);
		
		/* Since the graveyard houses lots of different types of equipment,
			and we don't keep the same kinds of data on all of them,
		   we need to extract  only the relevant fields when we return a device to 
		   its origin table.  */
		$table = $result['Origin_Table'];
		unset($sql);
		// This pulls the first record from the relevant table
		$sql = "SELECT * FROM " . $table . " LIMIT 1";
		$comparison = mysqli_fetch_assoc(mysqli_query($db, $sql));
		// The keys tell us what data from the graveyard is needed
		$keys = array_keys($comparison);
		
		unset($sql);
		 $sql = "INSERT INTO ". $table ." SET " ;
		 
		 //pulls the relevant values out of the graveyard result & creates the $sql
		 foreach($keys as $key){
			// I was a dummy and didn't use "Device_Name" in every table,
			// but so much code is already written that it's easier to just fix it here. 
			if($key === 'Computer_Name'){
				$sql .= $key . "='" . $result['Device_Name'] . "', ";
			}else if($key === "Connection" || $key === 'ID'){
				// Items in the graveyard by definition don't have a connection, and will need a new ID so we need to skip those.
				continue;
			}else if($key === 'User' && isset($user)){
				//items in the graveyard also lack a user, by definition, so we'll set it here if need be.
				$sql .= $key . "=" . $user .", ";
			}else{
				$data = $result[$key];
				if(is_numeric($data)){
					$sql .= $key . "=" . $result[$key] .", ";
				}else if(is_string($data)){
					$sql .= $key . "='" . $result[$key] ."', ";
				};
			};
		 };
		 // give the device its new connection
		 $sql .= 'Connection=' . $newPort ;
		 
		 if(mysqli_query($db, $sql)){
			 // Once the data is reinserted to its origin table, the corresponding record is removed from the graveyard
			 $sql = "DELETE FROM graveyard WHERE ID=" . $deviceID ;
			 $sql .= "AND Device_Name='" . $deviceName . "'";
			 if(mysqli_query($db, $sql)){
				 // if all steps are completed successfully, outputs an alert to notify the user that the action was successful.
				echo "<script> window.alert('Device restored succefully.')</script>";
			 };
			 
		 }else{
			 // if one or more errors occurred, output an alert to notify the user what went wrong.
			  echo "<script> window.alert('Device could not be restored. " . mysqli_error($db) . "')</script>";
		 };
	
	};
?>