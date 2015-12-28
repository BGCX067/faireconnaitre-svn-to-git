<?php
  /**
   * This class is used for creating log files
   *
   * @author Macdonald Robinson {@link http://macs-framework.sourceforge.net/}
  */
  class Log
  {
    var $logBase = '';

    /**
     * @return void|int
    */
    function Log($logBase)
    {
      if(file_exists($logBase) && (is_dir($logBase)))
        $this->logBase = $logBase;
      else
        return false;
    }

    /**
     * @return void
    */
    function writeLog($logType, $logHeader, $data)
    {
      $header = "****************************************************************************\n";
      $header .= $logHeader."\n";
      $header .= 'Timestamp: '.date('d-M-Y H:i:s')."\n";
      $header .= "----------------------------------------------------------------------------\n";
      $footer = "****************************************************************************\n\n";

      $logFolder = "$this->logBase/$logType";

      if(!file_exists($logFolder))
      	mkdir($logFolder, 0755);

      $filePath = $logFolder.'/'.$logType.'_'.date('Y-m-d').'.txt';
      $content = $header."\n".print_r($data,true)."\n".$footer;
      $handler = fopen($filePath, 'a+');
      fwrite($handler, $content);
      fclose($handler);
    }
  }
?>
