<?php 

namespace App\Services;
use GeoIp2\Database\Reader;

class CountryCodeService
{

	public function getCountryCodeByIp($ip)
	{
		$reader = new Reader(public_path()."/GeoLite2-Country.mmdb");
		try{
			$record = $reader->country($ip);
		}
		catch(\Exception $e)
		{
			return "";
		}
    	
    	return strtolower( $record->country->isoCode );
	}
}