<?php 

if (0 > version_compare(PHP_VERSION, '5')) {
	die('This file was generated for PHP 5');
}

if (!class_exists('PDO')) {
	die('Add PDO driver (see PHP.ini)');
}

/**
 * include connectionengine_Result_STH
 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
 */
require_once ('PdoMYSQLResult_STH.class.php');

/* user defined includes */
require_once ('Connect_STH.class.php');
require_once ('ConnectionengineException.class.php');

/* user defined constants */


/**
 * @abstract Connection class to a MYSQL databases with PDO.
 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
 * @version 1.0
 * @date 01-12-2009
 */
class PdoMYSQLConnect_STH extends Connect_STH {
	
	private $_fetchStyle = PDO::FETCH_BOTH;
	private $_user = '';
	private $_pwd = '';
	
	public function PdoMYSQLConnect_STH ($dsnMysql = '', $user, $password, $debugMode = false) { 
		//$dsnRegularExp = '/mysql:dbname=/'; // 'mysql:' && 'dbname='		
		//if ( !preg_match($dsnRegularExp,$dsnMysql) ) throw new ConnectionengineException('Invalid mysql DSN: '. $dsnMysql);
		
		$this->connectionType = 'PDO_MYSQL';
		$this->dsn = $dsnMysql;
		$this->debug = $debugMode;
		
		$this->_user = $user;
		$this->_pwd = $password;
		
		try {
			
			// Connect Object
			$this->connection = new PDO ( $dsnMysql, $user, $password );
			
			// Valid Connect object
			if ( isset ($this->connection) ) $this->connected = true;
		
		} 
		catch ( PDOException $excPDO ) {
			throw new ConnectionengineException($excPDO->getMessage());
		}
	}
	
	public function __destruct(){
		$this->connection = NULL;
	}
	
	public function getFetchStyle () { return $this->_fetchStyle; }
	
	public function getLastErrorMessage(){
		throw new ConnectionengineException('Not implemented');
	}
	

	public function quote($value) { return $this->connection->quote($value); }
	
	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Connect_STH#execute($query)
	 */
	public function &execute( $query = 0 ) {
		$returnValue = -1;
		
		global  $LOGGEROBJ;
		if (isset($LOGGEROBJ) ) $LOGGEROBJ->logFile(__FILE__, __CLASS__, __FUNCTION__ .':'. $query , 4);
		
		try {

			// Connection valid
			if (!$this->connected) { $this->connection = new PDO ( $this->dsn, $this->user, $this->password ); if ( isset ($this->connection) ) $this->connected = true;}
				
			if (($returnValue = $this->connection->exec($query)) === FALSE )	{
				throw new ConnectionengineException('Error with '.$query);
			}
		}
		catch ( PDOException $excPDO ) {
			throw new ConnectionengineException($excPDO->getMessage());
		}
		
		return $returnValue;
	}

	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Connect_STH#query($query, $cached)
	 */
	public function query( $query = 0, $cached = false ) {
		$returnValue = -1;
		
		try {

			// Connection valid
			if (!$this->connected) { $this->connection = new PDO ( $this->dsn, $this->user, $this->password ); if ( isset ($this->connection) ) $this->connected = true;}
				
			if (($sth = $this->connection->query($query)) === FALSE )	{
				throw new ConnectionengineException('Error with '.$query);
			}
			
			$returnValue = new PdoMYSQLResult_STH($sth, $this->_fetchStyle, $query);
		}
		catch ( PDOException $excPDO ) {
			throw new ConnectionengineException($excPDO->getMessage());
		}
		
		return $returnValue;
	} 
	
	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Connect_STH#procedure($procedureName, $params)
	 */
	public function &procedure( $procedureName = 0, $params = array() ) {
		throw new ConnectionengineException('Not implemented');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Connect_STH#getMapping($tableName)
	 */
	public function getMapping($tableName) {
		
		$returnValue = array();
		$queryMapping =  'SHOW COLUMNS FROM '.$tableName.';';
		
		/**
		 * EG:
+---------------+-------------+------+-----+---------+----------------+
| Field         | Type        | Null | Key | Default | Extra          |
+---------------+-------------+------+-----+---------+----------------+
| idRole        | int(11)     | NO   | PRI | NULL    | auto_increment |
| Right_idRight | char(1)     | NO   | PRI | NULL    |                |
| nameRole      | varchar(45) | YES  |     | NULL    |                |
+---------------+-------------+------+-----+---------+----------------+
		 * 
		 */
		
		try {
			// Connection valid
			if (!$this->connected) { $this->connection = new PDO ( $this->dsn, $this->user, $this->password ); if ( isset ($this->connection) ) $this->connected = true;} 
			
			// Exec query
			if ( ($sth = $this->connection->query($queryMapping) ) === FALSE ) {
				throw new ConnectionengineException('Error with '.$queryMapping);
			}
			
			$responses =  $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($responses as $row) {
				$returnValue[] = $row['Field'];
			}
			
			unset ($sth);
			unset ($responses);
		
		} 
		catch ( PDOException $excPDO ) {
			throw new ConnectionengineException($excPDO->getMessage());
		}
		
		return $returnValue;
	} 
	
}

?>