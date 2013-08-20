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
<script>
$.restoreAll = function() {
	var actions = $('a:contains("Restore")'),
	doAction = function() {
		var me = remaining.pop();
		$.get(me.attr('href'), function() {
			doAction();
		});
	},
	remaining = [];

	actions.each(function() {
		remaining.push($(this));
	});

	doAction();
};
</script>
</head>

<body>
	<div id="wrapper">
    	<!-- h1 tag stays for the logo, you can use the a tag for linking the index page -->
    	<h1><a href="index.php"><span>Stamba Backup & Restore</span></a></h1>
        
        <!-- You can name the links with lowercase, they will be transformed to uppercase by CSS, we prefered to name them with uppercase to have the same effect with disabled stylesheet -->
        <ul id="mainNav">
        	<li><a href="manage.php" class="active">DASHBOARD</a></li> <!-- Use the "active" class for the active menu item  -->
        	<li><a href="backup.php">BACKUP</a></li>
        	<li><a href="restore.php">RESTORE</a></li>
        	<li class="logout"><a href="?logout=1">LOGOUT</a></li>
        </ul>
        <!-- // #end mainNav -->
        
        <div id="containerHolder">
			<div id="container">
                
                <!-- h2 stays for breadcrumbs -->
                <h2><a href="#" class="active">Dashboard</a></h2>
                
                <div id="main">
                	<form action="" class="jNice">
					<h3>Available Backups <a href="#" onclick="$.restoreAll();return false;">Restore All</a></h3>
                    	<table cellpadding="0" cellspacing="0">
<?php
// List the files
$dir = opendir ("./backup"); 
while (false !== ($file = readdir($dir))) { 

	// Print the filenames that have .sql extension
	if (strpos($file,'.sql',1)) { 

	// Get time and date from filename
	$date = substr($file, 9, 10);
	$time = substr($file, 20, 8);

	// Remove the sql extension part in the filename
	$filenameboth = str_replace('.sql', '', $file);
                        
	// Print the cells
		print("<tr>\n");
		print("  <td>" . $filenameboth . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $date . " - " . $time . "</td>\n");
		print("  <td class='action'><a href='restore.php?id=" . $filenameboth . "' class='edit'>Restore</a>\n");
		print("<a href='backup/" . $filenameboth . ".sql' class='view'>Download SQL</a>\n");
		print("<a href='backup/" . $filenameboth . ".zip' class='view'>Download ZIP</a>\n");
		print("<a href='delete.php?file=" . $filenameboth . "' class='delete'>Delete</a></td>\n");
		print("</tr>\n");
	} 
} 
?>

				</table>
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
