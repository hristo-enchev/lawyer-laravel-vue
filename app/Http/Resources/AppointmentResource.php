<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'lawyer' => $this->lawyer()->first()->name,
            'user' => $this->user()->first()->name,
            'from_time' => $this->from_time,
            'to_time' => $this->to_time,
            'status' => $this->status,
            'details' => $this->details,
            'user_id' => $this->user_id,
            'lawyer_id' => $this->lawyer_id
        ];
    }
}
