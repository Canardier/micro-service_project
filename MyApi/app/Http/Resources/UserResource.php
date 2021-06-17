<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            "id" => $this->id,
            "username" => $this->username,
            "pseudo" => $this->pseudo,
            "created_at" => $this->created_at,
            "email" => $this->when(Auth::check() && Auth::user()->id == $this->id, $this->email)
        ];
    }
}
