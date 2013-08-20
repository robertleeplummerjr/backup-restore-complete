<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Stamba Backup & Restore</title>

<!-- CSS -->
<link href="style/css/transdmin.css" rel="stylesheet" type="text/css" media="screen" />
<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie6.css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie7.css" /><![endif]-->

<!-- JavaScripts-->
<script type="text/javascript" src="style/js/jquery.js"></script>
<script type="text/javascript" src="style/js/jNice.js"></script>
</head>

<body>
	<div id="wrapper">
    	<!-- h1 tag stays for the logo, you can use the a tag for linking the index page -->
    	<h1><a href="index.php"><span>Stamba Backup & Restore</span></a></h1>
        
        <!-- You can name the links with lowercase, they will be transformed to uppercase by CSS, we prefered to name them with uppercase to have the same effect with disabled stylesheet -->
        <ul id="mainNav">
        	<li><a href="manage.php">DASHBOARD</a></li> <!-- Use the "active" class for the active menu item  -->
        	<li><a href="backup.php">BACKUP</a></li>
        	<li><a href="restore.php" class="active">RESTORE</a></li>
        	<li class="logout"><a href="?logout=1">LOGOUT</a></li>
        </ul>
        <!-- // #end mainNav -->
        
        <div id="containerHolder">
			<div id="container">
                
                <!-- h2 stays for breadcrumbs -->
                <h2><a href="#" class="active">Restore a Backup</a></h2>
                
                <div id="main">
                	<form action="" class="jNice">
					<h3>Restore Log</h3>
                    	<table cellpadding="0" cellspacing="0"><td>
<?php
error_reporting(E_ERROR);
set_time_limit(0);
ini_set('memory_limit', '1200M');
ini_set( 'display_errors','1'); 
// Get the provided arg
$id=$_GET['id'];

// Check if the file has needed args
if ($id==NULL){
  print("<script type='text/javascript'>window.alert('You have not provided a backup to restore.')</script>");
  print("<script type='text/javascript'>window.location='manage.php'</script>");
  print("You have not provided a backup to restore.<br>Click <a href='manage.php'>here</a> if your browser doesn't automatically redirect you.");
  die();
}

// Include settings
include("config.php");

// Generate filename and set error variables
$filename = 'backup/' . $id . '.sql';
$sqlErrorText = '';
$sqlErrorCode = 0;
$sqlStmt      = '';

// Restore the backup
$con = mysql_connect($DBhost,$DBuser,$DBpass);
if ($con !== false){
  // Load and explode the sql file
  mysql_select_db("$DBName");
  $f = fopen($filename,"r+");
  $sqlFile = fread($f,filesize($filename));
  $sqlArray = explode(';<|||||||>',$sqlFile);
          
  // Process the sql file by statements
  foreach ($sqlArray as $stmt) {
    if (strlen($stmt)>3){
         $result = mysql_query($stmt);
    }
  }
}

// Print message (error or success)
if ($sqlErrorCode == 0){
   print("Database restored successfully!<br>\n");
   print("Backup used: " . $filename . "<br>\n");
} else {
   print("An error occurred while restoring backup!<br><br>\n");
   print("Error code: $sqlErrorCode<br>\n");
   print("Error text: $sqlErrorText<br>\n");
   print("Statement:<br/> $sqlStmt<br>");
}

// Close the connection
mysql_close();

// Change the filename from sql to zip
//$filename = str_replace('.sql', '.zip', $filename);
?> 

				</td></table>
				<br />
                    </form>
                </div>
                <!-- // #main -->
                
                <div class="clear"></div>
            </div>
            <!-- // #container -->
        </div>	
        <!-- // #containerHolder -->
        
        <p id="footer">Feel free to use and customize it, as you feel like. Credit & backlink is much appreciated but not obligatory! If you are using it for commercial purposes I kindly ask you to give some credit, but still it's your free will. <a href="http://campstamba.com">http://campstamba.com</a></p>
    </div>
    <!-- // #wrapper -->
</body>
</html>
