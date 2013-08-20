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
        	<li><a href="backup.php" class="active">BACKUP</a></li>
        	<li><a href="restore.php">RESTORE</a></li>
        	<li class="logout"><a href="?logout=1">LOGOUT</a></li>
        </ul>
        <!-- // #end mainNav -->
        
        <div id="containerHolder">
			<div id="container">
                
                <!-- h2 stays for breadcrumbs -->
                <h2><a href="#" class="active">Create a Backup</a></h2>
                
                <div id="main">
                	<form action="" class="jNice">
					<h3>Backup Log</h3>
                    	<table cellpadding="0" cellspacing="0"><td>
<?php
error_reporting(E_ERROR);
set_time_limit(0);
ini_set('memory_limit', '1200M');
ini_set( 'display_errors','1'); 
// Include settings
include("config.php");
require_once('pclzip.lib.php');
// Set the suffix of the backup filename
if ($table == '*') {
	$extname = 'all';
}else{
	$extname = str_replace(",", "_", $table);
	$extname = str_replace(" ", "_", $extname);
}

// Generate the filename for the backup file
$filess = 'backup/dbbackup_' . date("d.m.Y_H:i:s") . '_' . $extname;

// Call the backup function for all tables in a DB
backup_tables($DBhost,$DBuser,$DBpass,$DBName,$table,$extname,$filess);

// Backup the table and save it to a sql file
	function backup_tables($host,$user,$pass,$name,$tables,$bckextname,$filess)
{
		$link = mysql_connect($host,$user,$pass);
		mysql_select_db($name,$link);

		// Get all of the tables
		if($tables == '*') {
			$tables = array();
			$result = mysql_query('SHOW TABLES');
			while($row = mysql_fetch_row($result)) {
				$tables[] = $row[0];
			}
		} else {
			if (is_array($tables)) {
				$tables = explode(',', $tables);
			}
	}

		// Cycle through each provided table
		foreach($tables as $table) {
			$return = "";
			$result = mysql_query('SELECT * FROM '.$table);
			$num_fields = mysql_num_fields($result);
		
			// First part of the output - remove the table
			$return .= 'DROP TABLE ' . $table . ';<|||||||>';

			// Second part of the output - create table
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$return .= "\n\n" . $row2[1] . ";<|||||||>\n\n";

			// Third part of the output - insert values into new table
			for ($i = 0; $i < $num_fields; $i++) {
				while($row = mysql_fetch_row($result)) {
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++) {
						$row[$j] = addslashes($row[$j]);
						$row[$j] = ereg_replace("\n","\\n",$row[$j]);
						if (isset($row[$j])) { 
$return .= '"' . $row[$j] . '"'; 
} else { 
$return .= '""'; 
}
						if ($j<($num_fields-1)) { 
$return.= ','; 
}
					}
					$return.= ");<|||||||>\n";
				}
			}
			
			// Save the sql file
			$handle = fopen($filess.'.'.$table.'.sql','w+');
			fwrite($handle,$return);
			fclose($handle);
			echo $filess.'.'.$table.'.sql<br />';
			
			$archive = new PclZip($filess.'.'.$table.'.zip');
			$v_dir = dirname(getcwd()); // or dirname(__FILE__);
			$v_remove = $v_dir;
			//$v_list = $archive->create($v_dir, PCLZIP_OPT_REMOVE_PATH, $v_remove);
			//if ($v_list == 0) {
			//	echo "Error : ".$archive->errorInfo(true);
				//	die("Error : ".$archive->errorInfo(true));
			//} else {

			      	// Print the message
				print('The backup has been created successfully. <br />You can get <b>MySQL dump file</b> <a href="' . $filess . '.'.$table. '.sql" class="view">here</a>.<br>' . "\n");
				print('You can get <b>Backed-up files archive</b> <a href="' . $filess . '.'.$table. '.zip" class="view">here</a>.<br>' . "\n");
			//}
		}

		

	// Close MySQL Connection
	mysql_close();
} 
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
