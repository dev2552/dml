<?php

namespace App\Console\Commands;

use App\Services\OpenExchangeRatesService;
use Illuminate\Console\Command;

class OpenExchangeRates extends Command
{
    
    protected $signature = 'open_exchange_rates:update';
    protected $description = 'Update open_exchange_rates Table';

    protected $openExchangeRatesService;

  
    public function __construct(OpenExchangeRatesService $openExchangeRatesService)
    {
        $this->openExchangeRatesService = $openExchangeRatesService;
        parent::__construct();
    }

    
    public function handle()
    {
        $this->openExchangeRatesService->update();
    }
}
