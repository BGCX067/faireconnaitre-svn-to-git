<?php
  /**
   * This class is a wrapper around PHP's libCURL library.
   *
   * It provides helper functions such as server to server upload, POST forwarding etc.
   *
   * @author Macdonald Robinson {@link http://macs-framework.sourceforge.net/}
  */
  class CURL
  {
    private $errMessages = array();

    /**
     * @return array
    */
    public function getErrorMessages()
    {
      echo $this->errMessages;
    }

    /**
     * @return string
    */
    public function getEncoded($oned_array)
    {
      $str = '';
      foreach($oned_array as $key=>$val)
      {
        $str .= urlencode($key).'='.urlencode($val).'&';
      }
      return $str;
    }
    
    /**
     * @return int|mixed
    */
    public function curlForwardPOST($url)
    {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getEncoded($data));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $result = curl_exec($ch);
      $this->errMessages[] = curl_error($ch);
      curl_close($ch);
      return $result;
    }
    
    /**
     * @return int
    */
    public function curlSaveData($url,$file_path)
    {
      if(!file_exists($file_path))
        $fp = fopen($file_path, "w");
      else
        return "file '$file_name' already exist";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_FILE, $fp);
      $result = curl_exec($ch);
      $this->errMessages[] = curl_error($ch);
      curl_close($ch);
      return $result;
    }
    
    /**
     * @return mixed|int
    */
    public function curlReturnData($url)
    {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
      $result = curl_exec($ch);
      $this->errMessages[] = curl_error($ch);
      curl_close($ch);
      return $result;
    }
    
    /**
     * @return mixed|int
    */
    public function serverToServerUpload($host,$username,$password,$target_file_path,$file_path)
    {
      if(file_exists($file_path))
        $fp = fopen($file_path, "r");
      else
        return "file '$file_name' does not exist";
      $url="ftp://$username:$password@$host:21/$target_file_path";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_UPLOAD, 1);
      curl_setopt($ch, CURLOPT_INFILE, $fp);
      curl_setopt($ch, CURLOPT_FTPASCII, 1);
      curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file_path));
      $result = curl_exec($ch);
      $this->errMessages[] = curl_error($ch);
      curl_close($ch);
      return $result;
    }
  }
?>
