<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
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
            'name'=>$this->name,
            'country'=>$this->country,
            'url_site'=>$this->url_site,
            'cpanel'=>$this->cpanel,
            'login'=>$this->login,
            'pwd'=>$this->pwd,
            'id_customer'=>$this->id_customer,
            'inscr_email'=>$this->inscr_email,
            'inscrlname'=>$this->inscrlname,
            'inscrfname'=>$this->inscrfname,
            'status'=>$this->status,
            'type'=>$this->type,
            'note'=>$this->note,
            'created'=>$this->created->format('Y-m-d'),
            'createdBy'=>$this->createdBy,
            'updatedBy'=>$this->updatedBy,
            'deletedBy'=>$this->deletedBy,
        ];
    }
}
