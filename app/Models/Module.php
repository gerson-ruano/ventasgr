<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use App\Models\Module;

class Module extends Model
{
    //use HasFactory;
    protected $fillable = ['name', 'description', 'active'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'module_permission');
    }

    // Método para asignar permisos a un módulo
    public function assignPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }
        return $this->permissions()->syncWithoutDetaching($permission);
    }

}
