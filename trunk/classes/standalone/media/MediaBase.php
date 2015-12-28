<?php
  class MediaBase
  {
    protected $srcURL = '';
    protected $useSWFObject = true;
    protected $width = '100%';
    protected $height = '100%';
    protected $id = '';
    protected $jsDisabledText = 'Javascript is disabled';
    protected $flashDisabledText = 'Flash is disabled or not installed';
    protected $params = array();
    protected $flashVars = array();

    public function __construct( $id, $width='100%', $height='100%' )
    {
      $this->id = $id;
      $this->width = $width;
      $this->height = $height;
    }

    public function setSrcURL( $srcURL )
    {
      $this->srcURL = $srcURL;
    }

    public function setFlashVars( $flashVars )
    {
      $this->flashVars = $flashVars;
    }

    public function setParams( $params )
    {
      $this->params = $params;
    }

    public function jsDisabledText( $jsDisabledText )
    {
      $this->jsDisabledText = $jsDisabledText;
    }

    public function flashDisabledText( $flashDisabledText )
    {
      $this->flashDisabledText = $flashDisabledText;
    }

    public function generateEmbedCode($playerURL, array $flashVars = array(), array $params=array(), $jsDisabledText='', $flashDisabledText='')
    {
      $this->setSrcURL($playerURL);
      $this->setFlashVars($flashVars);

      if($jsDisabledText != '')
        $this->jsDisabledText($jsDisabledText);

      if($flashDisabledText != '')
        $this->flashDisabledText($flashDisabledText);

      if( count($params) == 0 )
      {
        $params['quality'] = 'high';
        $params['wmode'] = 'transparent';
      }

      $this->setParams($params);
      
      return $this->generateSwfObject();
    }

    public function generateDiv()
    {
      $div = '
      <div id="'.$this->id.'" style="width:'.$this->width.'px; height:'.$this->height.'px">
        '.$this->jsDisabledText.'
      </div>
      ';

      return $div;
    }

    private function generateSWFObjectFlashVars()
    {
      $code = '';
      foreach ($this->flashVars as $key=>$value)
        $code .= "so.addVariable('$key', '$value');\r\n";

      return $code;
    }

    private function generateSWFObjectParams()
    {
      $code = '';
      foreach ($this->params as $key=>$value)
        $code .= "so.addParam('$key', '$value');\r\n";

      return $code;
    }

    private function generateSwfObject()
    {
      $code = '
        <script type="text/javascript">
          $(document).ready(function(){
             $("#'.$this->id.'").html("'.$this->flashDisabledText.'");
             var so = new SWFObject("'.$this->srcURL.'", "swf-'.$this->id.'", "'.$this->width.'", "'.$this->height.'", "8");
             '.$this->generateSWFObjectParams().'
             '.$this->generateSWFObjectFlashVars().'
             so.write("'.$this->id.'");
          });
        </script>
      ';

      return $code;
    }
  }
?>
