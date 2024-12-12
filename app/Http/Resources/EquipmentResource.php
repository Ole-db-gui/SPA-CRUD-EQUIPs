<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'serial_number' => $this->serial_number,
            'description' => $this->description,
            'equipment_type' => new EquipmentTypeResource($this->whenLoaded('equipmentType')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
