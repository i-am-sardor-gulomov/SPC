<?php

namespace App\Http\Resources\v1;

use App\Models\v1\Credential;
use Illuminate\Http\Resources\Json\JsonResource;

class AppResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = auth()->user();
        $credential = Credential::where([
            'user_id'=>$user->id,
            'app_id'=>$this->id
        ]);

        if ($credential->count()==0){
            $status = false;
        }elseif($credential->count()==1){
            $status = !(is_null($credential->first('login')) or is_null($credential->first('password')));
        }else{
            abort(500, 'Bazada xatolik. Foydalanuvchida ortiqcha kridensiyalar mavjud.');
        }

        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'url' => $this->url,
            'icon' => $this->icon,
            'status' => $status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

        if ($user->status=='admin'){
            $admin_resource = [
                'IP'=>$this->IP,
                'port'=>$this->port,
                'grant_type'=>$this->grant_type,
                'client_id'=>$this->client_id,
                'client_secret'=>$this->client_secret,
            ];
            $resource = array_merge($resource, $admin_resource);
        }

        return $resource;
    }
}