<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
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
            'status' => $this->status,
            'explication' => $this->explication,
            'id' => $this->id,
            'created'=>($this->created ? $this->created->format('Y-m-d') : null),
            'createdBy' => $this->createdBy,
            'server'=> $this->server,
            'request'=>$this->request,
        ];
    }
}
