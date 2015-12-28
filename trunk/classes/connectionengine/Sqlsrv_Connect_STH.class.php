<?php

//error_reporting(E_ALL);

/**
 * connectionengine - connectionengine\class.Sqlsrv_connect_STH.php
 *
 * $Id: Sqlsrv_Connect_STH.class.php,v 1.1.2.2 2009/09/09 15:44:47 pplessis Exp $
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
	die(__FILE__ . ': This file was generated for PHP 5');
}

// security - hide paths
//if (!defined('ADODB_DIR')) die();

if (!function_exists('sqlsrv_configure') || !function_exists('sqlsrv_fetch_array')) {
	die("Ms sqlsrv extension not installed");
}

/*
 if (!function_exists('sqlsrv_set_error_handling')) {
 function sqlsrv_set_error_handling($constant) {
 sqlsrv_configure("WarningsReturnAsErrors", $constant);
 }
 }
 if (!function_exists('sqlsrv_log_set_severity')) {
 function sqlsrv_log_set_severity($constant) {
 sqlsrv_configure("LogSeverity", $constant);
 }
 }
 if (!function_exists('sqlsrv_log_set_subsystems')) {
 function sqlsrv_log_set_subsystems($constant) {
 sqlsrv_configure("LogSubsystems", $constant);
 }
 }
 */


/**
 * include connectionengine_Connect_STH
 *
 * @author Pierre PLESSIS, <author@example.org>
 */
require_once('Connect_STH.class.php');

/* user defined includes */
require_once('Sqlsrv_result_STH.class.php');
require_once('Sqlsrv_result_STH_CACHED.class.php');

/* user defined constants */


/**
 * Short description of class connectionengine_Sqlsrv_connect_STH
 *
 * @access public
 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
 * @package connectionengine
 */
class Sqlsrv_Connect_STH extends Connect_STH {
	// --- ASSOCIATIONS ---

	// --- ATTRIBUTES ---

	/**
	 *
	 * @var String
	 */
	private $host = '';
	/**
	 *
	 * @var unknown_type
	 */
	private $connectionInfo = null;

	/**
	 * @uses SQLSRV_FETCH_NUMERIC The next row of data is returned as a numeric array.
	 * @uses SQLSRV_FETCH_ASSOC The next row of data is returned as an associative array. The array keys are the column names in the result set.
	 * @uses SQLSRV_FETCH_BOTH The next row of data is returned as both a numeric array and an associative array. This is the default value.
	 **/
	private $defaultAssocType = SQLSRV_FETCH_ASSOC;

	// --- OPERATIONS ---

	/**
	 * @abstract Constructeur
	 *
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @return void
	 */
	public function Sqlsrv_Connect_STH($host=0, $connectionInfo=null) {


		global $LOGGER;
		if (isset ($LOGGER) && $LOGGER == true) $LOGGER->logFile(__FILE__,__CLASS__, 'Create', 1);

		// Exceptions
		if (!$host) 			throw new ConnectionengineException('$host is null');
		if (!$this->validHost($host)) 	throw new ConnectionengineException('$host is not valid');

		// Save all parameters
		$this->host = $host;
		$this->connectionInfo = $connectionInfo;

		// Init atributes of parent class
		$this->init();
		$this->dsn = 'mssql://'.$connectionInfo['UID'] .':'. $connectionInfo['PWD'].'@'. $host .'/'.$connectionInfo['Database'];

		// Open connection

		 if (false === ($this->connection = sqlsrv_connect($host, $connectionInfo)) ) {
			$this->connected = false;
			throw new ConnectionengineException('Connection (KO) on '.$host, __FILE__, __LINE__-2, sqlsrv_errors());
			} else {
			$this->connected = true;
			}

	} // END FUNCTION

	public function __destruct() {
		global $LOGGER;
		if (isset ($LOGGER) && $LOGGER == true) $LOGGER->logFile(__FILE__.'('.__LINE__.')',__CLASS__, 'Destruct', 1);

		if ($this->connection != null) sqlsrv_close( $this->connection );

		unset ( $this->host );
		unset ( $this->connectionInfo	);
		unset	(	$this->defaultAssocType	);
	}

	/**
	 * @abstract init current object
	 * @return void
	 */
	protected function init () {
		$this->connectionType = 'sqlsrv';



	}


	/**
	 * @abstract Valid hostname
	 * @version 1.0
	 * @param string $hostname
	 */
	private function validHost ($hostname) {
		return true;
	}

	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Connect_STH#getLastErrorMessage()
	 */
	public function getLastErrorMessage () {
		return sqlsrv_errors();
	}


	public function &execute($query = 0) {
		$params = null;
		$stmt = null;
		$objReturned = null;
		//$result = null;

		// Exceptions
		if (!$query) throw new ConnectionengineException ('Error query is null.', __FILE__, __LINE__,sqlsrv_errors() );

		if (!$this->connected) {
			// auto-reconnect
			$this->connection = sqlsrv_connect($this->host, $this->connectionInfo);
			if ($this->connection === false) { throw new ConnectionengineException ('Error on Auto Connection (KO) on'.$this->host, __FILE__, __LINE__, sqlsrv_errors() );}
			$this->connected = true;
			//echo '<h1>CONNECTION!!</h1>';
		}

		/* Create the statement. */
		$stmt = sqlsrv_prepare( $this->connection, $query, $params);
		if( ! $stmt ) {
			throw new ConnectionengineException ('Error in preparing statement.', __FILE__, __LINE__, sqlsrv_errors());
		}

		$objReturned = sqlsrv_execute( $stmt );

		//echo '<h5>'.$query . '->'. $objReturned.'</h5>';

		// ADD LOG WHEN $objReturned  = false
		if ($objReturned  == false) {
			global $LOGGER;
			//if (isset($LOGGER) && $LOGGER == true) {
			$LOGGER->logFile(__FILE__,__LINE__, '(KO) Error on '.$query, 2);
			//}
		}

		return $objReturned;
	}

	public function query ($query = 0, $cached = false) {
		$objReturned = null;
		$result = null;

		// Exceptions
		if (!$query) throw new ConnectionengineException ('Error query is null.', __FILE__, __LINE__,sqlsrv_errors() );

		if (!$this->connected) {
			// auto-reconnect
			$this->connection = sqlsrv_connect($this->host, $this->connectionInfo);
			if ($this->connection === false) { throw new ConnectionengineException ('Error on AutoConnection (KO) on'.$this->host, __FILE__, __LINE__, sqlsrv_errors() );}
			$this->connected = true;
		}

		$result = sqlsrv_query($this->connection, $query);

		// Traitement erreurs
		if( $result === false) {
			//echo "Error in query preparation/execution.\n";
			throw new ConnectionengineException ('Error on query (KO):'. $query, __FILE__, __LINE__,sqlsrv_errors() );
		}

		// Traitement du $result
		//echo 'NB :'. $this->getNbResult($result);

//		if ($cached) {
			// CREATE A NEW TAB WITH ALL RESULTS INSIDE
//			$tabTmp = array();
//			while ($row = sqlsrv_fetch_object ( $result )) {
					
//				$tabTmp[] = $row;
//			}
			
//			$objReturned = new Sqlsrv_result_STH_CACHED (  $tabTmp , $this->defaultAssocType, $query );
//		} else {
			$objReturned = new Sqlsrv_result_STH (  $result , $this->defaultAssocType, $query );
//		}
		// DEBUG MODE ACTIVE		
		$objReturned->debug = $this->debug;

		return $objReturned;
	}


	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Connect_STH#procedure($procedureName, $params)
	 */
	public function &procedure ( $procedureName = 0, $params = array()){
		$objReturned = null;
		$result = null;
			
		if ($procedureName != 0)  throw new ConnectionengineException ('$procedureName is null.', __FILE__, __LINE__,sqlsrv_errors() );
			
		$callString = sprintf('EXEC %s ',$procedureName);
		if (count($params) > 0 ) {
			//$callString .= ' (';

			for ($index=0,$indexMax=count($params); $index<$indexMax; $index++) {
				if ($index != $indexMax-1)
				$callString .= $params[$index][0] . ',';
				else
				$callString .= $params[$index][0];
			} // foreach

			//$callString .= ' )';
		}// if
		//$callString .= ' }';

		/* Create the statement. */
		$stmt = sqlsrv_prepare( $this->connection, $callString, null);
		if( ! $stmt ) {
			throw new ConnectionengineException ('Error in preparing statement.', __FILE__, __LINE__, sqlsrv_errors());
		}
			
		$result = sqlsrv_execute( $stmt );
			
			
		if ($this->debug == true) echo Logger::Log($callString);
			
		// Traitement erreurs
		if( $result === false && $procedureName != 'sp_start_job') {

			print_r(sqlsrv_errors());

			//echo "Error in query preparation/execution.\n";
			throw new ConnectionengineException ('Error on query (KO):'. $callString, __FILE__, __LINE__,sqlsrv_errors());
		} else
		$objReturned = true;
			
		//	$objReturned = new Sqlsrv_result_STH ( & $result , $this->defaultAssocType, $query );
		//	$objReturned->debug = $this->debug;

		return $objReturned;
	}


	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Connect_STH#getNbResult($result)

	 protected function getNbResult ($result) {
		$valReturn = 0;

		$req = 'SELECT @@rowcount';
		$result = sqlsrv_query ($this->connection, $req);


		if( $result === false) {
		echo "Error in query preparation/execution.\n";
		throw new ConnectionengineException ('Error on query: '. $req, __FILE__, __LINE__);
		}

		//echo 'AQSQQQ: ' . sqlsrv_rows_affected($result);


		// TODO SUPPR ... $result
		if( sqlsrv_fetch( $result ) === false)
		{
		echo "Error in retrieving row.\n";
		die( print_r( sqlsrv_errors(), true));
		}

		$valReturn = sqlsrv_get_field( $result, 0);


		return $valReturn;
		}
		**/
	public function executeLimit( $query = 0, $nbLimit = 0) {
		throw new ConnectionengineException ('Not implemented function', __FILE__ , __LINE__ );
	}



} /* end of class connectionengine_Sqlsrv_connect_STH */

?>