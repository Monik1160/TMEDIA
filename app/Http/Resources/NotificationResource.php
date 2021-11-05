<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'read_at' => ($this->pivot->read_at != NULL) ? $this->pivot->read_at : '',
            'deleted_at' => ($this->pivot->deleted_at) ? $this->pivot->deleted_at : ''
        ];
    }
}
