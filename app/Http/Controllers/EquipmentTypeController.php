<?php

namespace App\Http\Controllers;

use App\Models\EquipmentType;
use Illuminate\Http\Request;
use App\Http\Resources\EquipmentTypeResource;
use App\Http\Requests\EquipmentType\StoreRequest;
use App\Http\Requests\EquipmentType\UpdateRequest;

class EquipmentTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = EquipmentType::query();

        if ($q = $request->input('q')) {
            $query->where('name', 'LIKE', '%' . $q . '%');
        }

        return EquipmentTypeResource::collection($query->paginate(15));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $equipmentType = EquipmentType::create($data);
        return new EquipmentTypeResource($equipmentType);
    }

    public function show(EquipmentType $equipmentType)
    {
        return new EquipmentTypeResource($equipmentType);
    }

    public function update(UpdateRequest $request, EquipmentType $equipmentType)
    {
        $data = $request->validated();
        $equipmentType->update($data);
        return new EquipmentTypeResource($equipmentType);
    }

    public function destroy(EquipmentType $equipmentType)
    {
        $equipmentType->delete();
        return response()->json([], 204);
    }
}
