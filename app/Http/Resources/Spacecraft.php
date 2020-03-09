<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Spacecraft extends JsonResource
{
    private $fetchFullDetails = false;

    public function __construct($resource, $fetchFullDetails = false)
    {
        parent::__construct($resource);
        $this->fetchFullDetails = $fetchFullDetails;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status_label,
            //the order of the fields is not the same as the sample response in the question cause I am using mergeWhen
            $this->mergeWhen($this->fetchFullDetails, [
                'class' => $this->class,
                'crew' => $this->crew,
                'image' => $this->image,
                'value' => $this->value,
                'armament' => new ArmamentCollection($this->armaments()->get()),
            ])
        ];
    }
}
