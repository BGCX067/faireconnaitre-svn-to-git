<?php
// INCLUDES

/**
 *
 * @author PLESSSIS PIERRE
 * @abstract Add a logger for add a time on each page.
 */
class LoggerTime {

	const tableName	=  'mos_time'; // TABLE NAME use with constant ('LoggerTime::tableName')
	private $database = null;

	public function LoggerTime($database) { $this->database = & $database; }
	public function __destruct() {}
	public function __toString() { return __CLASS__; }

	/**
	 * Add a line in MOS_TIME TABLE
	 * @param $action String Action NAME
	 * @param $add Boolean if (true) INSERT INTO all time
	 * @return None
	 */
	public function AddSample ($action = '', $add = false) {

		global $cryptSessionCookie;
		$selectQuery = 'SELECT TOP 1 * FROM %s %s;';
		$updateQuery = 'UPDATE %s SET %s;';
		$insertQuery = 'INSERT INTO %s (%s) VALUES (%s);';

		// COLS NAME
		$colsName[0] = 'id_session';
		$colsName[1] = 'action';
		$colsName[2] = 'count';
		$colsName[3] = 'time';
		$colsName[4] = 'date';
		
		$chargingTime = microtime(true) - (float)$_SERVER['REQUEST_TIME'];
		
		if ($action == '') {
			$action = 'none';
			if (isset ($_POST['option']))  $action = $_POST['option'];
			if (isset($_GET['option'])) $action = $_GET['option'];
			
			// orderAction
			if (isset($_POST['orderAction'])) $action .= ';'.$_POST['orderAction'];
			if (isset($_GET['orderAction'])) $action .= ';'.$_GET['orderAction'];
		}

		$idsession = _USERTYPE_ANONYMOUS;
		if ( isset($cryptSessionCookie) ) $idsession = $cryptSessionCookie;

		try{
			if ($this->database == null) throw new Exception();
				
			// DEFINE WHERE CONDITION
			// BUG -- 26/11/2009 -- PPLESSIS -- IF session is not init 
			$sqlWhere = sprintf(' WHERE %s LIKE \'%%%s%%\' AND  %s = \'%s\' AND CONVERT(VARCHAR, %s,103) = CONVERT (VARCHAR,GETDATE(),103)',$colsName[0], $idsession,$colsName[1],$action, $colsName[4] );
				
			// TEST / UPDATE or INSERT
			$sql = sprintf($selectQuery,constant('LoggerTime::tableName'), $sqlWhere );
			$result = $this->database->openConnectionWithReturnNew($sql, false);
			//  
			if ( $result->haveResult() && !$add  ) {
				// UPDATE
				$values = sprintf('%s=%s+1 , %s=(%f+%s) %s', $colsName[2], $colsName[2], $colsName[3], $chargingTime, $colsName[3],$sqlWhere);
				$sql = sprintf($updateQuery, constant('LoggerTime::tableName'), $values);
				$this->database->openConnectionNoReturnNew($sql);
			} else {
				// INSERT INTO
				$values = sprintf(' \'%s\', \'%s\', 1, %f, GETDATE()',$idsession,$action,$chargingTime);
				$sql = sprintf($insertQuery,constant('LoggerTime::tableName'),implode(',', $colsName) , $values);
				$this->database->openConnectionNoReturnNew($sql);
			}
			unset ($values);
			unset ($sql);
				
			unset ($result);
		}
		catch (Exception $e) {}

	}

}//end class

/************************ NOTES
 SQL CONFIG:

 USE MAMBOFRSTHI

 -- CREATE mos_time
 IF OBJECT_ID('mos_time', 'U') IS NOT NULL
 DROP TABLE mos_time

CREATE TABLE [EXTSTHFR].[mos_time](
	[id_session] [varchar](200) NOT NULL CONSTRAINT [DF_Table_1_id_session]  DEFAULT ('anyone'),
	[action] [varchar](50) NOT NULL CONSTRAINT [DF_Table_1_action]  DEFAULT ('none'),
	[count] [int] NOT NULL CONSTRAINT [DF_Table_1_count]  DEFAULT ((1)),
	[time] [float] NOT NULL CONSTRAINT [DF_Table_1_time]  DEFAULT ((0)),
	[date] [datetime] NOT NULL DEFAULT (GETDATE()), 
 CONSTRAINT [PK_Table_1] PRIMARY KEY CLUSTERED 
(
	[id_session] ASC,
	[action] ASC
	[date] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
 



 */
?>