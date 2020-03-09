<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreSpacecraftRequest;
use App\Http\Requests\UpdateSpacecraftRequest;
use App\Http\Resources\SpacecraftCollection;
use App\Http\Resources\Spacecraft as SpacecraftResource;
use App\Models\Spacecraft;
use App\Http\Controllers\Controller;

class SpacecraftController extends Controller
{
    //using '_' as default. In the restful URL, empty will lead to name//status/0
    public function index($name = '_', $class = '_', $status = 0)
    {
        return new SpacecraftCollection(Spacecraft::search([ 'name' => $name, 'class' => $class, 'status' => $status ]));
    }

    public function show(Spacecraft $spacecraft)
    {
        return new SpacecraftResource($spacecraft, true);
    }

    public function store(StoreSpacecraftRequest $request)
    {
        $request->persist();

        return response()->json([ 'success' => true ]);
    }

    public function update(Spacecraft $spacecraft, UpdateSpacecraftRequest $request)
    {
        $request->persist();

        return response()->json([ 'success' => true ]);
    }

    public function destroy($id)
    {
        $spacecraft = Spacecraft::findOrFail($id);
        $this->authorize('delete', $spacecraft);
        //trash method is deleting the image file too
        $spacecraft->trash();

        return response()->json([ 'success' => true ]);
    }
}
