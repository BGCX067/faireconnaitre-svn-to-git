<?php

  require_once 'encryption/Crypt.class.php';

  /**
   * This Class is a wrapper around the Encryption Library ({@link http://phpclasses.ca/browse/file/17234.html})
   *
   * It provides some helper functions for encrypting, decrypting strings based on a key
   *
   * @author Macdonald Robinson {@link http://macs-framework.sourceforge.net/}
  */

  class Encryption extends Crypt
  {
    function Encryption()
    {
      parent::__construct();
    }
  }

?>
