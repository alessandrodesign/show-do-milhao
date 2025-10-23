<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Difficulty;
use Illuminate\Http\Request;

class DifficultyController extends Controller
{
    public function index()
    {
        return Difficulty::orderBy('level')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|unique:difficulties,name',
            'level' => 'required|integer|unique:difficulties,level',
        ]);

        return Difficulty::create($data);
    }

    public function show(Difficulty $difficulty)
    {
        return $difficulty;
    }

    public function update(Request $request, Difficulty $difficulty)
    {
        $data = $request->validate([
            'name'  => 'required|string|unique:difficulties,name,' . $difficulty->id,
            'level' => 'required|integer|unique:difficulties,level,' . $difficulty->id,
        ]);
        $difficulty->update($data);

        return $difficulty;
    }

    public function destroy(Difficulty $difficulty)
    {
        $difficulty->delete();

        return response()->noContent();
    }
}
