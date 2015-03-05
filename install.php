<?php
include ("vars.php");
$db_array = array("Server"=>$db_server,'Name'=>$db_name,'User'=>$db_user,'Password'=>$db_pass);
### OPEN CONNECTION ###
$dbhandler = new mysqli($db_array['Server'], $db_array['User'], $db_array['Password'], $db_array['Name']) or die ("Unable to connect to Database-Server!"); # Opens db connection
### CREATE DATABASE ###
$dbname = $uberspaceuser."_".$db_name;
$sql = "CREATE DATABASE ".$dbname." COLLATE utf8_unicode_ci";
if ($dbhandler->query($sql) === TRUE) {
	echo "Database '".$db_array['Name']."' created successfully<br><br>";
} 
else {
	echo "Error creating database: ".$dbhandler->error."<br><br>";
}
### CREATE TABLE ###
#$sql = "USE ".$db_array['Name'];
#$dbhandler->query($sql);
mysqli_select_db ($dbhandler, $dbname);
$sql="CREATE TABLE ".$dbname.".mailboxlist (
	id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	boxname VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL, 
	destination VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL, 
	duration INT(7) NOT NULL, 
	creationdate TIMESTAMP NOT NULL, 
	comment VARCHAR(127) COLLATE utf8_unicode_ci NOT NULL)";
if ($dbhandler->query($sql) === TRUE) {
    echo "Table 'mailboxlist' created successfully<br><br>";
} else {
    echo "Error creating table: ".$dbhandler->error."<br><br>";
}
### CLOSE CONNECTION ###
#$dbhandler->close();
mysqli_close($dbhandler);

$url=str_replace("install.php","delete_emails.php",__FILE__);
echo "Bitte nun noch den Befehl 'crontab' in die SSL-Shell eingeben und dort die Zeile<br><br>15 * * * * /".$url."<br><br>ergänzen und abspeichern.<br>
Nun wird alle 15 Minuten nach veralteten Emails geschaut und diese im Bedarfsfall gelöscht.";
?>
