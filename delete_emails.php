<?php
include ("vars.php"); # defines variables used in the program
include ("loc_".$lang.".php");
include ("functions.php");

$db_array = array("Server"=>$db_server,'Name'=>$db_name,'User'=>$db_user,'Password'=>$db_pass);

function send_status_mail($to) { # configures the status-email
	$subject=$GLOBALS["email_subject"];
	$message=$GLOBALS["email_message"].$boxname;
	$from="From: trashmail-deamon@".$GLOBALS['domain']."\r\n";
	mail($to,$subject,$message,$from);
	echo "Email gesendet";
}

function planned_delete ($db_array,$path){ # used to cleanup outdated mailboxes
	$dbhandler="";
	open_db($dbhandler,$db_array);
	$request = $dbhandler->query("SELECT * FROM `mailboxlist`");
	while ($row = mysqli_fetch_array($request)) {
		$current = time();
		$current_readable = date('Y-m-d H:i:s', time());
		$creation = strtotime($row["creationdate"]);
		$creation_readable = $row["creationdate"];
		$ttl = $row["duration"]*60*60;
		$removing = $creation + $ttl;
		$removing_readable = date('Y-m-d H:i:s',$removing); # converts 
		if ($current >= $removing) { # Action for expired mailboxes
			$boxname = $row["boxname"];
			delete_mailbox($db_array,$boxname,$path);
			$to=$row["destination"];
			sent_status_mail($to);
			#echo "Die Mailbox ".$row["boxname"]." wurde gelöscht!</br>";
		}
		else { #echo "for debug purposes</br>"; 
		}
	}
	mysqli_close ($dbhandler);
}

planned_delete ($db_array,$path);
?>
