<?php

/**
 * connectionengine - connectionengine\class.Connect_STH.php
 *
 * $Id: Connect_STH.class.php,v 1.1.2.2 2009/09/09 15:44:47 pplessis Exp $
 *
 * This file is part of connectionengine.
 *
 * Automatically generated on 28.07.2009, 15:56:20 with ArgoUML PHP module
 * (last revised $Date: 2009/09/09 15:44:47 $)
 *
 * @author Pierre PLESSIS, <author@example.org>
 * @package connectionengine
 */

if (0 > version_compare(PHP_VERSION, '5')) {
	die('This file was generated for PHP 5');
}

/**
 * include connectionengine_Result_STH
 *
 * @author Pierre PLESSIS, <author@example.org>
 */
require_once ('Result_STH.class.php');

/* user defined includes */
require_once ('ConnectionengineException.class.php');

/* user defined constants */


/**
 * Short description of class connectionengine_Connect_STH
 *
 * @abstract
 * @access public
 * @author Pierre PLESSIS, <author@example.org>
 * @package connectionengine
 */
abstract class Connect_STH {
	// --- ATTRIBUTES ---
	/**
	 * @abstract curent connection DSN 
	 * @var String
	 */
	protected $dsn = null;

	/**
	 * @abstract driver type
	 * @var String
	 */
	protected $connectionType = 'none';

	/**
	 * @abstract Get if the curent object is connected
	 * @access public
	 * @var Boolean
	 */
	protected  $connected = false;

	/**
	 * @abstract Is the connection object
	 * @var unknown_type
	 */
	protected $connection = null;

	/**
	 * @abstract Define is the class is on DEBUG mode.
	 * @var Boolean
	 */
	protected $debug = false;

	// --- OPERATIONS ---
	/**
	 * @abstract Constructor
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @return void
	 */
	public  function Connect_STH() {
		global $LOGGER;
		if (isset ($LOGGER) && is_object ($LOGGER)) $LOGGER->logFile(__FILE__,__CLASS__,'Create', 1);

	}
	
	public function __destruct() {
		unset	(	$this->dsn	);
		unset	(	$this->connectionType	);
		unset	(	$this->connected	);
		unset	(	$this->debug	);	
	}


	/**
	 * @abstract return dns value
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @return String
	 */
	public function getDsn() {
		return $this->dsn;
	}

	public function getConnection () {
		return $this->connection;
	}
	
	public function getConnexionType() {
		return $this->connectionType;
	}
	
	/**
	 * @abstract Get last error Message
	 * @return Array
	 */
	public abstract function getLastErrorMessage ();
	
	/**
	 * @abstract 
	 * @param $value
	 * @return string
	 */
	public abstract function quote($value);
	
	/**
	 * @abstract Execute a UPDATE or INSERT query
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @param  String query
	 * @return Boolean
	 */
	public abstract function &execute( $query = 0 );

	/**
	 * @abstract Execute a query with a
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @param $query
	 * @return Result_STH object
	 */
	public abstract function  query ( $query = 0, $cached = false );
	
	/**
	 * @abstract Execute a store procedure
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @param $procedureName 
	 * @param $params
	 * @return Result_STH object
	 */
	public abstract function &procedure ( $procedureName = 0, $params = array() );

	/**
	 * @abstract Get table mapping
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @param $tableName
	 * @return Array
	 */
	public abstract function getMapping ( $tableName );

} /* end of abstract class _connectionengine_Connect_STH */

?>