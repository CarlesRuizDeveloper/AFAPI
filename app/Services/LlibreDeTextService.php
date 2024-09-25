<?php

namespace App\Services;

use App\Models\LlibreDeText;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class LlibreDeTextService
{
    public function getAll()
    {
        try {
            return LlibreDeText::with('user', 'category')->get(); 
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener los libros');
        }
    }

    public function getByIdWithUser($id)
    {
        try {
            return LlibreDeText::with('user', 'category')->findOrFail($id); 
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Libro no encontrado');
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener el libro');
        }
    }

    public function create($data, $userId)
    {
        try {
            $data['user_id'] = $userId;  
            return LlibreDeText::create($data);
        } catch (\Exception $e) {
            throw new \Exception('Error al crear el libro');
        }
    }

    public function update($id, $data)
    {
        try {
            $llibre = LlibreDeText::findOrFail($id);
            $llibre->update($data);
            return $llibre;
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Libro no encontrado');
        } catch (\Exception $e) {
            throw new \Exception('Error al actualizar el libro');
        }
    }

    public function delete($id)
    {
        try {
            $llibre = LlibreDeText::findOrFail($id);
            $llibre->delete();
            return $llibre;
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Libro no encontrado');
        } catch (\Exception $e) {
            throw new \Exception('Error al eliminar el libro');
        }
    }
}
