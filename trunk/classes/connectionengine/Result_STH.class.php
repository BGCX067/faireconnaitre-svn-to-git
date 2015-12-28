<?php

//error_reporting(E_ALL);

/**
 * connectionengine - connectionengine\class.Result_STH.php
 *
 * $Id: Result_STH.class.php,v 1.1.2.1 2009/09/04 08:06:45 pplessis Exp $
 *
 * This file is part of connectionengine.
 *
 * Automatically generated on 28.07.2009, 15:56:20 with ArgoUML PHP module
 * (last revised $Date: 2009/09/04 08:06:45 $)
 *
 * @author Pierre PLESSIS, <author@example.org>
 * @package connectionengine
 */

if (0 > version_compare(PHP_VERSION, '5')) {
	die('This file was generated for PHP 5');
}

/**
 * include connectionengine_Connect_STH
 *
 * @author Pierre PLESSIS, <author@example.org>
 */
require_once('Connect_STH.class.php');

/* user defined includes */

/* user defined constants */


/**
 * Short description of class connectionengine_Result_STH
 *
 * @abstract It's a result object form a query.
 * @access public
 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
 * @package connectionengine
 */
abstract class Result_STH
{
	// --- ASSOCIATIONS ---
	
	// --- ATTRIBUTES ---
	/**
	 * 
	 * @var object
	 */
	protected $result = null;


	/**
	 * Short description of attribute EOF
	 *
	 * @access public
	 * @var Boolean
	 */
	public $EOF = true;
	/**
	 *
	 *
	 * @access protected
	 * @var Object
	 * */
	protected $row = false;

	/**
	 * @abstract The query attached to current result.
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @access private
	 * @var String
	 */
	protected $currentQuery = '';

	/**
	 * @abstract Number of rows by SELECT query or number rows affected by UPDATE or INSERT query.
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @access protected
	 * @var Int
	 */
	protected $nbRow = 0;
	

	/**
	 * @abstract active debug mode
	 * @var Boolean
	 */
	public $debug = false;

	// --- OPERATIONS ---
	/**
	 * @abstract MAIN CONSTRUCTOR
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @return void
	 */
	public function Result_STH () { }

	public function __destruct () {
		if ( isset($this->result) && $this->result != null  &&  is_object($this->result) ) $this->result->__destruct();
		else unset ($this->result);
	}

	/**
	 * @abstract Return number of row(s) affected.
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @return int
	 */
	public function getNbRow () { return $this->nbRow;}

	/**
	 * @abstract Get if query have some results
	 * @return Boolean
	 */
	public function haveResult() {
		return ( ($this->row === false || $this->row === null) ?false:true);
	}

	/**
	 * @abstract get a query
	 * @return string
	 */
	public function getCurrentQuery () { return $this->currentQuery; }


	/**
	 * @abstract Set number of row(s) affected.
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @return void
	 */
	protected abstract function setNbRow();

	/**
	 * @abstract Move to frist row
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @return void
	 */
	public abstract function moveFrist();

	/**
	 * @abstract Move to next row
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @return void
	 */
	public abstract function moveNext();


	public abstract function fields ($colName);
	//public abstract function fields (int $colNb);

	/**
	 * @abstract Get current row in a array.
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @return Current row on array format or false
	 */
	public abstract function fetch_array() ;

	/**
	 * @abstract Get current row in a array with cols name on keys.
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @return Current row on array format or false
	 */
	public abstract function fetch_array_name() ;
	
	/**
	 * @abstract Get curent row in a array
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @return unknown_type
	 */
	public abstract function fetch_row();
	
	/**
	 * @abstract Get current row in an object.
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @return Curent row on object or false
	 */
	public abstract function fetch_object();

	/**
	 *
	 * @access public
	 * @author Pierre PLESSIS, <pierre plessis/fr/stanhome/yves-rocher@yrnet>
	 * @return int
	 */
	public abstract function num_fields();

	/**
	 *
	 * @param int $index
	 * @return String
	 */
	public abstract function field_name($index);

	/**
	 * @abstract Create a HTML TABLE
	 * @return unknown_type
	 */
	public abstract function debugTable ();

} /* end of abstract class connectionengine_Result_STH */

?>