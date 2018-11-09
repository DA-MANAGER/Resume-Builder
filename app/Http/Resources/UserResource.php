<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'email'      => $this->email,
            'name'       => $this->name,
            'username'   => $this->username,
            'avatar'     => $this->avatar,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString()
        ];
    }
}
