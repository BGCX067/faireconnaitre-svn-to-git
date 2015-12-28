<?php
  require_once 'media/MediaBase.php';
  
  class Media extends MediaBase
  {
    private $siteURL = '';
    
    public function __construct($siteURL, $id, $width='100%', $height='100%' )
    {
      $this->siteURL = $siteURL;
      parent::__construct($id, $width, $height);
    }
    
    public function generateFLVEmbedCode( $flvFilePath, $additionalFlashVars = array(), $params = array(), $jsDisabledText = '', $flashDisabledText = '' )
    {
      $playerURL =  $this->siteURL.'libs/media/players/jwFlvPlayer.swf';

      $flashVars = array();
      $flashVars['file'] = $flvFilePath;
      
      array_merge($flashVars, $additionalFlashVars);
      
      return $this->generateEmbedCode($playerURL, $flashVars, $params, $jsDisabledText, $flashDisabledText);
    }
    
    
    public function generateAudioEmbedCode( $audioFilePath, $additionalFlashVars = array(), $params = array(), $jsDisabledText = '', $flashDisabledText = '' )
    {
      $playerURL =  $this->siteURL.'libs/media/players/webplayer.swf';

      $flashVars = array();
      $flashVars['src'] = $audioFilePath;
      $flashVars['autostart'] = 'no';
      $flashVars['loop'] = 'no';
      
      array_merge($flashVars, $additionalFlashVars);

      return $this->generateEmbedCode($playerURL, $flashVars, $params, $jsDisabledText, $flashDisabledText);
    }

  }
?>
