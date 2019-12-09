<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DomainResource extends JsonResource
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
            'id'=>$this->id,
            'group'=>$this->group,
            'registrar'=>$this->registrar,
            'domain'=>$this->domain,
            'datex'=>($this->datex ? $this->datex->format('Y-m-d') : ''),
            'entered'=>($this->entered ? $this->entered->format('Y-m-d') : ''),
            'datec'=>($this->datec ? $this->datec->format('Y-m-d') : ''),
            'status'=>$this->status,
            'is_active'=>$this->is_active,
            'registrar_id'=>$this->registrar_id,
            'group_id'=>$this->group_id,
            'is_expired'=>$this->is_expired,
            'price'=>$this->price,
            'currency'=>$this->currency,
            'description'=>$this->description,
            'alreadyAssigned'=>$this->alreadyAssigned,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'deletedBy' => $this->deletedBy,
            'ip'=>$this->ip,
            'server'=>$this->server,
        ];
    }
}
