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

					
					
					############# Variables ################
					####       only edit below        	####
					########################################

## UBERSPACE SETTINGS ##					

# path to mailbox-files (../ if htdoc = pageroot)
$path = "../";	
# your username at uberspace			
$uberspaceuser="name";
# language
$lang = "de"; # alternatives: de = deutsch,en = english
# your domain
$domain = "domain.com";
# max length for mailbox name, default = 20
$max_mailboxname_length = 20;
# recipient status-messages
$adminmail = "service-trashmail@domain.com";

## USER PARAMETER ##

# first email-address
$address1 = "jon@doe.com";	
# displayname for first email-address	 
$name1 = "Jon Doe";		
# second email-address		
$address2 = "max@mustermann.de";	
# displayname for second email-address	
$name2 = "Max Mustermann";	

# prefix for random-email-addresses		
$defaultprefix="trash";	

## DATABASE PARAMETER ##
		
# database-server ('localhost')
$db_server = "localhost"; 		
# database-name	(begins with your uberspace name, followed by _)
$db_name = "mailDB"; 	
# database-user (your uberspace name)
$db_user = $uberspacename;
# database-user-password (get it from ~/.my.cnf)
$db_pass = ""; 						
?>
