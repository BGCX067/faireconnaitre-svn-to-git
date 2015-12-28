<?php

if (0 > version_compare(PHP_VERSION, '5')) {
	die('This file was generated for PHP 5');
}

/**
 * include connectionengine_Result_STH
 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
 */
require_once ('Result_STH.class.php');

/* user defined includes */
require_once ('ConnectionengineException.class.php');

/* user defined constants */


/**
 * @abstract Connection class to a MYSQL databases with PDO.
 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
 * @version 1.0
 * @date 01-12-2009
 */
class PdoMYSQLResult_STH extends Result_STH {
	
	private $_assosType = PDO::FETCH_BOTH;
	private $_sth = null;
	private $_lineIndex = 0;
	
	/**
	 * @abstract Constructor
	 * @param $result Object Result object
	 * @param $typeAssosFields Int 
	 * @param $query
	 * @return void
	 */
	public function PdoMYSQLResult_STH ($result, $typeAssosFields, $query){
		$this->currentQuery = $query;
		$this->_assosType = $typeAssosFields;
		
		// SAVE RESULT 
		if ($result !== FALSE) {
			$this->_sth = $result;
			// CONVERT "$this->_sth" TO TABLE.
			$this->result = $this->_sth->fetchAll($typeAssosFields);
			
			// Number ROWS
			$this->nbRow = sizeof($this->result);
			$this->_lineIndex = 0;
			
			// ROW
			$this->row = $this->result[$this->_lineIndex];
			if ($this->_lineIndex >= $this->nbRow) {$this->EOF = true;} else {$this->EOF = false;}
			//if ($this->row === FALSE || $this->row == null) $this->EOF = true; else $this->EOF = false;
		}
		else {
			$this->nbRow = 0;
		}
	}
	
	public function setNbRow() { throw new ConnectionengineException('Not implemented.'); }
	
	
	public function fetch_row(){
		$returnvalue = false;
		
		if ($this->EOF == true) { $returnvalue = false; } else {
			$returnvalue = $this->row;
			
			//$this->row = $this->result[$this->_lineIndex];
			
		}
		
		return $returnvalue;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Result_STH#moveFrist()
	 */
	public function moveFrist() { 
		if ($this->_lineIndex > -1) { 
			$this->_lineIndex=0;
			$this->row = $this->result[$this->_lineIndex];
			if ($this->_lineIndex >= $this->nbRow) {$this->EOF = true;} else { $this->EOF = false;} 
		}  
	}
	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Result_STH#moveNext()
	 */
	public function moveNext() {
	if ($this->_lineIndex > -1 && $this->EOF == false && $this->_lineIndex+1 <  $this->nbRow) {
			$this->row = $this->result[++$this->_lineIndex];
			$this->EOF = false;
		} else {
			$this->row = false;
			$this->EOF = true;
		}
	}
	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Result_STH#fetch_array()
	 */
	public function fetch_array() {
		$returnvalue = false;
		
		if ($this->EOF == true) { $returnvalue = false; } else {
			$returnvalue = $this->row;
			
			// VALID IF END
			if ($this->_lineIndex+1 <  $this->nbRow) {
				$this->row = $this->result[++$this->_lineIndex];
				$this->EOF = false;
			} else {
				$this->row = false;
				$this->EOF = true;
			}
			
			
			/**
			if (! ($this->_lineIndex+1 >= $this->nbRow) ) {
				// INCREMENT CURRENT ROW LINE
				$this->row = $this->result[++$this->_lineIndex];
				if ($this->_lineIndex >= $this->nbRow) {$this->EOF = true;} else { $this->EOF = false; }
			} else {
				 
			} 
			**/
			
		}
		
		return $returnvalue;
	}
	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Result_STH#fetch_array_name()
	 */
	public function fetch_array_name() { return $this->fetch_array(); }
	
	public function fetch_object() { throw new ConnectionengineException('Not implemented.'); } 
	public function num_fields()  { throw new ConnectionengineException('Not implemented.'); }
	public function fields($colName) { throw new ConnectionengineException('Not implemented.'); }
	public function field_name($index) { throw new ConnectionengineException('Not implemented.'); } 
	
	/**
	 * (non-PHPdoc)
	 * @see classes/connectionengine/Result_STH#debugTable()
	 */
	public function debugTable(){
		echo '<div><h4>DebugTable</h4>';
		
		$nbcols = $this->_sth->columnCount();
		echo 'NB COLS:' . $nbcols.'; NB LINES:'.$this->nbRow;
		
		print '<table><tr>';
		for ($index=0; $index < $nbcols; $index++ ) {
				echo '<td> COL '.$index.'</td>';
			}
		print '</tr>';

		foreach ($this->result as $row) {
			echo '<tr>';
			for ($index = 0; $index<$nbcols; $index++ ) {
				echo '<td>'.$row[$index].'</td>';
			}
			print '</tr>';	
		}

		print '</table>';
		echo '</div>';
	}
	
} // END CLASS

?>