<?php

namespace App\Services;

use App\Models\LlibreDeText;

class LlibreDeTextService
{
    public function getAll()
    {
        return LlibreDeText::all();
    }

    public function create($data)
    {
        return LlibreDeText::create($data);
    }

    public function update($id, $data)
    {
        $llibre = LlibreDeText::findOrFail($id);
        $llibre->update($data);
        return $llibre;
    }

    public function delete($id)
    {
        $llibre = LlibreDeText::findOrFail($id);
        $llibre->delete();
        return true;
    }
}
