<?php
					#######################################
					#######################################
					#######################################
					#####                             #####
					#####   Mailbox-Administration    #####
					#####                             #####
					#####   for Uberspace Webspace    #####
					#####                             #####
					#######################################
					###############by loh.re###############
					#######################################

include ("vars.php"); # defines variables used in the program
include ("functions.php");
include ("loc_".$lang.".php");


$db_array = array("Server"=>$db_server,'Name'=>$db_name,'User'=>$db_user,'Password'=>$db_pass); 

function test_input($data) { 
	$data = trim($data);	# removes spaces, tabs or nl chars
	$data = stripslashes($data); # removes \
	$data = htmlspecialchars($data); # convert special chars to html entities
	return $data;
}

function random_string($length=10) { # generate random string
	$characters = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$characters_length = strlen($characters) - 1;
	$rand = '';
	for ($i = 0; $i < $length; $i++) {
		$rand .= $characters[mt_rand(0, $characters_length)];
	}
	return $rand;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $text_site_title; ?></title>
<link rel="stylesheet" type="text/css" href="style.css">
<body>
<div class="header">
<img class="header" src="header.png"></img>
</div>
<?php
if ( ( isset($_POST["mailbox"]) || isset($_POST["random"] ) ) && isset($_POST["destination"], $_POST["ttl"] ) ){ # check for POST
	if (isset($_POST["random"])) {	# check for $random
		$boxname = test_input($_POST["random"]);
		$rand=random_string(6);
		$date=date('Ymd');
		$boxname = "temp-".$date."-".$rand;
		$destination = $_POST["destination"];
		$duration = $_POST["ttl"];
		$comment = test_input($_POST["comment"]);
		$form_array ="";		# empty array
		$form_array = array("Boxname"=>$boxname,"Destination"=>$destination,"Duration"=>$duration,"Comment"=>$comment);	
		create_mailbox($db_array,$form_array,$path);
	}
	else if(isset($_POST["mailbox"])) { # check for $mailbox
		if ($_POST["mailbox"] == "dorandom") {
			echo "Der Mailboxname ".$_POST['mailbox']." darf nicht verwendet werden.";
		}
		else if ($_POST["mailbox"] == "") {
			echo "Der Mailboxname darf nicht leer sein.";
		}
		else {
			$boxname = test_input($_POST["mailbox"]);
			$destination = $_POST["destination"];
			$duration = $_POST["ttl"];
			$comment = $_POST["comment"];
			$form_array ="";	# empty array
			$form_array = array("Boxname"=>$boxname,"Destination"=>$destination,"ttl"=>$duration,"Comment"=>$comment);
			create_mailbox($db_array,$form_array,$path);
		}
	}
	else {
		echo "Fehler in POST";
		echo "<pre>"; 
		print_r($_POST); 
		echo "</pre>";
	}
}
if (isset($_POST["delete"])){ # check for delete mark
	$boxname = $_POST["deleteboxname"];
	delete_mailbox($db_array,$boxname,$path);
}
else {	# show form to create mailbox
	$form_array_specific ="";
	$form_array_random ="";
	?>	
	<div>
		<form action="<?php htmlentities($_SERVER['PHP_SELF']) ?>" method="POST">
			<table>
				<tr>
					<th colspan="2"><?php echo $text_set_name; ?></th>
				</tr>
				<tr>
					<td class="form_left">
						<a href="#" class="tooltip"><?php echo $text_mailbox; ?>
							<span><img class="callout" src="callout.png" /><?php echo $text_tooltip_mailbox1.": ".$max_mailboxname_length." ".$text_tooltip_chars."<br /><br />".$text_tooltip_mailbox2.": a-z, A-Z, 0-9 ".$text_tooltip_and." .-_+<br /><br />".$text_tooltip_mailbox3."<br /><br />".$text_tooltip_mailbox4."<br /><br />email-default@".$GLOBALS['domain']." --> <br /><br />email-maxmustermann@".$GLOBALS['domain']."  &<br />email-jondoe@".$GLOBALS['domain'];?></span>
						</a>
					</td>
					<td class="form_right">
						<input type="text" maxlength="20" id="mailbox" name="mailbox" style="width: 60%" class="noBorder">@<?php echo $domain; ?>
					</td>
				</tr>
				<tr>
					<td colspan ="2" align="center">- <?php echo $text_or_in_caps; ?> -</td>
				</tr>
				<tr>
					<td class="form_left">
						<a href="#" class="tooltip"><?php echo $text_random_address; ?>
							<span><img class="callout" src="callout.png" /><?php echo $text_tooltip_random1.":<br /><br /> 'trash-".$text_tooltip_random2."@".$GLOBALS['domain']."'<br /><br />".$text_tooltip_random3;?></span>
						</a>
					</td>
					<td class="form_right">
						<input type="checkbox" name="random" value="dorandom"> <?php echo $text_replace_prior_field; ?>
					</td>
				</tr>
				<tr>
					<th colspan ="2" align="center"><?php echo $text_parameters; ?></th>
				</tr>
				<tr>
					<td class="form_left">
						<a href="#" class="tooltip"><?php echo $text_recipient; ?>
							<span><img class="callout" src="callout.png" /><?php echo $text_tooltip_recipient;?></span>
						</a>
					</td>
					<td class="form_right">
						<select name="destination" style="width: 300px"  class="noBorder">
						<option value="<?php echo $address1; ?>"><?php echo $name1; ?></option>
						<option value="<?php echo $address2; ?>"><?php echo $name2; ?></option>
						<!-- you can add more addresses here -->
					</td>
				</tr>
				<tr>
					<td class="form_left">
						<a href="#" class="tooltip"><?php echo $text_time_to_live; ?>
							<span><img class="callout" src="callout.png" /><?php echo $text_tooltip_ttl;?></span>
						</a>
					</td>
					<td class="form_right">
						<select name="ttl" style="width: 300px" class="noBorder">
							<option value="1"><?php echo $GLOBALS["text_one_hour"]; ?></option>
							<option value="2"><?php echo $GLOBALS["text_two_hours"]; ?></option>
							<option value="12"><?php echo $GLOBALS["text_twelve_hours"]; ?></option>
							<option value="24"><?php echo $GLOBALS["text_one_day"]; ?></option>
							<option value="48"><?php echo $GLOBALS["text_two_days"]; ?></option>
							<option value="72"><?php echo $GLOBALS["text_three_days"]; ?></option>
							<option value="168"><?php echo $GLOBALS["text_one_week"]; ?></option>
							<option value="0"><?php echo $GLOBALS["text_no_limit"]; ?></option>
						</select> 
					</td>
				</tr>
				<tr>
					<td class="form_left">
						<a href="#" class="tooltip"><?php echo $text_comment; ?>
							<span><img class="callout" src="callout.png" /><?php echo $text_tooltip_comment;?></span>
						</a>
					</td>
					<td class="form_right"><input type="text" maxlength="99" id="comment" name="comment" style="width: 100%" class="noBorder"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="reset" onClick="reset" value="<?php echo $GLOBALS["text_reset_button"]; ?>"><input type="submit" value="<?php echo $GLOBALS["text_submit_button"]; ?>">
					</td>
				</tr>
			</table>
			<p><h6><?php echo "* ".$text_tooltip; ?></h6>
		</form>
	</div>
<?php } ?>
<div>
	<?php list_mailbox($db_array) ?>
</div>
<div class="licence">
	<a href="http://uberspace.de">Uberspace</a> mail-console von <a href="http://loh.re">Gabriel Lohre</a><br /><a class="credittext"><a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0">Licensed under GNU GPL v2.0</a>
</div>
</body>
</html>
