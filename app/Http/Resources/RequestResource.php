<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
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

            'subject' => $this->subject,

            'request' => $this->request,

            'priority' => $this->priority,

            'user' => $this->user,

            'group' => ($this->user ? $this->user->group : ''),

            'id' => $this->id,

            'status' => new StatusResource($this->status->last()),

            'allStatus' => StatusResource::collection($this->status),

            // '_status'=>$this->_status,

             'created' => ( $this->created ? $this->created->format("M,d/Y") : null )
        ];
    }
}
