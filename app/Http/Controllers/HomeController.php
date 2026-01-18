<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use Illuminate\Support\Facades\Config;


class HomeController extends Controller
{

    public function index()
    {
        return view('livewire.pages.home.modules', [
            'modules' => getUserModules()
        ]);
    }

    public function show($module)
    {
        $modules = getUserModules();

        abort_unless(isset($modules[$module]), 404);

        return view('livewire.pages.home.menus', [
            'module' => $modules[$module],
            'children' => $modules[$module]['children'] ?? [],
        ]);
    }
}
