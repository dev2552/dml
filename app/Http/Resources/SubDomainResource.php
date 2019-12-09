<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubDomainResource extends JsonResource
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
       'ip'=>$this->ip,
       'id'=>$this->id,
       'subdomain'=>$this->subdomain,
       'is_active'=>$this->is_active,
       'created'=>$this->created ? $this->created->format('Y-m-d') : null ,
       'user'=>$this->user,
       'domain'=>$this->domain,];
    }
}
