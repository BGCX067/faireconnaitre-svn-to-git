<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Log Reader</title>
<style type="text/css">
	body {
	font-family: sans-serif;
	}
	h2 {
	font-family: sans-serif;	
	}
	#code {
		color: #ff0000;
	}
	#codeLnA {
		background-color: #f1f1f1;
	}
	#codeLnB {
		background-color: #cccccc;
	}
</style>
</head>
<body>
<?php 

/** 
 * include all classes files (*.class.php) in G_DIR_CLASS
 */
function read_logs () {
		$files = glob(dirname(__FILE__) . '/*' . G_EXT_LOGS);
		
		for ($index = 0, $max = count($files);$index < $max; $index++) {
				echo '<a href="?file='.$files[$index].'">'.  $files[$index].'</a><br/>';			
		}	
}

function print_log($file) {
	
	if (isset($file) && file_exists($file)) { 
	$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
	
	foreach ($lines as $line_nb => $line) {
		echo sprintf('<div id="%s"> #<b>%d</b>: %s<br/></div>',($line_nb%2==0?'codeLnA':'codeLnB'),  $line_nb, htmlspecialchars( $line ) );
	}
	
	unset ($lines);
	}
}

require ('../local_configuration.php');

if (isset($_GET['file']) && file_exists($_GET['file']) ) {
	echo '<div style="font-family: monospace; size: 10px;"><h2>Current file</h2>';
	print_log($_GET['file']);
	echo '</div>';
}

echo '<div><h2>File list</h2>';
	read_logs();
echo '</div>';

?>

</body>
</html>