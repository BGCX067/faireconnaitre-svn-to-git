<?php
  require_once 'dompdf/dompdf_config.inc.php';

  class PDF
  {
    public $dompdf = NULL;
    private $fileName = 'test.pdf';

    public function __construct($fileName)
    {
      $this->fileName = $fileName;
      $this->dompdf = new DOMPDF();
    }
    
    public function setBasePath($basePath)
    {
      $this->dompdf->set_base_path($basePath);
    }
    
    public function setHost($host)
    {
      $this->dompdf->set_protocol($host);
    }
    
    public function setPaper($size = 'A4', $orientation = "portrait")
    {
      $this->dompdf->set_paper($size, $orientation);
    }
    
    public function setProtocol($protocol)
    {
      $this->dompdf->set_protocol($protocol);
    }
    
    private function Generate()
    {
      $this->dompdf->render();
      $this->dompdf->stream($this->fileName);
    }

    public function GenerateFromString($html)
    {
      $this->dompdf->load_html($html);
      $this->Generate();
    }

    public function GenerateFromFile($filePath)
    {
      $this->dompdf->load_html_file($filePath);
      $this->Generate();
    }

  }

?>
