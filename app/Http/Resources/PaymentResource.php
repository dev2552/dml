<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => $this->type,
            'server' => new ServerResource($this->server),
            'domain'=>new DomainResource($this->domain),
            'ip'=>new IpResource($this->ip),
            'group' => $this->group,
            'unit_price' => $this->unit_price,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'currency' => $this->currency,
            'payment_date' => ($this->payment_date ? $this->payment_date->format('Y-m-d') : ""),
            'description' => $this->description,
            'created'=>$this->created->format('Y-m-d'),
            'id'=>$this->id,
            'createdBy'=>$this->createdBy,
            'updatedBy'=>$this->updatedBy,
            'deletedBy'=>$this->deletedBy,
        ];
    }

    public function paymentable($type)
    {
        if($type == 'App\Models\ServerModel') return new ServerResource($this->paymentable);
        if($type == 'App\Models\IpModel') return new IpResource($this->paymentable);
        if($type == 'App\Models\DomainModel') return new DomainResource($this->paymentable);
    }
}
