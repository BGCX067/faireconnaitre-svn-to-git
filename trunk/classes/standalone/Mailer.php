<?php
  require_once 'phpmailer/class.phpmailer.php';
  
  class Mailer
  {
    private $mailer = NULL;
    private $errorMessages = array();
    private $mailType = 'Mail'; // SMTP | Mail | Sendmail | Qmail

    // Will be used if the mailType variable is set to SMTP
    private $smtpHost = 'localhost';
    private $smtpPort = 25;
    private $smtpUsername = '';
    private $smtpPassword = '';
    
    public function getMailer()
    {
      return $this->mailer;
    }

    public function Mailer( $fromAddress, $fromName )
    {
      $this->mailer = new PHPMailer(true);
      $this->mailer->SetFrom($fromAddress, $fromName);
    }
    
    public function send()
    {
      if( count($this->errorMessages)>0 )
        return false;

      $result = false;

      switch ($this->mailType)
      {
        case 'Sendmail':
          $this->mailer->IsSendmail();
        break;

        case 'Qmail':
          $this->mailer->IsQmail();
        break;

        case 'SMTP':
          $this->mailer->IsSMTP();
          $this->mailer->Host = $this->smtpHost;
          $this->mailer->Port = $this->smtpPort;
          $this->mailer->Username = $this->smtpUsername;
          $this->mailer->Password = $this->smtpPassword;
        break;
      }
      try
      {
        $sent = $this->mailer->Send();
        return $sent;
      }
      catch (phpmailerException $e)
      {
        $this->errorMessages['ErrorInfo'] = $e->getMessage();
      }
      catch (Exception $e)
      {
        $this->errorMessages['ErrorInfo'] = $e->getMessage();
      }
      
      return false;
    }
    
    public function isValidEmail($emailAddress)
    {
      if(!stristr($emailAddress, '@'))
        return false;

      list($account, $host) = explode('@', $emailAddress);

      if(!$this->isValidHost($host))
      {
        $this->errorMessages[$emailAddress] = 'Invalid email address';
        return false;
      }

      return true;
    }

    public function isValidHost($hostname)
    {
      $ips = gethostbynamel($hostname);

      if( is_array($ips) && count($ips)>0 )
      {
        if(!function_exists('checkdnsrr'))
          return true;

        if(!checkdnsrr($hostname ,'MX'))
          return false;
      }
        return true;


      return false;
    }

    public function setSubject( $subject )
    {
      $this->mailer->Subject = $subject;
    }

    public function setReplyToAddress( $emailAddress, $name )
    {
        $this->mailer->AddReplyTo($emailAddress, $name);
    }

    public function setAddresses( $to, $cc = array(), $bcc = array() )
    {
      foreach ($to as $key=>$value)
      {
        if($this->isValidEmail($value))
          $this->mailer->AddAddress($value, $key);
      }

      foreach ($cc as $key=>$value)
      {
        if($this->isValidEmail($value))
          $this->mailer->AddCC($value, $key);
      }

      foreach ($bcc as $key=>$value)
      {
        if($this->isValidEmail($value))
          $this->mailer->AddBCC($value, $key);
      }
    }

    public function setMessage($htmlMessage, $plainTextMessage='')
    {
      $this->mailer->MsgHTML($htmlMessage);
      
      if($plainTextMessage != '')
        $this->mailer->AltBody = $plainTextMessage; // optional - MsgHTML will create an alternate automatically

    }

    public function attachFiles($filePathsArray)
    {
      foreach ($filePathsArray as $index=>$path)
      	$this->mailer->AddAttachment($path);
      }
    }
?>
