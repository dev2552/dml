<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\CountryCodeService;

class IpResource extends JsonResource
{
   

    public function toArray($request)
    {
        $countryCodeService = new CountryCodeService;
        return [
            'id'=>$this->id,
            'server'=>$this->server,
            'provider'=>$this->provider,
            'ip'=>$this->ip,
            'ip_range'=>$this->ip_range,
            'netmask'=>$this->netmask,
            'price'=>$this->price,
            'currency'=>$this->currency,
            'type'=>$this->type,
            'is_active'=>$this->is_active,
            'created'=>$this->created->format('Y-m-d'),
            'group'=>($this->server ? $this->server->group : null),
            'ipCountry'=>$this->ipCountry,
            'ipCountryCode'=>$countryCodeService->getCountryCodeByIp($this->ip),
            'ip_status'=>$this->ip_status,
            'gateway'=>$this->gateway,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'deletedBy' => $this->deletedBy,
        ];
    }
}
