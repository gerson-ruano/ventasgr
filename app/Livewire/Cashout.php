<?php

namespace App\Livewire;

use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Carbon\Carbon;

class Cashout extends Component
{
    use WithPagination;

    public $fromDate, $toDate, $userid, $total, $items;
    public $details = [];
    private $pagination = 10;
    private $sales = [];
    public $isModalOpen = false;
    public $saleId;

    public $currency = '';

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }

    public function mount()
    {
        $this->fromDate = null;
        $this->toDate = null;
        $this->userid = 0;
        $this->total = 0;
        $this->sales = [];
        $this->details = [];
        $this->currency = setting('app_currency', 'Q');
    }

    public function updating($fromDate, $toDate)
    {
        $this->resetPage();
    }

    public function render()
    {

        $this->Consultar();

        return view('livewire.cashout.components', [
            'users' => User::orderBy('name', 'asc')->get(),
            'sales' => $this->sales,
        ])
            ->extends('layouts.app')
            ->section('content');
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function Consultar()
    {
        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';

        $this->sales = Sale::whereBetween('created_at', [$fi, $ff])
            ->where('status', 'Paid')
            ->where('user_id', $this->userid)
            //->get();
            ->paginate($this->pagination);

        $this->total = $this->sales ? $this->sales->sum('total') : 0;
        $this->items = $this->sales ? $this->sales->sum('items') : 0;

    }

    public function viewDetails(Sale $sale)
    {
        try {
            $this->authorize('cashout.details', $sale);
            $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';
            $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';

            $this->details = Sale::join('sale_details as d', 'd.sale_id', 'sales.id')
                ->join('products as p', 'p.id', 'd.product_id')
                ->select('d.sale_id', 'p.name as product', 'd.quantity', 'd.price')
                ->whereBetween('sales.created_at', [$fi, $ff])
                ->where('sales.status', 'Paid')
                ->where('sales.user_id', $this->userid)
                ->where('sales.id', $sale->id)
                ->get();

            $this->saleId = $sale->id; // Almacena el ID de la venta

            $this->openModal();
        } catch (\Illuminate\Auth\Access\AuthorizationException $exception) {
            // Notificación de error de autorización
            $this->dispatch('noty-permission', type: Auth::user()->name, name: 'PERMISOS', permission: 'DETALLES');
        }
    }

    public function updatedToDate()
    {
        if (Carbon::parse($this->fromDate)->gt(Carbon::parse($this->toDate))) {
            $this->addError('toDateError', 'La fecha "Desde" no puede ser mayor que la fecha "Hasta".');
            $this->dispatch('showNotification', 'La fecha "Desde" no puede ser mayor que la fecha "Hasta"', 'error');
        } else {
            $this->resetErrorBag('toDateError');
        }
    }

    public function updatedFromDate()
    {
        // Verificar que 'fromDate' no sea mayor que la fecha actual
        if (Carbon::parse($this->fromDate)->gt(Carbon::parse($this->toDate))) {
            $this->dispatch('showNotification', 'La fecha "Desde" no puede ser mayor que la fecha actual.', 'error');
            $this->addError('fromDateError', 'La fecha "Desde" no puede ser mayor que la fecha actual.');
        } else {
            $this->resetErrorBag('fromDateError');
        }
    }

    protected $listeners = [
        'closeModal' => 'closeModal'
    ];

}
