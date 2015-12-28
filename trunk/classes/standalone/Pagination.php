<?php
  class Pagination
  {
    private $paginationKey = '';
    private $resultsPerPage = 2;
    private $currentPageIndex = 0;
    
    public function __construct($paginationKey, $resultsPerPage)
    {
        $this->paginationKey = $paginationKey;
        $this->resultsPerPage = $resultsPerPage;
    }
    
    public function getPrefix()
    {
      return 'Pagination_';
    }
    
    public function setResults(array $result)
    {
      $_SESSION[$this->getPrefix().$this->paginationKey] = $result;
    }
    
    public function getTotalNumberofPages()
    {
      return intval(count($this->getResults())/$this->resultsPerPage);
    }
    
    public function getResults()
    {
      return (isset($_SESSION[$this->getPrefix().$this->paginationKey]) && is_array($_SESSION[$this->getPrefix().$this->paginationKey])) ? $_SESSION[$this->getPrefix().$this->paginationKey] : array();
    }
    
    public function getResultsByPageIndex($pageIndex)
    {
      $this->currentPageIndex = $pageIndex;
      $startRecord = $pageIndex * $this->resultsPerPage;
      return array_splice($this->getResults(), $startRecord, $this->resultsPerPage);
    }
    
    public function renderNav($currentPageIndex)
    {
      if($this->getTotalNumberofPages() < 2)
        return;
        
      $currentPageIndex = ($currentPageIndex < 0)? 0: $currentPageIndex;
      $currentPageIndex = ($currentPageIndex > ($this->getTotalNumberofPages()-1) )? $this->getTotalNumberofPages(): $currentPageIndex;
        
      $code = '<ul class="paging">';
      
      if($currentPageIndex != 0)
      {
        $code .= '<li><a href="?paginationKey='.$this->paginationKey.'&pageIndex=0" class="ajax first" ajaxOutput="#crawleResults"><<</a></li>';
        $code .= '<li><a href="?paginationKey='.$this->paginationKey.'&pageIndex='.($currentPageIndex-1).'" class="ajax previous" ajaxOutput="#crawleResults"><</a></li>';
      }
      else
      {
        $code .= '<li class="disabled"><a><<</a></li>';
        $code .= '<li class="disabled"><a><</a></li>';
      }
        
      for($i=0; $i < $this->getTotalNumberofPages(); $i++ )
      {
        $text = $i+1;
        
        if($i == $currentPageIndex)
          $code .='<li><a class="current">'.$text.'</a></li>';
        else
          $code .='<li><a href="?paginationKey='.$this->paginationKey.'&pageIndex='.$i.'" class="ajax" ajaxOutput="#crawleResults">'.$text.'</a></li>';
      }
      
      if($currentPageIndex != $this->getTotalNumberofPages()-1)
      {
        $code .= '<li><a href="?paginationKey='.$this->paginationKey.'&pageIndex='.($currentPageIndex+1).'" class="ajax next" ajaxOutput="#crawleResults">></a></li>';
        $code .= '<li><a href="?paginationKey='.$this->paginationKey.'&pageIndex='.($this->getTotalNumberofPages()-1).'" class="ajax last" ajaxOutput="#crawleResults">>></a></li>';
      }
      else
      {
        $code .= '<li><a class="disabled">></a></li>';
        $code .= '<li><a class="disabled">>></a></li>';
      }
      
      $code .= '</ul><div class="clear"></div>';
      
      return $code;
    }
    
  }

?>
