<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\RoleResource;
use App\Http\Resources\V1\RoleCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'token' => $this->token,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            // 'roles' => $this->whenLoaded('roles', function () {
            //     return $this->roles;
            // })
        ];
    }
}
