<?php
  require_once 'ini/class.ConfigMagik.php';

  /**
   * This Class is a wrapper around the class.ConfigMagik.php ({@link http://www.phpclasses.org/browse/package/1726.html})
   *
   * It provides some helper functions for manipulating INI Files.
   *
   * @author Macdonald Robinson {@link http://macs-framework.sourceforge.net/}
  */

  class INI extends ConfigMagik
  {
    function INI($path=null, $synchronize=false, $process_sections=true)
    {
      parent::ConfigMagik($path, $synchronize, $process_sections);
    }
  }

?>
