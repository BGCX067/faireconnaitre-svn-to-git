<?php
/*
 TEST de commantaires
 */

// INCLUDES
define ('GLOBAL_DIR_ROOT', dirname( __FILE__) ); // Sauvegarde de la racine du site web
include 'conf/conf-includes.inc';

// DEVELOPER OPTIONS
$LOGGER = TRUE;
$DEBUG = TRUE;

if (isset($DEBUG) && $DEBUG ) {
	error_reporting(E_ALL);
}

if ((class_exists('Logger')) && $LOGGER) {
	$LOGGEROBJ = new Logger();
}

// PRODUCTION OPTIONS

//STANPORTAL
$THEME_NAME = 'default';
$EMAIL_ADMIN = 'pierre.plessis@yrnet.com';
$VERSION = '1.0a';
$LOGINEXPIRE = (3600 * 24) +1;

// WEBSERVICE
$wsdlUrl = 'http://localhost:8880/stanauthentication.wsdl';
$options	= array('compression'=>true,'exceptions'=>true,'trace'=>$DEBUG);


?>