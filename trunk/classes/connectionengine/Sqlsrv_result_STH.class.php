<?php



/**
 * connectionengine - connectionengine\class.Sqlsrv_result_STH.php
 *
 * This file is part of connectionengine.
 *
 * Automatically generated on 28.07.2009, 15:56:20 with ArgoUML PHP module
 * (last revised $Date: 2009/09/11 15:28:32 $)
 *
 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
 * @package connectionengine
 */

if (0 > version_compare(PHP_VERSION, '5')) {
	die(__FILE__ . ': This file was generated for PHP 5');
}

/**
 * include connectionengine_Result_STH
 *
 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
 */
require_once('Result_STH.class.php');

/* user defined includes */
require_once('ConnectionengineException.class.php');
// section 10-7-80--63--4fdaa9bc:122c129f9d7:-8000:0000000000000CFE-includes end

/* user defined constants */
// section 10-7-80--63--4fdaa9bc:122c129f9d7:-8000:0000000000000CFE-constants begin
// section 10-7-80--63--4fdaa9bc:122c129f9d7:-8000:0000000000000CFE-constants end

/**
 * Short description of class connectionengine_Sqlsrv_result_STH
 *
 * @access public
 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
 * @package connectionengine
 */
class Sqlsrv_result_STH extends Result_STH {
	// --- ASSOCIATIONS ---

	// --- ATTRIBUTES ---
	private $typeAssosFields = SQLSRV_FETCH_ASSOC;
	// --- OPERATIONS ---

	/**
	 * Short description of method Result_STH
	 *
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @param $result MSSQLResult
	 * @param $typeAssosFields
	 * @return void
	 */
	public function Sqlsrv_result_STH($result, $typeAssosFields, $query, $nbrow=0 ){
		global $LOGGER;
		if (isset ($LOGGER) && $LOGGER == true) $LOGGER->logFile(__FILE__,__CLASS__,'Create', 1);

		// EXCEPTIONS
		if ($result === false) throw new ConnectionengineException('Result is NULL', __FILE__, __LINE__);

		// AFFECTATIONS
		if ($query) $this->currentQuery = $query;
		$this->result = & $result;
		if ($this->typeAssosFields != $typeAssosFields) $this->typeAssosFields = $typeAssosFields;
		$this->setNbRow();
		
		// THREAD RESULT
		$this->row = sqlsrv_fetch_object ( $this->result );
		
		if ($this->row === null) { $this->EOF = true; }
		else { $this->EOF = false; }
			
	}
	
	public function __destruct() {
		global $LOGGER;
		if (isset ($LOGGER) && $LOGGER == true) $LOGGER->logFile(__FILE__,__CLASS__,'Destruct', 1);
		
//		if (isset($this->row) && $this->row != null ) sqlsrv_free_stmt ($this->row);
		if (isset($this->row) && $this->row != null )unset ($this->row);
		if (isset($this->result) && $this->result != null ) sqlsrv_free_stmt ( $this->result );
		
		unset	(	$typeAssosFields );
	}
	
	public function __toString() {
		$format = 'Qurey:%s; Nb rows: %d; EOF: %b';
		
		return sprintf($format, $this->currentQuery, $this->nbRow, $this->EOF );
	}

	/**
	 * (non-PHPdoc)
	 * @see STH_connect/classes/connectionengine/Result_STH#fetchRow()
	 */
	public  function fetch_array() {
		$valReturn =  array_values ((array) $this->row);
		$this->row = sqlsrv_fetch_object( $this->result );

		if ($this->row === false) { $this->EOF = true; }
		else { $this->EOF = false; }
		
		return $valReturn;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Result_STH#fetch_array_name()
	 */
	public function fetch_array_name(){
		$valReturn =  (array) $this->row;
		$this->row = sqlsrv_fetch_object( $this->result );

		if ($this->row === false) { $this->EOF = true; }
		else { $this->EOF = false; }
		
		return $valReturn;
	}	
	
	
	public function fetch_row() {
		return $this->fetch_array();
	}
		
	/**
	 * (non-PHPdoc)
	 * @see STH_connect/classes/connectionengine/Result_STH#fetchRow()
	 */
	public  function fetch_object() {
		$valReturn = $this->row;
		$this->row = sqlsrv_fetch_object( $this->result );
		
		if ( $this->row === null) { $this->EOF = true; }
		else { $this->EOF = false; }
		
		return $valReturn;
	}
	

	
	
	
	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Result_STH#setNbRow()
	 */
	protected function setNbRow(){
		$this->nbRow = sqlsrv_rows_affected($this->result);
		
		if( $this->nbRow === false) {
			throw new ConnectionengineException ('Error in calling sqlsrv_rows_affected: '. $this->query, __FILE__, __LINE__,sqlsrv_errors() );	
		}
		
		if ($this->nbRow == -1) {
			$this->nbRow = 0;
		}
		
	}

	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Result_STH#moveFrist()
	 */
	public function moveFrist(){
		throw  new ConnectionengineException('Not implemented !!');
	}

	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Result_STH#moveNext()
	 */
	public function moveNext() {
		$this->row = sqlsrv_fetch_object($this->result);

		if ($this->row === false) { $this->EOF = true; }
		else { $this->EOF = false; }

	}

	/**
	 * (non-PHPdoc)
	 * @see STH_connect/classes/connectionengine/Result_STH#fields($colName)
	 */
	public function fields ($colName){
		if ($this->row === false) return '';
		
		return $this->row->$colName;
	}
	
	
	public function num_fields(){
		return sqlsrv_num_fields($this->result);
	}
	
	public function field_name($index) {
		//$array = array(0 => 100, "color" => "red");
		//print_r(array_keys($array)); -- > NOT WORK WITH (array) $this->row; 
		$valReturn = '';
		$tabTmp = (array) $this->row;
		$indexTmp = 0;
		foreach ($tabTmp as $key => $value ) {
			if ($indexTmp == $index) {
				$valReturn = $key;
				break; }
			$indexTmp ++;
		}
		unset ($tabTmp);
		unset ($indexTmp);
		
		return $valReturn;
	}
	
	public function debugTable () {
		/* Retrieve the number of fields. */
		$numFields = sqlsrv_num_fields($this->result);

		echo '<table>';
		echo '<tr><td colspan="'.$numFields.'">'.$this->currentQuery.'</td></tr>';
		/* Iterate through each row of the result set. */
		do
		{
			echo '<tr>';
			/* Iterate through the fields of each row. */
			for($i = 0; $i < $numFields; $i++)
			{
				echo '<td>' . sqlsrv_get_field($this->result, $i, SQLSRV_PHPTYPE_STRING(SQLSRV_ENC_CHAR)) . '</td>';
			}
			echo '</tr>';
			echo "\n";
			
		}while ( sqlsrv_fetch( $this->result ));
		echo '</table>';
		
		// Avoir un objet connexion et  reeécuter la requete
		
	}
	
} /* end of class connectionengine_Sqlsrv_result_STH */

?>