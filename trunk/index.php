<?php
// LOAD CONFIG FILE
if (!file_exists('local_configuration.php')) die ('Create a "local_configuration.php" file');
require 'local_configuration.php';

// CREATE A PAGE
$urlAnalyser = new Url();
echo $urlAnalyser->generatePage();

?>