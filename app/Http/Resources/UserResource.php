<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username'=>$this->username,
            'group'=>$this->group,
            'email'=>$this->email,
            'password'=>$this->password,
            'role'=>$this->roule_id,
            'f_name'=>$this->f_name,
            'l_name'=>$this->l_name,
            'active'=>$this->active,
            'id'=>$this->username,
            'createdBy'=>$this->createdBy,
            'updatedBy'=>$this->updatedBy,
            'deletedBy'=>$this->deletedBy,
        ];
    }
}
