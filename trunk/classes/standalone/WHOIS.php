<?php

  require_once 'whois/whois.main.php';

  /**
   * This Class is a wrapper around the phpWhois Library ({@link http://www.phpwhois.com/})
   *
   * It provides some helper functions for creating, compressing and uncompressing Archives
   *
   * @author Macdonald Robinson {@link http://macs-framework.sourceforge.net/}
  */

  class WHOIS extends WhoisMain
  {
    function WHOIS()
    {
      parent::WhoisMain();
    }
  }

?>
