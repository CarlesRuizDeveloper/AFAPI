<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ManagerService;

class ManagerController extends Controller
{
    protected $managerService;

    // Inyectamos el servicio en el controlador
    public function __construct(ManagerService $managerService)
    {
        $this->managerService = $managerService;
    }

    public function crearUsuarioAfa(Request $request)
    {
        // Pasamos los datos al servicio
        return $this->managerService->crearUsuarioAfa($request->all());
    }
}
