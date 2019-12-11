<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistrarResource extends JsonResource
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

       'name' => $this->name,
       'company' => $this->company,
       'eml_contact' => $this->eml_contact,
       'password' => $this->password,
       'customerid' => $this->customerid,
       'registrar_key' => $this->registrar_key,
       'secret' => $this->secret,
       'website' => $this->checkWebsite($this->website),
       'phone' => $this->phone,
       'address' => $this->address,
       'description' => $this->description,
       'is_active' => $this->is_active,
       'id' => $this->id,
       //'domains' => DomainResource::collection($this->domains),
       'createdBy' => $this->createdBy,
       'updatedBy' => $this->updatedBy,
       'deletedBy' => $this->deletedBy,
     ];
    }


    public function checkWebsite($website)
    {
       if( !preg_match("/^https:\/\//", $website) && $website!==null )
       {
        return "https://".$website;
       }
       return $website;
    }

   
}


//inprod