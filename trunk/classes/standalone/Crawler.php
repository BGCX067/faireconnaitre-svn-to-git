<?php
  class Crawler
  {
    private $crawleLog = array();
    public $foundLog = array();
    private $initialUrl = '';
    private $extenalLinks = array();
    private $tagsToSearchIn = array('html');
    private $crawlerDataFile = 'crawlerLog.php';
    private $currentLanguage = '';

    public function __construct($currentLanguage, $defaultController, $defaultFunction)
    {
      $this->currentLanguage = $currentLanguage;
      $this->defaultController = $defaultController;
      $this->defaultFunction = $defaultFunction;
      
      $this->crawleLog = array();
      //ob_end_flush();
    }
    
    public function setTagsToSearchIn( array $tagsToSearchIn )
    {
      $this->tagsToSearchIn = $tagsToSearchIn;
    }
    
    public function getDataFilePath()
    {
      return Master::getConfig()->siteDir.Master::getConfig()->writableDir.'crawlerLog/'.$this->currentLanguage.'.'.$this->crawlerDataFile;
    }
    
    public function saveCrawleLog()
    {
      file_put_contents( $this->getDataFilePath(), serialize($this->crawleLog) );
    }
    
    public function getCrawleLog()
    {
      if( file_exists($this->getDataFilePath()) )
        return @unserialize(file_get_contents( $this->getDataFilePath() ));

      return null;
    }

    public function start( $url, $searchString, $followLinks, $followExternalLinks)
    {
      $crawleLog = $this->getCrawleLog();
      
      if( !is_array($crawleLog) )
      {
        $this->initialUrl = $url;
        $this->crawleLink($this->initialUrl, $searchString, $followLinks);

        if($followExternalLinks)
        {
          foreach ($this->extenalLinks as $key=>$value)
          {
            if( !in_array($value, array_keys($this->crawleLog)) )
            {
              $this->initialUrl = $value;
              $this->crawleLink($value, $searchString, $followLinks);
            }
          }
        }
        $this->saveCrawleLog();
      }
      else
        $this->searchInCrawleLog($searchString, $crawleLog);

      return $this->foundLog;
    }
    
    public function searchInCrawleLog($searchString, $crawleLog)
    {
      $crawleLog = array_unique($crawleLog);
      
      foreach ($crawleLog as $key=>$value)
      {
        $doc = new DOMDocument();
        $doc->loadHTML($value);

        if( $this->searchForString($doc, $searchString))
          $this->addToFoundLog($doc, $key);
      }
    }
    
    public function AllowURL( $url )
    {
      if(in_array($url, array_keys($this->crawleLog)))
        return false;

      return true;
    }

    public function crawleLink($url, $searchString, $followLinks = false)
    {
      $beforeCleaning = $url;
      $url = $this->cleanUrl($url);
      
      if( !$this->AllowURL($url) )
      {
        //echo '<strong>$url OMITTED: '.$url.'</strong><br />';
        //flush();

        return;
      }
        
      /*echo '<strong>Before: '.$beforeCleaning.' | After: '.$url.'</strong><br />';
      flush();*/
        
      $doc = new DOMDocument();
      @$doc->loadHTMLFile($url);

      $this->crawleLog[$url] = $doc->saveHTML();
        
      $tags = $doc->getElementsByTagName('a');

      $links = array();

      foreach ($tags as $tag)
      {
        $href = $this->cleanUrl($tag->getAttribute('href'));

        if( !$this->AllowURL($href) )
          continue;

        $links[] = $href;
        
        if( $this->searchForString($doc, $searchString))
          $this->addToFoundLog($doc, $url);
      }

      if($followLinks)
        $this->followLinks($url, $links, $searchString, $followLinks);

    }
    
    public function followLinks($parentUrl, $links, $searchString, $followLinks)
    {
      foreach ($links as $key=>$value)
      {
        $childUrl = $this->getAbsURL($parentUrl, $value);

        if($childUrl == '')
          continue;

        if($this->isExternalURL($childUrl))
        {
          if( !in_array($childUrl, $this->extenalLinks) )
            $this->extenalLinks[] = $this->cleanUrl($childUrl);
            
          continue;
        }

        if( !$this->AllowURL($childUrl) )
          continue;
        
      	$this->crawleLink($childUrl, $searchString, $followLinks);
      }
    }
    
    public function cleanUrl($url)
    {
      $url = strtolower($url);

      if( stristr($url, 'javascript:') !== false)
        return '';

      if( stristr($url, 'mailto:') !== false)
        return '';
        
      if( stristr($url, 'root/') !== false)
        return '';
        
      if( stristr($url, 'basecontroller/') !== false)
        return '';

      if( stristr($url, '#') !== false)
        return '';
        
      if( !stristr($url, 'lang=') && !stristr($url, '?') )
          $url .= '?lang='.$this->currentLanguage;

      if( !stristr($url, 'lang=') && stristr($url, '?') )
          $url .= '&amp;lang='.$this->currentLanguage;

      if( stristr($url, 'lang=') && !stristr($url, 'lang='.$this->currentLanguage) )
        return '';
        
      if( stristr($url, '/?') !== false)
      {
        list($urlSeg, $querySeg) = explode('/?', $url);

        $lastChar = substr($urlSeg,strlen($urlSeg)-1, strlen($urlSeg));

        if($lastChar == '/')
          $urlSeg = substr($urlSeg,0, strlen($urlSeg)-1);

        $url = implode('?', array($urlSeg, $querySeg));
      }
      
      if( stristr($url, '/'.$this->defaultController.'/'.$this->defaultFunction) )
        return '';
        
        
      $lastChar = substr($url,strlen($url)-1, strlen($url));
      
      if($lastChar == '&')
        $url = substr($url,0, strlen($url)-1);

      return $url;
    }

    public function searchForString(DOMDocument $doc, $searchString)
    {
      $searchString =  stripslashes(strtolower(trim($searchString)));
      if($searchString == '')
        return true;

      $elements = array();
      
      foreach ($this->tagsToSearchIn as $key=>$value)
        $elements[] = $doc->getElementsByTagName($value);
      
      foreach($elements as $hey=>$value)
      {
        foreach ($value as $subKey=>$subValue)
        {
          $str = '';
          
          foreach ($subValue->attributes as $attrName=>$attrValue)
            $str.= $attrValue->nodeValue;

          $str .= $subValue->nodeValue;
          
          $str = strtolower($str);

          if( substr_count( $str, $searchString) > 0 )
            return true;
        }
      }

      return false;
    }
    
    public function isExternalURL($url)
    {
      if( stristr($url, $this->initialUrl) === false )
        return true;

      return false;
    }
    
    public function getAbsURL($parentUrl, $url)
    {
      $parentUrl = $this->cleanUrl($parentUrl);
      $url = $this->cleanUrl($url);

      if(($parentUrl == '') || ($url == ''))
        return '';

      $parentSegments = parse_url($parentUrl);
      
      if((stripos($url, '/') === 0) || ( (isset($parentSegments['path']) && ($parentSegments['path'] !='/') && (stristr($url, '://') === false))))
        $url = $parentSegments['scheme'].'://'.$parentSegments['host'].$url;

      if(stristr($url, '://') === false)
      {
        $lastChar = substr($parentUrl, strlen($parentUrl)-1, strlen($parentUrl));

        if($lastChar != '/')
          $parentUrl = $parentUrl.'/';
        
        $path = '';
        
        if(isset($parentSegments['path']))
          $path = $parentSegments['path'];
        
        $url = $parentSegments['scheme'].'://'.$parentSegments['host'].$path.'/'.$url;
      }
        
      return trim($url);
    }
    
    public function renderResults($onlyLinks = true)
    {
      $html = '<ul>';

      foreach ($this->foundLog as $key=>$value)
        $html .= $this->renderResult($key, $value, $onlyLinks);
        
      $html .= '</ul>';
      
      return $html;
    }

    public function renderResult($href, $docXml, $onlyLinks = true)
    {
      $doc = new DOMDocument();
      $doc->loadHTML($docXml);
      
      $linkTitle = trim($doc->getElementsByTagName('title')->item(0)->firstChild->nodeValue);

      $meta = '';
      $html = '<li>'."\r\n";
      
      if(!$onlyLinks)
      {
        $metas = $doc->getElementsByTagName('meta');
        
        foreach ($metas as $key=>$value)
        {
          $name = $value->getAttribute('name');
          if( strtolower($name)  == 'description' )
            $meta.= $value->getAttribute('content');
        }
      }

      if($href == '')
        return;

      if($linkTitle == '')
        $linkTitle = $href;

      $html .= '<a href="'.$href.'" class="title">'.$linkTitle.'</a>'."\r\n";
      
      if(!$onlyLinks)
        $html .='<p class="metaDesc">'.$meta.'</p>'."\r\n";

      $html .= '</li>'."\r\n";
      
      return $html;
    }
    
    public function addToFoundLog(DOMDocument $doc, $parentUrl)
    {
      if( array_key_exists($parentUrl, $this->foundLog) )
        return;
      
      $this->foundLog[$parentUrl] = $doc->saveXML();
    }
  }
?>
