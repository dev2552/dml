<?php

namespace App\Http\Controllers;

use App\Services\OpenExchangeRatesService;
use Illuminate\Http\Request;

class OpenExchangeRates extends Controller
{
   protected $openExchangeRatesService;

   public function __construct(OpenExchangeRatesService $openExchangeRatesService)
   {
    $this->openExchangeRatesService = $openExchangeRatesService;
   }

    public function __invoke(Request $request)
    {
        $this->openExchangeRatesService->update();
    }
}
