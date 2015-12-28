<?php

if (0 > version_compare(PHP_VERSION, '5')) {
	die( __FILE__ . ': This file was generated for PHP 5');
}

/* ****************************
 * MSG LEVEL DESCRIPTION OF EACH LEVEL
 * 1; DEBUG; CONSTRUCTOR OR DESTRUCTOR
 * 2; DEBUG; EXCEPTION
 * 3; DEBUG; MESSAGES
 * 4; MODULE; EXCEPTION
 * 5; MODULE; MESSAGES
 * 6; APP; EXCEPTION
 * 7; APP; MESSAGE
 * ****************************/

/* Created on 28 avr. 2009
 * Last update on 15 may 2009
 * Logger CLASS
 */
define('LOG_LEVEL_PHP5',12); // GLOBAL LOG LEVEL (0=>NONE | 12 =>ALL)
define('LOG_LEVEL_DPRINT_PHP5', 1); // LIMIT MINI TO SEE LOG
define('LOG_LEVEL_SEP_FILE',(defined('CONST_PATH_SEPARATOR') ? CONST_PATH_SEPARATOR : '/'));
define('ADDCALLSTACKLOG', FALSE);
define('LOGTODATABASE', FALSE);
define('LOGDIRECTORY', dirname (__FILE__) . CONST_PATH_SEPARATOR . '..' . CONST_PATH_SEPARATOR . '..' . CONST_PATH_SEPARATOR . 'logs' . CONST_PATH_SEPARATOR );

/**
 * #INCLUDE
 */
if (defined(LOGTODATABASE) && LOGTODATABASE == TRUE)  require_once ( 'databasePDO.class.php');
 
class Logger {
	
	/**
	 * CONST
	 */
	
	const DATEFORMAT = 'd-m-Y H:i:s';
	
	const HTMLMESSAGEFORMAT = '<div style="background-color: #eeffa2;width: 100%%;font-size: 6pt; font-family: Courier, Geneva, sans-serif">%s %s</div>';
	const HTMLERRORFORMAT = '<div style="color:red; background-color: #eeffa2;width: 100%%;font-size: 6pt; font-family: Courier, Geneva, sans-serif">%s %s</div>';
	const FILELINEFORMAT = '%s;%f;%s;%s;%s;%d;%s';
	const QUERYFORMAT = 'INSERT INTO %s (%s) VALUES (%s);';
	
	private static $pool = null;
	
	/**
	 * Constructeur
	 */
	public function Logger() {
		date_default_timezone_set('Europe/Paris');
	}
	
	public function __destruct() { 
		if (Logger::$pool != null) Logger::$pool->__destruct(); 
	}
	
	
	/**
	 * Convert vars to string
	 */
	private static function getString($vars) {
		$message = "*";
		if (is_array($vars)) {
			$message =' #';
			
			foreach ($vars as $key => $value) { 
				$message .=  " , ". $key . " => '" . Logger::getString($vars[$key])."'";
			}
			$message = $message.'#';
		}elseif (is_object($vars)) {
			$message =' !'.get_class($vars);
			if (method_exists($vars,'toString')) { // TOSTRING() METHOD
				@$var_val = $vars->toString();
				if (!empty($var_val))  $message .=' : '. $var_val;
			}
		}elseif (is_bool($vars)) {
			if ($vars)
				$message ='TRUE';
			else
				$message ='FALSE';
		} else {
			//$message= serialize ($vars);
			$message = $vars;
		}
		return $message;
	}
	
	/**
	 * Ecrire des logs dans un fichier
	 * @param int $level niveau de debug pour ces traces
	 * @param string $class nom de la classe appelante
	 * @param string $fonction nom de la fonction appelante
	 * @param string $prefixStr  prefix ou message a tracer avant les parametres
	 * @param string ou Array $params parametres a ecrire
	 */
	 function logToFile ($level, $class, $fonction, $prefixStr, $params='') {
		$result = false;
		
		if (Logger::isDebugEnabled() and $level > LOG_LEVEL_DPRINT_PHP5) {

			try{
			
			$message = ' ' . $prefixStr . ' ' . Logger::getString($params);
			$dir = Logger::getLogDir($level,$class,$fonction);
			$filename = Logger::getLogFileName($level,$class,$fonction);
			$log_file = $dir.LOG_LEVEL_SEP_FILE.$filename;
			
			// Remplace \n \v \f \r by '' and '  ' \t by ' '  
			$cherche1 = array('/[\x0A]/', '/[\x09]/','/[\x0B]/', '/[\x0C]/', '/[\x0D]/', '/[ ]{2,}/');
			$remplace1 = array ('', ' ', '', '', '', ' ' );
			
			//OLD $cherche =  '/\s/';
			//OLD $remplace = '';
			
			if (!file_exists($log_file )) touch($log_file);
							
			$msg = sprintf(Logger::FILELINEFORMAT . "\r\n", date(Logger::DATEFORMAT, time()), (microtime(true) - $_SERVER['REQUEST_TIME']), strlen(session_id())>0?session_id():'none', $class, $fonction, $level, preg_replace($cherche1,$remplace1, $message));
			
			// TODO VALIDER LA PERTE de LIGNE -> SI GRAVE ... (SEE CODE AVEC PERTE)
			/*
			$fp = fopen($log_file, 'a');
			while (! flock($fp, LOCK_EX) ) usleep(50); 
				fwrite($fp, $msg);
				flock($fp, LOCK_UN); // libere le verrou
			fclose($fp);
			*/

			//-- CODE AVEC PERTE
			$fp = fopen($log_file, 'a');
			 
				fwrite($fp, $msg);
				
			fclose($fp);

			$result = true;
			} catch (Exception $e) {$result = false;}
			
		}
	
		return $result;
	}
	
	static function getLogDir ($level, $class, $fonction) {
		$result = '';
		$dir = dirname (__FILE__);
		
		if ( !defined('LOGDIRECTORY') ) {
			$result = $dir . LOG_LEVEL_SEP_FILE . 'log';
		} else  {
			$result = realpath(LOGDIRECTORY);
		}
					
		if (!file_exists($result)) mkdir($result);
		
		return $result;
	}
	
	function getLogFileName($level,$class,$fonction) {
		//$date = date ('d-m-Y');
		$result = date ('d-m-Y') . '-debug.log';
		return $result;
	}
	
	static function isDebugEnabled() {
		if (defined('LOG_LEVEL_PHP5') and LOG_LEVEL_PHP5 > 0) {
			return true;
		} else { 
			return false; 
		}
	}
	
	public static function logToDataBases ($level, $class, $fonction, $prefixStr, $params='') {
		$tableName = 'log';
		$fields = 'date,  ticks ,  className , functionName ,  title,  text, callstack';
		$valuesFormat = '\'%s\', %f, \'%s\', \'%s\', %d, %s, \'%s\' ';
		$query = '';
		
		if (defined(LOGTODATABASE) && LOGTODATABASE == TRUE && Logger::isDebugEnabled()) {
			if (Logger::$pool == null) Logger::$pool = new databasePDO();
			
			$message = ' ' . $prefixStr . ' ' . Logger::getString($params);
			$values = sprintf($valuesFormat, date('Y-m-d H:i:s',time()), (microtime(true) - (float)$_SERVER['REQUEST_TIME']), $class, $fonction, $level, Logger::$pool->ValidSGBDString($message), (ADDCALLSTACKLOG==TRUE?(Logger::getString(apd_callstack() )):''));											
			$query = sprintf(Logger::QUERYFORMAT,	$tableName, $fields, $values);
																					
			Logger::$pool->ExecuteQuery($query);
		}
	}
	
	/**
	 * @abstract Methode pour creation d'un message dans la page.
	 * @param string $message
	 */
	public static function Log($message) {
		$date = date(Logger :: DATEFORMAT);
		if (Logger::isDebugEnabled() )
			return  sprintf(Logger :: HTMLMESSAGEFORMAT, $date,  $message);		
	}
	/**
	 * @abstract Methode pour un message d'erreur
	 * @param string $message
	 */
	public static function Error($message) {
		$date = date(Logger :: DATEFORMAT );
		if ( Logger::isDebugEnabled() )
			return  sprintf(Logger :: HTMLERRORFORMAT, $date,  $message);		
	}
	
	/**
	 * @abstract Method get PHP version
	 */
	public static function getPhpVersion () {
		$raw = phpversion();
 		list($v_Upper,$v_Major,$v_Minor) = explode(".",$raw);
		return $v_Upper;
	}
	
	public static function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());		
	    return ((float)$usec + (float)$sec);
	}
	
	/**
	 * @abstract Log a message on client webpage
	 * @param string $message
	 */
	public function logToPage ($message){
		echo Logger::Log($message);
	}
	/**
	 * @param string $filename
	 * @param string $className
	 * @param mix $message
	 * @param int $level (default =4)
	 */
	public function logFile ($filename,$className,$message,$level=4) {
		Logger::logToFile($level, $filename,$className, 'Manual log;', $message);
	}
	
	
}// END CLASS

?>
