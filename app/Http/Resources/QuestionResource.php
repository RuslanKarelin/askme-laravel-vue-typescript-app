<?php

namespace App\Http\Resources;

use App\Models\QuestionState;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class QuestionResource extends JsonResource
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
            'title' => $this->title,
            'detail' => $this->detail,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->user),
            'answers_count' => $this->answers_count,
            'state' => new QuestionStateResource($this->state),
            'status' => new QuestionStatusResource($this->status),
            'tags' => TagResource::collection($this->tags),
            'can' => $this->permissions(),
        ];
    }

    protected function permissions(): array
    {
        return [
            'update' => Gate::allows('update', $this->resource),
            'destroy' => Gate::allows('destroy', $this->resource)
        ];
    }
}
