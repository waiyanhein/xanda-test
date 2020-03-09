<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpacecraftRequest;
use App\Http\Requests\UpdateSpacecraftRequest;
use App\Http\Resources\Spacecraft as SpacecraftResource;
use App\Models\Spacecraft;
use Illuminate\Http\Response;

class SpacecraftController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', [ 'spacecrafts' => Spacecraft::search(request([ 'name', 'class', 'status' ])) ]);
    }

    public function show(Spacecraft $spacecraft)
    {
        return response()->json(new SpacecraftResource($spacecraft, true), Response::HTTP_OK, [ ], JSON_PRETTY_PRINT);
    }

    public function create()
    {
        return view('create', [ 'spacecraft' => new Spacecraft() ]);
    }

    public function store(StoreSpacecraftRequest $request)
    {
        $request->persist();

        return response()->json([ 'success' => true ]);
    }

    public function edit(Spacecraft $spacecraft)
    {
        return view('create', compact('spacecraft'));
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

        return redirect('home');
    }
}
