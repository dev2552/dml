<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DateTime;
use App\Services\CountryCodeService;

class ServerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $countryCodeService = new CountryCodeService;

        $this->date_expiration ? ($days = $this->date_expiration > now() ? $this->date_expiration->diffInDays(now()) : (-1)*$this->date_expiration->diffInDays(now())) : $days = null;
        return [
            'group_id'=>$this->group_id,
            'group'=>$this->group,
            'provider_id'=>$this->provider_id,
            'provider'=>new ProviderResource($this->provider),
            's_name'=>$this->s_name,
            'order_number'=>$this->order_number,
            'numberIps'=>$this->ips->count(),
            'main_ip'=>$this->main_ip,
            'main_domain'=>$this->main_domain,
            'ssh_user_default'=>$this->ssh_user_default,
            'ssh_pwd_default'=>$this->ssh_pwd_default,
            'ssh_key'=>$this->ssh_key,
            'price'=>$this->price,
            'currency'=>$this->currency,
            'date_delivred'=>($this->date_delivred ? $this->date_delivred->format('Y-m-d') : null),
            'date_order'=>($this->date_order ? $this->date_order->format('Y-m-d') : null),
            'date_purchase'=>($this->date_purchase ? $this->date_purchase->format('Y-m-d') : null),
            'date_expiration'=>($this->date_expiration ? $this->date_expiration->format('Y-m-d') : null),
            'date_cancelation'=> ($this->date_cancelation ? $this->date_cancelation->format('Y-m-d') : null),
            'os'=>$this->os,
            'cpu'=>$this->cpu,
            'ram'=>$this->ram,
            'hdd'=>$this->hdd,
            'band_width'=>$this->band_width,
            'description'=>$this->description,
            'note'=>$this->note,
            'type'=>$this->type, 
            'created'=>$this->created->format('Y-m-d'),
            'id'=>$this->id,
            'status'=>new StatusResource($this->status->last()),
            'listStatus'=>StatusResource::collection($this->status),
            'mainIpCountry'=>$this->mainIpCountry,
            'mainIpCountryCode'=>$countryCodeService->getCountryCodeByIp($this->main_ip),
            //'last_status'=>$this->last_status,
            'days'=>$days,
            'ips'=>IpResource::collection($this->ips),
            'createdBy'=>$this->createdBy,
            'updatedBy'=>$this->updatedBy,
            'deletedBy'=>$this->deletedBy,
            'subDomains'=>SubDomainResource::collection($this->subDomains),
            
        ];
    }
}
