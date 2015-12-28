<?php
  class GoogleMaps
  {
    private $apiKey = '';
    private $mapId = '';
    private $latsLongs = array();
    private $addresses = array();
    private $zoom = 3;
    private $mapControl = 'large';
    private $mapEnableType = true;
    private $mapEnableGoogleBar = true;
    private $mapEnableSmoothZoom = true;
    
    public function __construct( $apiKey, $mapId )
    {
      $this->apiKey = $apiKey;
      $this->mapId = $mapId;
    }
    
    public function setZoom( $zoom )
    {
      $this->zoom = $zoom;
    }
    
    public function enableSmoothZoom( $enableSmoothZoom)
    {
      $this->mapEnableSmoothZoom = $enableSmoothZoom;
    }

    public function setControl( $mapControl )
    {
      $this->mapControl = $mapControl;
    }
    
    public function showMapTypeButtons( $showMapTypeButtons )
    {
      $this->mapEnableType = $showMapTypeButtons;
    }
    
    public function showGoogleBar( $showGoogleBar )
    {
      $this->mapEnableGoogleBar = $showGoogleBar;
    }
    
    public function addLatLong( $lat, $long, $desc )
    {
      $this->latsLongs[] = array('lat' => $lat, 'long' => $long, 'desc' => $desc);
    }
    
    public function addAddress( $address, $desc )
    {
      $this->addresses[] = array('address' => $address, 'desc' => $desc);
    }
    
    public function generateScript()
    {
      $script = "
      <script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=$this->apiKey' type='text/javascript'></script>
      <script type='text/javascript'>
        $(document).ready(function(){
          setMapId('$this->mapId');
        	jQuery('#$this->mapId').jmap('init', {'mapType':'hybrid', 'mapZoom': $this->zoom , 'mapControl': '$this->mapControl', 'mapEnableType': '$this->mapEnableType', 'mapEnableScaleControl': false, 'mapShowjMapsIcon': false, 'mapEnableGoogleBar': $this->mapEnableGoogleBar, 'mapEnableSmoothZoom': '$this->mapEnableSmoothZoom' });
      		".$this->addLatLongMarkers()."
      		".$this->addAddressMarkers()."
        });
      </script>";
      
      return $script;
    }
    
    private function addLatLongMarkers()
    {
      $markers = '';
      foreach ($this->latsLongs as $key=>$value)
        $markers .= "addLatLongMarker('".$this->getPoints($value)."', '".$value["desc"]."');\r\n";
      
      return $markers;
    }
    
    private function addAddressMarkers()
    {
      $markers = '';
      foreach ($this->addresses as $key=>$value)
        $markers .= "addAddressMarker('".$value['address']."','".$value['address']."');\r\n";

      return $markers;
    }
    
    private function getPoints( array $latLong )
    {
      $code = "[".$latLong['lat'].", ".$latLong['long']."]";
      
      return $code;
    }
    
  }
?>
