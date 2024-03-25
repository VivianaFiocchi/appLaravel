<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function create()
    {
        // Aquí puedes retornar la vista del formulario de creación de tareas
    }

    public function store(Request $request)
    {
        // Valida y guarda la nueva tarea en la base de datos
    }

    public function destroy($id)
    {
        // Encuentra y elimina la tarea con el ID proporcionado
    }
}
