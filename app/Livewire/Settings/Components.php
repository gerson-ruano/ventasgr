<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting;

class Components extends Component
{
    public $settings = [];
    public $originalSettings = [];

    public function mount()
    {
        $this->settings = Setting::pluck('value', 'key')->toArray();
        $this->originalSettings = $this->settings;
    }

    public function resetToOriginal()
    {
        $this->settings = $this->originalSettings;
        $this->dispatch('showNotification', 'Cambios descartados', 'info');
    }

    public function updatedSettings($value, $key)
    {
        //Setting::set($key, $value);
        //session()->flash('message', 'Configuración guardada.');
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        $this->dispatch('showNotification', 'act ' . $key . ' actualizado exitosamente', 'info');
    }

    public function save()
    {
        foreach($this->settings as $key => $value) {
            Setting::updateOrCreate(['key'=>$key], ['value'=>$value]);
        }
        // actualizar snapshot
        $this->originalSettings = $this->settings;
        $this->dispatch('showNotification', 'Configuración guardada', 'success');
    }


    public function render()
    {
        $meta = Setting::all();
        return view('livewire.settings.components', compact('meta'))
            ->extends('layouts.app')
            ->section('content');
    }

}
