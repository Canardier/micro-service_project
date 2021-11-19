<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Models\format;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class VideoRessource extends JsonResource implements HasMedia
{

    use InteractsWithMedia;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "source" => $this->getFirstMediaUrl('videos'),
            "created_at" => $this->created_at,
            "view" => $this->view,
            "enabled" => $this->enabled,
            "user" => UserResource::make($this->user),
            "format" => (object) ([
                "1080" => $this->getFirstMedia('videos', ['format' => '1080']) ? $this->getMedia('videos', ['format' => '1080'])->first()->getFullUrl() : null,
                "720" => $this->getFirstMedia('videos', ['format' => '720']) ? $this->getMedia('videos', ['format' => '720'])->first()->getFullUrl() : null,
                "480" => $this->getFirstMedia('videos', ['format' => '480']) ? $this->getMedia('videos', ['format' => '480'])->first()->getFullUrl() : null,
                "360" => $this->getFirstMedia('videos', ['format' => '360']) ? $this->getMedia('videos', ['format' => '360'])->first()->getFullUrl() : null,
                "240" => $this->getFirstMedia('videos', ['format' => '240']) ? $this->getMedia('videos', ['format' => '240'])->first()->getFullUrl() : null,
                "144" => $this->getFirstMedia('videos', ['format' => '144']) ? $this->getMedia('videos', ['format' => '144'])->first()->getFullUrl() : null
            ]),
        ];
    }
}
