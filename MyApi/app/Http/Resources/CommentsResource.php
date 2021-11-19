<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentsResource extends JsonResource
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
            "body" => $this->body,
            "send_at" => $this->send_at,
            "video_id" => $this->video_id,
            "user" => UserResource::make($this->user),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
