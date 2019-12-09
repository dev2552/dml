<?php

namespace Tests\Unit;

use App\Services\CountryCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use GeoIp2\Database\Reader;

class CountryCodeTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
    	$reader = new Reader(public_path()."/GeoLite2-Country.mmdb");
    	$record = $reader->country('128.101.101.101');
    	echo strtolower( $record->country->isoCode );
        $this->assertTrue(true);
    }
}
