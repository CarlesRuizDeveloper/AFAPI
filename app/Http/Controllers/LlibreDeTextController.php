<?php

namespace App\Http\Controllers;

use App\Services\LlibreDeTextService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LlibreDeTextController extends Controller
{
    protected $llibreService;

    public function __construct(LlibreDeTextService $llibreService)
    {
        $this->llibreService = $llibreService;
    }

    public function index()
    {
        try {
            $llibres = $this->llibreService->getAll();
            return response()->json($llibres, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'titol' => 'required|string|max:255',
                'curs' => 'required|string|max:255',
                'editorial' => 'required|string|max:255',
                'observacions' => 'nullable|string',
                'category_id' => 'required|exists:categories,id', 
            ]);

            $llibre = $this->llibreService->create($request->all(), auth()->id());

            return response()->json(['message' => 'Llibre creat correctament', 'data' => $llibre], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'titol' => 'required|string|max:255',
                'editorial' => 'required|string|max:255',
                'observacions' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
            ]);

            $llibre = $this->llibreService->update($id, $request->all());
            return response()->json(['message' => 'Llibre actualitzat correctament', 'data' => $llibre], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->llibreService->delete($id);
            return response()->json(['message' => 'Llibre eliminat correctament'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $llibre = $this->llibreService->getByIdWithUser($id);
            return response()->json($llibre, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
