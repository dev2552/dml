<?php 

namespace App\Services;

use App\OpenExchangeRates;

class OpenExchangeRatesService {

	protected $baseURl = 'https://openexchangerates.org/api/';
	protected $appId = '4a0b032477fd43b09d6831c4829846dd';
	protected $openExchangeRates;

	public function __construct(OpenExchangeRates $openExchangeRates)
	{
		$this->openExchangeRates = $openExchangeRates;
	}

	public function update()
	{
		$url = $this->baseURl.'latest.json?app_id='.$this->appId;
		$curl = curl_init();
		curl_setopt_array($curl,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_URL=>$url]);
		$res = curl_exec($curl);
		curl_close($curl);
		$res = json_decode($res);
		$res = (array)$res->rates;

		$this->openExchangeRates->MAD = $res['MAD'];
		$this->openExchangeRates->EUR = $res['EUR'];
		$this->openExchangeRates->GBP = $res['GBP'];
		$this->openExchangeRates->save();
	}

	public function toUSD($value,$currency) 
	{
		$openExchangeRates = $this->openExchangeRates->all()->last();
		if($currency == 'EUR') return round($value*(1/$openExchangeRates->EUR),2);
		if($currency == 'GBP') return round($value*(1/$openExchangeRates->GBP),2);
		if($currency == 'MAD') return round($value*(1/$openExchangeRates->MAD),2);
		return $value;
	}
}