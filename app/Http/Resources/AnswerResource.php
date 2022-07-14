<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'detail' => $this->detail,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->user),
            'comments' => CommentResource::collection($this->comments),
            'likes_count' => $this->likes()->count(),
        ];
    }
}
