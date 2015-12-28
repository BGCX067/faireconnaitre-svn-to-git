<?php
/**
 * ConnectionengineException - ConnectionengineException.class.php
 *
 * This file is part of connectionengine.
 *
 * Automatically generated on 28.07.2009, 16:13:21 with ArgoUML PHP module
 * (last revised $Date: 2009/09/09 15:44:47 $)
 *
 * @author Pierre PLESSIS
 */


if (0 > version_compare(PHP_VERSION, '5')) {
	die(__FILE__ . ': This file was generated for PHP 5');
}


/**
 * include ConnectionengineException
 *
 * @author Pierre PLESSIS, <author@example.org>
 */

/* user defined includes */

/* user defined constants */

/**
 * Short description of class Database
 *
 * @access public
 * @author Pierre PLESSIS, <author@example.org>
 */
class ConnectionengineException extends Exception {
	// --- ATTRIBUTES ---

	// --- OPERATIONS ---
	/**
	 * @abstract Constructor
	 * @param String $message Exception message
	 * @return void
	 */
	public function ConnectionengineException ($message) {
		parent::__construct($message, 8000);
		$this->AddLog();
	}

	/**
	 * @abstract Add a new message on logger
	 * @return void
	 */
	private function AddLog () {
		global $LOGGER, $LOGGEROBJ;
		if (isset($LOGGER) && $LOGGER && is_object($LOGGEROBJ)) $LOGGEROBJ->logFile($this->getFile(),$this->getLine(), $this->getMessage(), 2);
		else {
			$logger = new Logger();
			$logger->logFile($this->getFile(),$this->getLine(), $this->getMessage(), 2);
			unset ($logger);
		}
	}

} /* end of class ConnectionengineException */

?>