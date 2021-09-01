<!doctype html>
<?php require_once("../Private/Connect/initialize.php");?> 
<head>
	<title>Andersen Device Network</title>
	<meta  name="viewport" content="width=device-width, initial-scale=1.0" >
	<meta charset="utf-8" lang ="en-us">
	<link rel='stylesheet' href='../Private/FAQ.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src='../Private/FAQ.js'> </script>
</head>

<body>
	<header class='masthead'>
		<h1>UW-W Libraries Device Network</h1>
		<h2>FAQs</h2>
		<span>
			<button class='norm' type='button' id='changeLibrary' data-url='<?php echo url_for("DeviceNetworkLenoxUpper.php");?>'>Lenox Maps</button>
			<button class='norm' type='button' id='change-Library' data-url='<?php echo url_for("DeviceNetworkMain.php");?>'>Andersen Maps</button>
			<button class='norm' type="button" id="reports" data-url='<?php echo url_for("reports.php");?>'>Run Reports</button>
		</span>
		
	</header>
	
	<main>
		<div id='container'>
			<form method="post" action='<?php echo(url_for("FAQ.php")); ?>' id='topicForm'>
				<div id="topics">
				
					<div class='topic'>
						<button type='button' data-topic= "general" >Using the Device Network</button>
						<p>Whether you're using this tool for the first time or in need of a refresher about what it can do and how to do it, you can find that information under this topic.</p>
					</div>
				
					<div class='topic'>
						<button type='button' data-topic="withdaw" >Withdrawing Devices</button>
						<p>Eventually, all equipment will be replaced. Here's what we need to know before we send our devices off.</p>
					</div>
					
					<div class='topic'>
						<button type='button'data-topic="wireless" >Managing Wireless Devices</button>
						<p>For more on managing records of wireless devices.</p>
					</div>
					
					<div class='topic'>
						<button type='button' data-topic="admin" >Admin Tasks</button>
						<p>For adding new Staff, removing old Staff, changing admin passwords, and the like.<p>
					</div>
					
					<div class='topic'>
						<button type='button' ndata-topic="reports" >Running Reports</button>
						<p>Need a breakdown of what we have where, how much we've spent on a deptartment's computers, or where available ports are? We have a report for that.<p>
					</div>
					
					<div class='topic'>
						<button type='button' data-topic="bugs" >Reporting Bugs</button>
						<p>Find something a little strange? How to tell what the developer knows, whether it's intended or very, very wrong, and what to do about it.</p>
					</div>
					
				</div>	
			</form>
		<div id='display'>	
			<div id='general'>
				<h3>Using the Device Network</h3>
				<p>This section provides general information about using the tool. If you can't find what you're looking for, try another topic 
					or contact the developer at <a class='email' href='mailto:coonenn@uww.edu'>coonenn@uww.edu</a> or <a class='email' href='mailto:nicolecoonen@gmail.com'>nicolecoonen@gmail.com</a>. </p>

				<ol>
					<li><p>What is the map showing me?</p>
						<details>
							<p>Each marker on the map represents a network access point containing at least one data port. Inside each marker is a table of ports found
							at that point and their status. If a device (such as a computer or printer) is accessing the network from a port in the group, a table containing
							 relevant details about that device will be attached to the port it is physically plugged into. <em>This map gives a general location, because
							 a device's specific physical location is not represented, merely its connection to a fixed point on the map!</em> This way, we don't need to 
							 remember to update the maps when we move a computer from the right of a column to the left, only when it moves to another port group. While
							 this makes it more dificult to know which of two computers connected at a point on the map you need to be looking at in physical space,
							 you should already have the name of the computer or its noncap number, and armed with that information, you will be able to distinguish 
							 which device requires your attention. </p>
						</details>
					</li>
					<li><p>How do I see more detailed information about the ports and devices at a specific location on the map?</p>
						<details>
							<p>When you hover your mouse over or click on a marker on the map, a caption will show the number of ports and devices at that location.
							Underneath that, there is a button that says "Details", which yu can click to display port information. If a port has a device connected to it,
							there will be a button with an electrical plug icon next to the ports status. Clicking that button will reveal more information about the device and its
							accessories, if applicable.</p>
						</details>
					</li>
					<li><p>How do I update port or device information?</p>
						<details>
							<p>To display the update form, double-click on the port or device you want to update. A form will appear to the right of the map, prefilled with the
							 information of the port and conncected device and accessories, if applicable. If no device is connected, only the port information will be pre-filled.
							  From this form, you can move a device to a new location, add a new device, or remove a device from the collection. Once you've entered or changed 
							  information, click the "Update" button to submit it to the database. You can rename a port or change 
							  its status, but device information is assumed to have been entered accurately, so to maintain proper records, device info is designed to be difficult 
							  to update. If you made a mistake, you'll have to withdraw the device, edit it, then recall it to the collection, so it's best to pay attention and 
							  enter the information correctly the first time. You can see more about withdrawing, editing and recalling under the "Withdrawing Device" FAQ. </p>
						</details>
					</li>
					<li><p>How do I move a device to a new location?</p>
						<details>
							<p>To move a device, click the "Move" button. This will clear all port information from the form. You can then select a new port by clicking its name
							on the map or by typing/pasting the port name into the appropriate field. The latter method is used to move a device between floors or libraries. Make 
							sure to set the port satus to "On" and leave the "Broken" box unchecked before submitting!</p>
						</details>
					</li>
					<li><p>How do I move add a new device?</p>
						<details>
							<p>To add a device, select an empty port, make sure the status is set to "On", and click the "New Device" button. This will unlock three form fields
							which you can then fill with relevant data. Be aware that vendor information should only be included for printers, scanners, and other devices provied by 
							third-party contractors. Desktops do not have a vendor, so no vendor infomation should be included. It is very important <em>not</em> to include vendor information 
							on computers. Once you have filled the relevant fields accurately, click the "Update" button to submit the form and add the device to the map.</p>
						</details>
					</li>
					<li><p>How do I use the search tool?</p>
						<details>
							<p>The search tool is case sensitive! You'll need to be familiar with some of the naming conventions our equipment uses. All public computers in Andersen
							start with "L" followed by a room number, while those in Lenox all begin with "AH-109". In both libraries, the prefix will be followed by a hyphen and two digits,
							followed by the suffix "-LP" (for Lab PC) or "-LM" (for Lab Mac).</p>
							
							<p>Unfortuanately,the naming of our data ports is wildly inconsistent: Some start with "IDF", some with "L" plus the room number, some simply with "D-". The formatting
							  is also all over the place. I suggest focusing on the part of the name that will be unique, usually the second half of a hyphenated pair (i.e. the 
							  "-D70B" in "L2213-D70B"). Some port names will have two hyphenated pairs, but the second half of each pair is usually unique. Be aware hat typing in a non-unique piece
							  of a name, such as '09' will match <em>all</em> instances of that pair of digits. Such a search would match all of the following: AH-1<strong>09</strong>-12-LP, anything
							  with a noncap such as 3<srong>09</strong>712,a printer with a vendor contact of 462-871-23<strong>09</strong> and port L2113 D1<strong>09</strong>. Therefore, it's best 
							 to use as specific a search term as you can.</p>
							<p>You can also use the search tool for borader searches. For example, if you want to know where all of the 8300 AIO PCs are on the main floor of Andersen,
							typing in "8300 AIO" in the "Search" field will highlight all of the port groups with at least one of that model connected.</p>
						</details></li>
				</ol>
				
			</div>
			<div id='withdraw'>
				<p> This section is in progress! Come back soon! If you are encountering an issue, please contact the developer at 
				<a class='email' href='mailto:coonenn@uww.edu'>coonenn@uww.edu</a> or 
				<a class='email' href='mailto:nicolecoonen@gmail.com'>nicolecoonen@gmail.com</a>.</p>
			</div>
			<div id='wireless'>
				<p> This section is in progress! Come back soon! If you are encountering an issue, please contact the developer at 
				<a class='email' href='mailto:coonenn@uww.edu'>coonenn@uww.edu</a> or 
				<a class='email' href='mailto:nicolecoonen@gmail.com'>nicolecoonen@gmail.com</a>.</p>
			</div>
			<div id='admin'>
				<p> This section is in progress! Come back soon! If you are encountering an issue, please contact the developer at 
				<a class='email' href='mailto:coonenn@uww.edu'>coonenn@uww.edu</a> or 
				<a class='email' href='mailto:nicolecoonen@gmail.com'>nicolecoonen@gmail.com</a>.</p>
			</div>
			<div id='reports'>
				<p> This section is in progress! Come back soon! If you are encountering an issue, please contact the developer at 
				<a class='email' href='mailto:coonenn@uww.edu'>coonenn@uww.edu</a> or 
				<a class='email' href='mailto:nicolecoonen@gmail.com'>nicolecoonen@gmail.com</a>.</p>
			</div>
			<div id='bugs'>
				<p> This section is in progress! Come back soon! If you are encountering an issue, please contact the developer at 
				<a class='email' href='mailto:coonenn@uww.edu'>coonenn@uww.edu</a> or 
				<a class='email' href='mailto:nicolecoonen@gmail.com'>nicolecoonen@gmail.com</a>.</p></p>
			</div>
		</div>	
	</div>
