<?php

namespace App\Http\Controllers;

use App\Services\LlibreDeTextService;
use Illuminate\Http\Request;

class LlibreDeTextController extends Controller
{
    protected $llibreService;

    public function __construct(LlibreDeTextService $llibreService)
    {
        $this->llibreService = $llibreService;
    }

    public function index()
    {
        return response()->json($this->llibreService->getAll(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titol' => 'required|string|max:255',
            'curs' => 'required|string|max:255',
            'editorial' => 'required|string|max:255',
            'observacions' => 'nullable|string',
        ]);

        $llibre = $this->llibreService->create($request->all());

        return response()->json(['message' => 'Llibre creat correctament', 'data' => $llibre], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titol' => 'required|string|max:255',
            'editorial' => 'required|string|max:255',
            'observacions' => 'nullable|string',
        ]);

        $llibre = $this->llibreService->update($id, $request->all());

        return response()->json(['message' => 'Llibre actualitzat correctament', 'data' => $llibre], 200);
    }

    public function destroy($id)
    {
        $this->llibreService->delete($id);

        return response()->json(['message' => 'Llibre eliminat correctament'], 200);
    }
}
