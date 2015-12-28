<?php

  require_once 'pclzip/pclzip.lib.php';

  /**
   * This Class is a wrapper around the PCLZip Library ({@link http://www.phpconcept.net/pclzip/index.en.php})
   *
   * It provides some helper functions for creating, compressing and uncompressing Archives
   *
   * @author Macdonald Robinson {@link http://macs-framework.sourceforge.net/}
  */

  class ZIP extends PclZip
  {
    function ZIP($archivePath)
    {
      parent::PclZip($archivePath);
    }
  }

?>
