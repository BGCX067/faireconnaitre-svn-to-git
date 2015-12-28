<?php

  require_once 'geoip/geoipcity.inc';
  require_once 'geoip/geoipregionvars.php';

  /**
   * This Class is a wrapper around the geolitecity Library ({@link http://www.maxmind.com/app/geolitecity})
   *
   * It provides functions to get Country name, City, Region from an IP address
   *
   * @author Macdonald Robinson {@link http://macs-framework.sourceforge.net/}
  */

  class IPLookup
  {
    var $geoip = NULL;
    
    function IPLookup()
    {
      $this->geoip = geoip_open(dirname(__FILE__)."/geoip/GeoIPCity.dat",GEOIP_STANDARD);
    }
    
    function getIPFromDomainName($domainName)
    {
      return gethostbyname($domainName);
    }
    
    function getIPsFromDomainName($domainName)
    {
      return gethostbynamel($domainName);
    }
    
    function lookup($ipAddress)
    {
      $record = geoip_record_by_addr($this->geoip,$ipAddress);
      return $record;
    }
    
    function close()
    {
      geoip_close($this->geoip);
    }
    
  }

?>
