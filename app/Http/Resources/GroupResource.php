<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'created' => ( $this->created ? $this->created->format('M,d/Y') : null ),
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'deletedBy' => $this->deletedBy,
        ];
    }
}
