<?php
  require_once 'wideimage/WideImage.inc.php';

  class GD
  {
    public function __construct()
    {
    }
    
    public function loadImage($imagePath)
    {
      return wiImage::load($imagePath);
    }
    
    public function createImage( $width, $height, $bgColor='#000000', $bgTransparentamount = 0 )
    {
      $newImage = wiTrueColorImage::create( $width, $height );
      
      list($bgR, $bgG, $bgB) = $this->hex2rgb($bgColor);
      $bgColor = $newImage->allocateColorAlpha($bgR, $bgG, $bgB, $bgTransparentamount);
      $newImage->fill(0,0, $bgColor);
      
      return $newImage;
    }
    
    public function createImageFromText($text, $fontColor='#000000',$fontSize = 12, $fontFile='temp/arial.ttf',
                                        $bgColor='#ffffff', $bgTransparentamount = 127,
                                        $textTransparentAmount = 0, $fontAngle = 0)
    {
      list($lLX, $lLY, $lRX, $lRY, $uRX, $uRY, $uLX, $uLY) = $details = imagettfbbox($fontSize, $fontAngle, $fontFile, $text);

      $width = abs($lRX)+abs($uLX);
      $height = abs($lRY)+abs($uRY);
      
      $newImage = $this->createImage( $width, $height, $bgColor, $bgTransparentamount);
      $this->writeTextToImage($newImage, $text, 0, 0, $fontColor, $fontSize, $fontAngle, $textTransparentAmount, $fontFile);
      
      return $newImage;
    }
    
    public function writeTextToImage($image, $text, $xPos=0, $yPos=0, $fontColor='#000000',$fontSize = 12, 
                                     $fontAngle = 0, $textTransparentAmount = 0, $fontFile='temp/arial.ttf')
    {
      if($yPos == 0)
        $yPos = $fontSize;
        
      $canvas = $image->getCanvas();
      list($fgR, $fgG, $fgB) = $this->hex2rgb($fontColor);

      $fontColor = $image->allocateColorAlpha($fgR, $fgG, $fgB, $textTransparentAmount);
      $font = new wiFont_TTF($fontFile, $fontSize, $fontColor);
      $canvas->setFont($font);

      $canvas->writeText($xPos,$yPos, $text, $fontAngle);
    }
    
    public function rgb2hex($r, $g=-1, $b=-1)
    {
        if (is_array($r) && sizeof($r) == 3)
            list($r, $g, $b) = $r;

        $r = intval($r); $g = intval($g);
        $b = intval($b);

        $r = dechex($r<0?0:($r>255?255:$r));
        $g = dechex($g<0?0:($g>255?255:$g));
        $b = dechex($b<0?0:($b>255?255:$b));

        $color = (strlen($r) < 2?'0':'').$r;
        $color .= (strlen($g) < 2?'0':'').$g;
        $color .= (strlen($b) < 2?'0':'').$b;
        return '#'.$color;
    }
    
    public function hex2rgb($color)
    {
        if ($color[0] == '#')
            $color = substr($color, 1);

        if (strlen($color) == 6)
            list($r, $g, $b) = array($color[0].$color[1],
                                     $color[2].$color[3],
                                     $color[4].$color[5]);
        elseif (strlen($color) == 3)
            list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
        else
            return false;

        $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

        return array($r, $g, $b);
    }
    
  }

?>
