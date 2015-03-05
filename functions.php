<?php 
function open_db(&$dbhandler,$db_array) { # create handler to open database
	$dbhandler = new mysqli($db_array['Server'], $db_array['User'], $db_array['Password'], $db_array['Name']) or die ("Unable to connect to Database-Server!"); # Opens db connection
	return $dbhandler;
}

function create_mailbox($db_array,$form_array,$path) { # Create a new mailbox (DB entry and file)
	echo "<div align='center'>";
	if (preg_match("/^([a-z0-9]{1}[a-z0-9\.+_-]{1,18}[a-z0-9]{1})$/i",$form_array["Boxname"])) {
		$dbhandler="";
		open_db($dbhandler,$db_array);
		$boxname = $form_array["Boxname"];
		$creationdate = time();
		$query = $dbhandler->query("SELECT * FROM `mailboxlist` WHERE boxname='".$boxname."'");	
		if (mysqli_num_rows($query)==0){
			mysqli_query($dbhandler,"INSERT INTO `mailboxlist`(`boxname`, `destination`, `duration`, `comment`) VALUES ('".$form_array['Boxname']."','".$form_array['Destination']."','".$_POST['ttl']."','".$form_array['Comment']."')");
			$boxname = str_replace(".",":",$boxname); # replace all dots with colons for qmail handling
			echo $GLOBALS["text_create_file"].": ".$path.".qmail-".$boxname."<br />";
			$filehandler = fopen($path.".qmail-".$boxname, "w+");
			# Create a new Mailbox-File
			fwrite($filehandler, $form_array['Destination']); # Writes the destination-address into the file
			echo $GLOBALS["text_box_created"];
		}
		else { echo $GLOBALS["text_box_exists"]; }
		mysqli_close ($dbhandler);
	}
	else { echo $GLOBALS["text_invalid_boxname"].": ".$form_array["Boxname"];}
	echo "</div>";
}

function list_mailbox ($db_array) { # list all available mailboxes from database
	$dbhandler="";
	open_db($dbhandler,$db_array);
	$request = $dbhandler->query("SELECT * FROM `mailboxlist`");
	?>
	<table>
	<tr><td colspan='5' align="center"><h3><?php echo $GLOBALS["text_currently_available_addresses"]; ?></h3></td></tr>
	<tr>
		<th class="list_name"><?php echo $GLOBALS["text_mailbox"]; ?></th>
		<th class="list_dest"><?php echo $GLOBALS["text_recipient"]; ?></th>
		<th class="list_expiration"><?php echo $GLOBALS["text_expiration"]; ?></th>
		<th class="list_comm"><?php echo $GLOBALS["text_comment"]; ?></th>
		
	</tr> 
	<?php
	while ($row = mysqli_fetch_array($request)) { 
		echo "<tr>";
			echo "<td class='list_name'>".$row{'boxname'}."</td>";
			echo "<td  class='list_dest'>".$row{'destination'}."</td>";
			
			if ($row["duration"] == 0) {
				$expirationdate_readable = $GLOBALS["text_infinite"];
			}
			else {
				$creationdate = strtotime($row["creationdate"]);
				$expirationdate = $creationdate + ($row["duration"]*60*60);
				$expirationdate_readable = date('Y-m-d H:i:s', $expirationdate);
			}
			
			echo "<td  class='list_expiration'>".$expirationdate_readable."</td>";
			echo "<td  class='list_comm'>".$row{'comment'}."</td>";
			echo "<td><form action=".htmlentities($_SERVER['PHP_SELF'])." method='POST'><input type='hidden' name ='delete' value='deletemailbox'><input type='hidden' name ='deleteboxname' value='".$row{'boxname'}."'><input type='image' src='delete.png' alt='".$GLOBALS["text_delete"]."' style='vertical-align:middle; display:table-cell';></form></td>";
		echo "</tr>";
	}
	echo "</table>";
	mysqli_close ($dbhandler);
}

function delete_mailbox($db_array,$boxname,$path){
	$dbhandler="";
	open_db($dbhandler,$db_array);
	$request = "DELETE FROM `mailboxlist` WHERE `boxname` = '".$boxname."'";
	echo "<div align='center'>";
	if ($dbhandler->query($request) === TRUE) {
		echo $GLOBALS["text_db_entry_removed"].": ".$boxname."<br />";
	} else {
		echo "Error removing DB entry: ".$dbhandler->error."<br />";
	}	
	mysqli_close ($dbhandler);
	$boxname = str_replace(".",":",$boxname);
	$filepath = $path.".qmail-".$boxname;
	if (unlink($filepath)) {
		echo $GLOBALS["text_file_removed"].": .qmail.".$boxname."<br />";
	} 
	else {
		echo $GLOBALS["text_file_error"]."<br />";
	}
	echo "<a href='".htmlentities($_SERVER['PHP_SELF'])."'>".$GLOBALS["text_back"]."</a><br />";
	echo "</div>";
}
?>
