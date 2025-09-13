<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\User;
use App\Models\Sale;
use App\Models\SaleDetail;
use Livewire\WithPagination;
use Carbon\Carbon;

class Reports extends Component
{

    use WithPagination;

    public $details, $sumDetails, $countDetails, $selected_id = 2,
        $reportType, $userId, $dateFrom, $dateTo, $saleId, $selectTipoEstado;

    private $pagination = 10;
    private $data = [];
    public $selectedId;
    public $valoresReporte = [];
    public $valoresPago = [];
    public $isModalOpen = false;
    public $currentModal = '';
    public $selectedStatus;
    public $currency = '';

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }

    public function updating($selectTipoEstado)
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->data = [];
        $this->details = [];
        $this->sumDetails = 0;
        $this->countDetails = 0;
        $this->reportType = 0;
        $this->selectTipoEstado = 0;
        $this->userId = 0;
        $this->saleId = 0;
        $this->valoresReporte = $this->tipoReporte();
        $this->valoresPago = $this->tipoPago();
        $this->currency = setting('app_currency', 'Q');

    }

    public function render()
    {
        $this->SalesByDate();

        return view('livewire.reports.components', [
            'users' => User::orderBy('name', 'asc')->get(),
            'data' => $this->data,
        ])
            ->extends('layouts.app')
            ->section('content');
    }

    public function openModal($modal)
    {
        $this->isModalOpen = true;
        $this->currentModal = $modal;
    }

    #[On('noty-done')]
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->currentModal = '';
    }

    public function SalesByDate()
    {

        if ($this->reportType == 1 && ($this->dateFrom == '' || $this->dateTo == '')) {
            return;
        }

        if ($this->reportType == 0)  //VENTAS DEL DIA
        {
            //$from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';
            //$to = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 23:59:59';
            // Asignar las fechas actuales a las propiedades dateFrom y dateTo
            $this->dateFrom = Carbon::now()->format('Y-m-d');
            $this->dateTo = Carbon::now()->format('Y-m-d');

            // Formatear las fechas para la consulta
            $from = $this->dateFrom . ' 00:00:00';
            $to = $this->dateTo . ' 23:59:59';

        } else {
            //$this->resetUI();
            $from = Carbon::parse($this->dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($this->dateTo)->format('Y-m-d') . ' 23:59:59';
        }

        $query = Sale::join('users as u', 'u.id', 'sales.user_id')
            ->select('sales.*', 'u.name as user')
            ->whereBetween('sales.created_at', [$from, $to]);

        if ($this->userId != 0) {
            $query->where('user_id', $this->userId);
        }

        if ($this->selectTipoEstado != 0) { // Si no es igual a 0, aplica el filtro
            $query->where('sales.status', $this->selectTipoEstado);
        }

        if ($this->userId == 0) {

            $this->data = Sale::join('users as u', 'u.id', 'sales.user_id')
                ->select('sales.*', 'u.name as user')
                ->whereBetween('sales.created_at', [$from, $to])
                ->get();

        } else {
            $this->data = Sale::join('users as u', 'u.id', 'sales.user_id')
                ->select('sales.*', 'u.name as user')
                ->whereBetween('sales.created_at', [$from, $to])
                ->where('user_id', $this->userId)
                ->get();
        }

        $this->data = $query->paginate($this->pagination);

    }

    public function getDetails($saleId, $modal = 'detail')
    {
        try {
            $this->authorize('reports.details', $saleId);
            $this->details = SaleDetail::join('products as p', 'p.id', 'sale_details.product_id')
                ->select('sale_details.id', 'sale_details.price', 'sale_details.quantity', 'p.name as product')
                ->where('sale_details.sale_id', $saleId)
                ->get();

            $suma = $this->details->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $this->sumDetails = $suma;

            $this->countDetails = $this->details->sum('quantity');
            //dd($this->countDetails);

            $this->saleId = $saleId;
            $this->openModal($modal);
        } catch (\Illuminate\Auth\Access\AuthorizationException $exception) {
            // Notificación de error de autorización
            $this->dispatch('noty-permission', type: Auth::user()->name, name: 'PERMISOS', permission: 'DETALLES');
        }
    }

    public function tipoPago()
    {
        return collect(Sale::STATUS_VALUES)->map(function ($name, $id) {
            return (object)[
                'id' => $id,
                'name' => $name,
            ];
        })->values()->toArray();
    }


    public function tipoReporte()
    {
        $reportTypes = [
            (object)['id' => '0', 'name' => 'VENTAS DE DIA'],
            (object)['id' => '1', 'name' => 'VENTAS POR FECHAS'],
        ];

        return $reportTypes;
    }

    public function edit($id, $modal = 'edit')
    {
        // Almacena el ID en la propiedad
        $this->selectedId = $id;

        // Emite un evento para mostrar el modal u otra lógica que tengas
        $sale = Sale::find($id);
        if ($sale) {
            $this->selectedStatus = $sale->status; // Guarda el estado actual
            //$this->valoresPago = $this->opcionesBase[$sale->status] ?? [];
            $this->openModal($modal);
        } else {
            $this->dispatch('showNotification', 'No se encontró la venta', 'dark');
        }
    }

    public function update()
    {
        try {
            $this->validate([
                'selectedStatus' => 'required|not_in:Elegir', // Asegúrate de que 'Elegir' sea el valor por defecto
            ]);
            $this->authorize('reports.update', $this->selected_id);

            // Obtén la venta correspondiente por su ID
            $sale = Sale::find($this->selectedId);
            //dd($sale);

            // Verifica si se encontró la venta
            if ($sale) {

                // Verifica si el estado es el mismo que el actual
                if ($sale->status == $this->selectedStatus) {
                    // Envía mensaje si el estado no ha cambiado
                    $this->dispatch('noty-done', type: 'info', message: 'Venta sin Modificar');
                    return;
                }
                // Asigna el nuevo estado al modelo de venta
                $sale->status = $this->selectedStatus; // Asumiendo que 'type' contiene el nuevo estado
                $sale->updated_at = now();   //Fecha de actualizacion
                $sale->mod_id = auth()->user()->id; //Nombre del usuario o id de quien lo actualizo

                // Guarda el cambio en la base de datos
                $sale->save();

                // Puedes emitir un evento o realizar alguna acción adicional si es necesario
                $this->resetUI();
                $this->dispatch('noty-done', type: 'success', message: 'Venta actualizada con éxito');
            } else {
                $this->dispatch('showNotification', 'Debe seleccionar un Tipo de ESTADO', 'dark');
                return;
                // Manejo si la venta no existe
                // Puedes emitir un mensaje de error o realizar alguna acción apropiada
            }
        } catch (\Illuminate\Auth\Access\AuthorizationException $exception) {
            // Notificación de error de autorización
            $this->dispatch('noty-permission', type: 'USUARIO', name: 'PERMISOS', permission: 'ACTUALIZAR');
        }
    }

    public function resetUI()
    {
        $this->type = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        //$this->value = '';
        $this->resetErrorBag();
    }

    protected $listeners = [
        'closeModal' => 'closeModal'
    ];

}
