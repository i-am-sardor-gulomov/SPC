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
        $credential = Credential::where([
            'user_id'=>auth()->user()->id,
            'app_id'=>$this->id
        ]);

        if (is_null($credential)){
            $front_status = $back_status = false;
        }elseif($credential->count()>1){
            abort(500, 'Bazada xatolik. Foydalanuvchida ortiqcha kridensiyalar mavjud.');
        }else{
            $front_status = !(is_null($credential->first('front_login')) or is_null($credential->first('front_password')));
            $back_status = !(is_null($credential->first('back_login')) or is_null($credential->first('back_password')));
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'front_url' => $this->front_url,
            'front_status' => $front_status,
            'back_url' => $this->back_url,
            'back_status' => $back_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}