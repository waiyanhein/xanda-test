<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class UpdateSpacecraftRequest extends StoreSpacecraftRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && optional($this->route('spacecraft'))->fleet_id == auth()->user()->fleet_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['name'] = [ 'required', 'unique:spacecrafts,name,' . $this->route('spacecraft')->id ];
        $rules['image'] = [ 'image' ];

        return $rules;
    }

    public function persist()
    {
        $spacecraft = $this->route('spacecraft');
        $spacecraft->update(request([
            'name', 'class', 'crew', 'value', 'status'
        ]));
        //only update the image if present and maintain the previous image.
        if ($this->file('image')) {
            $spacecraft->deleteImageFile();
            $spacecraft->image = $this->file('image')->storePublicly('public/');
        }
        $spacecraft->save();

        $this->updateArmaments();
    }

    private function updateArmaments()
    {
        $spacecraft = $this->route('spacecraft');
        $spacecraft->armaments()->delete();
        $this->attachArmaments($spacecraft);
    }
}
