<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\User;
use App\Models\Company;
use Exception;
use App\Models\Denomination;
use App\Models\Product;
use App\Models\SaleDetail;
use DB;
use Gloudemans\Shoppingcart\Facades\Cart;

class Pos extends Component
{
    public $totalPrice, $itemsQuantity, $change, $tipoPago, $vendedorSeleccionado;
    public $customer_name, $customer_nit, $customer_method_page, $customer_ref_page, $customer_address;
    public $customer_data = [];
    public $vendedores = [];
    public $valores = [];
    public $pagos = [];
    public $quantityInputs = [];
    public $efectivo = 0.00;
    public $discount = 0.00;
    public $totalTaxes = 0.00;
    public $revisionVenta = false;
    public $nextSaleNumber;
    public $empresa;
    public $isModalOpen = false;
    public $currency = ''; // Moneda por defecto


    public function mount()
    {
        $this->efectivo = 0000.00;
        $this->change = 0.00;
        $this->tipoPago = null;
        $this->updateTotalPrice();
        $this->itemsQuantity = Cart::count(); //cantidad de articulos en el carrito
        $this->vendedores = $this->ListaVendedores();
        $this->valores = $this->ListaPagos();
        $this->pagos = $this->MetodosPagos();
        $this->updateQuantityProducts();
        $this->getNextSaleNumber();
        $this->empresa = $this->companyVentas();
        $this->currency = setting('app_currency', 'Q'); // Obtener la moneda configurada
    }

    protected $rules = [
        'customer_name' => 'required|min:2',
        'customer_nit' => 'nullable|min:8',
        'customer_method_page' => 'required',
        'customer_ref_page' => 'nullable|max:50',
        'customer_address' => 'nullable|max:30',
    ];

    protected $messages = [
        'customer_name.required' => 'Nombre del cliente es requerido',
        'customer_name.min' => 'Debe tener al menos 2 caracteres',
        'customer_method_page.required' => 'Metodo de pago es requerido',
        'customer_nit.min' => 'NIT debe tener al menos 8 numeros',
        'customer_ref_page.max' => 'El campo referencia de pago debe tener como maximo 30 caracteres',
        'customer_address.max' => 'El campo direcciòn debe tener como maximo 50 caracteres',
    ];


    public function render()
    {
        $this->updateTaxes();
        return view('livewire.pos.components', [
            'denominations' => Denomination::orderBy('value', 'desc')->get(),
            'cart' => Cart::content(),
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
        $this->resetValidation();
    }

    public function saveCustomer()
    {
        $this->validate();

        // Guardar en el array temporal
        $this->customer_data = [
            'name' => $this->customer_name,
            'nit' => $this->customer_nit,
            'method_page' => $this->customer_method_page,
            'ref_page' => $this->customer_ref_page,
            'address' => $this->customer_address,
        ];
        $this->isModalOpen = false;
        $this->dispatch('showNotification', 'Cliente ' . $this->customer_name . ' agregado exitosamente', 'success');
    }

    public function updateCustomer()
    {
        $this->validate();

        if ($this->customer_data) {
            if (!empty($this->customer_name)) {
                $this->customer_data['name'] = $this->customer_name;
            }
            if (!empty($this->customer_nit)) {
                $this->customer_data['nit'] = $this->customer_nit;
            }
            if (!empty($this->customer_method_page)) {
                $this->customer_data['method_page'] = $this->customer_method_page;
            }
            if (!empty($this->customer_ref_page)) {
                $this->customer_data['ref_page'] = $this->customer_ref_page;
            }
            if (!empty($this->customer_address)) {
                $this->customer_data['address'] = $this->customer_address;
            }
        }

        $this->isModalOpen = false;
        $this->dispatch('showNotification', 'Cliente ' . $this->customer_name . ' actualizado exitosamente', 'info');
    }

    public function deleteCustomer()
    {
        $this->customer_data = [];
        $this->customer_name = '';
        $this->customer_nit = '';
        $this->customer_method_page = '';
        $this->customer_ref_page = '';
        $this->customer_address = '';

        //$this->dispatch('showNotification', 'Cliente ' . $this->customer_name . ' eliminado exitosamente', 'error');
    }

    public function ListaPagos()
    {
        $reportTypes = [
            (object)['id' => '1', 'name' => 'PAGADO'],
            (object)['id' => '2', 'name' => 'PENDIENTE'],
            //(object)['id' => '3', 'name' => 'ANULADO'],
        ];
        return $reportTypes;
    }

    public function MetodosPagos()
    {
        $reportTypes = [
            (object)['id' => '1', 'name' => 'Efectivo'],
            (object)['id' => '2', 'name' => 'Transferencia'],
            (object)['id' => '3', 'name' => 'Deposito'],
            (object)['id' => '4', 'name' => 'Tarjeta Credito'],
            //(object)['id' => '3', 'name' => 'ANULADO'],
        ];
        return $reportTypes;
    }

    public function translateTipoPago($tipoPago)
    {
        $traslation = [
            'PAGADO' => 'PAID',
            'PENDIENTE' => 'PENDING',
            'CANCELADO' => 'CANCELLED',
        ];
        return $traslation[$tipoPago] ?? $tipoPago;
    }

    public function obtenerTipoPago($tipoPagoId) //Obtiene el 'TIPO DE PAGO' y lo muestra en la vista de IMPRESION
    {
        $reportTypes = $this->ListaPagos();

        foreach ($reportTypes as $reportType) {
            if ($reportType->id == $tipoPagoId) {
                return $reportType->name;
            }
        }

        return 'Tipo de pago no encontrado';
    }

    public function obtenerMetodoPago($tipoPagoId)
    {
        foreach ($this->MetodosPagos() as $tipoPago) {
            if ($tipoPago->id == $tipoPagoId) {
                return $tipoPago->name;
            }
        }
        return 'N/A';
    }

    public function ListaVendedores()
    {
        $sellerProfiles = User::where('profile', 'seller')
            ->where('status', 'Active') // Filtra solo los USUARIOS ACTIVOS EN EL SISTEMA
            ->pluck('name', 'id')
            ->map(function ($name, $id) {
                return (object)['id' => $id, 'name' => $name];
            })
            ->values()
            ->toArray();

        return $sellerProfiles;
    }

    public function clearChange()
    {
        $this->efectivo = 0;
        $this->change = 0;
    }

    public function ACash($value)
    {
        if ($value == 0) {
            $value = $this->totalPrice - $this->efectivo;
        }
        $this->efectivo += (float)$value;
        $this->change = ($this->efectivo - $this->totalPrice);
    }

    public function getNextSaleNumber()
    {
        $lastSale = Sale::latest('id')->first();    // Obtener el último número de venta
        $lastSaleNumber = $lastSale ? $lastSale->id : 0;
        $this->nextSaleNumber = $lastSaleNumber + 1;   // Incrementar el número para la próxima venta
    }

    public function companyVentas() // Devuelve la Compañia actual #1
    {
        $empresa = Company::first();

        if (!$empresa) {
            abort(404, 'No se encontró ninguna compañía');
        }
        return $empresa;
    }

    public function calculateGlobalTax()
    {
        $totalQuantity = 0;
        $totalSale = 0;

        foreach (Cart::content() as $item) {
            $totalQuantity += $item->qty; // Contar la cantidad total de productos
            $totalSale += $item->price * $item->qty; // Calcular el total de la venta
        }

        $generalTaxRatePercent = setting('pos_tax_rate', 0.12); // Impuesto general del 12% (puedes cambiarlo)
        $generalTaxRate = $generalTaxRatePercent / 100;
        $totalTaxes = $totalSale * $generalTaxRate; // Calcular el total de impuestos

        return [
            'totalTaxes' => round($totalTaxes, 2), //number_format($totalTaxes, 2
            'generalTaxRate' => $generalTaxRate * 100, // Guardar el porcentaje para mostrar
            'totalQuantity' => $totalQuantity, // Cantidad total de productos
        ];
    }


    public function updateTaxes()
    {
        $taxData = $this->calculateGlobalTax();
        $this->totalTaxes = $taxData['totalTaxes'];
    }


    protected
        $listeners = [
        'scan-code' => 'scanCode',
        'deleteRow' => 'removeItem',
        'deleteAllConfirmed' => 'deleteAllConfirmedCart',
        'clearChange' => 'clearChange',
        'cartUpdated' => 'updateTaxes',
        'printSaleAfterDelay' => 'printSale'
    ];

    public function scanCode($barcode, $cant = 1)
    {
        // Si $barcode viene como array (porque dispatch manda un objeto JS)
        if (is_array($barcode) && isset($barcode['barcode'])) {
            $barcode = trim($barcode['barcode']);
        } else {
            $barcode = trim($barcode);
        }

        $product = Product::where('barcode', $barcode)->first();

        if (!$product) {
            $this->dispatch('showNotification', 'El producto con código ' . $barcode . ' no existe o aún no está registrado', 'dark');
            $this->search = '';
            return;
        }

        $cartItem = $this->getCartItem($product->id);

        if ($cartItem) {
            $this->increaseQty($product->id, $cant);
            return;
        }

        if ($product->stock < $cant) {
            $this->dispatch('showNotification', 'Stock insuficiente para realizar la operación', 'warning');
            return;
        }

        Cart::add($product->id, $product->name, $cant, $product->price, ['image' => $product->image]);
        $this->updateCartSummary();
        $this->updateTotalPrice();
        $this->dispatch('showNotification', 'Producto ' . $product->name . ' agregado exitosamente', 'success');
        $this->dispatch('cartUpdated');

    }

    public function updateCartSummary()
    {
        // Actualiza el precio total antes de cualquier cálculo
        $this->updateTotalPrice();

        // Actualiza la cantidad total de artículos en el carrito
        $this->itemsQuantity = Cart::count();

        // Actualiza la cantidad de cada producto en el carrito
        $this->updateQuantityProducts();

        // Calcula el cambio
        $this->change = (float)$this->efectivo - (float)$this->totalPrice;

        $this->dispatch('cartUpdated');

    }

    public function updateQuantityProducts()
    {
        foreach (Cart::content() as $item) {
            $this->quantityInputs[$item->id] = $item->qty;
        }
        return $this->totalPrice;
    }

    public function updateTotalPrice()
    {
        $this->totalPrice = 0; // Reset the total price before calculation

        foreach (Cart::content() as $item) {
            $this->totalPrice += $item->price * $item->qty;
        }

        $discountRatePercent = min(max(setting('pos_discount_rate', 0.00), 0), 100);
        $discountRate = $discountRatePercent / 100;
        $this->discount = round($this->totalPrice * $discountRate, 2);
        //dd($this->discount);
        $this->totalPrice -= $this->discount;
    }

    protected function getCartItem($productId)
    {
        $cartItem = Cart::search(function ($cartItem, $rowId) use ($productId) {
            return $cartItem->id === $productId;
        })->first();
        if (!$cartItem) {
            $this->dispatch('showNotification', 'Producto no encontrado en el carrito', 'warning');
            return null;
        }
        return $cartItem;
    }

    public function increaseQty($productId, $cant = 1)
    {
        //dd($productId);
        $product = Product::find($productId);
        $cartItem = $this->getCartItem($productId);
        if (!$cartItem) return; // Termina si el producto no fue encontrado

        if ($cartItem) {
            $newQty = $cartItem->qty + $cant;
            if ($product->stock < $newQty) {
                $this->dispatch('showNotification', 'Stock insuficiente para realizar la operación', 'warning');
                return;
            }
            Cart::update($cartItem->rowId, $newQty);
            $this->dispatch('showNotification', 'Cantidad de productos ' . $cartItem->name . ' actualizada', 'info');
        } else {
            Cart::add($product->id, $product->name, $cant, $product->price, ['image' => $product->image]);
            $this->dispatch('showNotification', 'Producto ' . $cartItem->name . ' agregado Exitosamente', 'success');
        }
        $this->updateCartSummary();
        $this->updateTotalPrice();
    }

    public function decreaseQty($productId, $cant = 1)
    {
        $cartItem = $this->getCartItem($productId);
        if (!$cartItem) return; // Termina si el producto no fue encontrado

        // Eliminar el producto del carrito si la cantidad es 0 o menos
        if ($cartItem->qty <= $cant) {
            Cart::remove($cartItem->rowId);
            $this->dispatch('showNotification', 'Producto ' . $cartItem->name . ' fue eliminado del carrito', 'error');
        } else {
            $newQty = $cartItem->qty - 1;
            // Actualizar la cantidad del producto en el carrito
            if (isset($cartItem->attributes[0])) {
                Cart::update($cartItem->rowId, $newQty, $cartItem->attributes[0]);
            } else {
                Cart::update($cartItem->rowId, $newQty);
            }
            $this->dispatch('showNotification', 'Cantidad de productos ' . $cartItem->name . ' actualizada', 'info');
        }

        $this->updateCartSummary(); // Actualizar resumen del carrito
        $this->updateTotalPrice();

    }

    public function updateQty($id, $newQty)
    {
        $productId = (int)$id;

        $cartItem = $this->getCartItem($productId);
        if (!$cartItem) return; // Termina si el producto no fue encontrado

        if ($newQty <= 0) {
            Cart::remove($cartItem->rowId);
            $this->dispatch('showNotification', 'Producto eliminado del carrito', 'error');
        } else {
            Cart::update($cartItem->rowId, $newQty);
            $this->dispatch('showNotification', 'Cantidad actualizada Exitosamente', 'info');
        }

        $this->updateCartSummary();
        $this->updateTotalPrice();

    }

    public function removeItem($id)
    {
        // Buscar el producto en el carrito por su ID
        $productId = (int)$id; // Asumiendo que $id es el ID del producto que quieres eliminar

        $cartItem = $this->getCartItem($productId);
        if (!$cartItem) return; // Termina si el producto no fue encontrado

        if ($cartItem) {
            try {
                // Eliminar el producto del carrito utilizando su rowId
                Cart::remove($cartItem->rowId);

                // Actualizar resúmenes u otros datos necesarios
                $this->updateCartSummary();
                $this->updateTotalPrice();

                // Mostrar notificación de éxito
                $this->dispatch('cartUpdated');
                $this->dispatch('showNotification', 'Producto eliminado del carrito', 'error');
            } catch (\Exception $e) {
                // Manejar cualquier excepción que pueda ocurrir
                logger()->error("Error al eliminar producto del carrito: " . $e->getMessage());
                $this->dispatch('showNotification', 'Error al eliminar producto del carrito', 'warning');
            }
        } else {
            // Manejar el caso donde el producto no se encuentra en el carrito
            $this->dispatch('showNotification', 'El producto no está en el carrito', 'dark');
        }
    }

    public function clearCart()
    {

        //const $total = parseFloat(document.getElementById('hiddenTotal').value)
        if ($this->totalPrice <= 0) {
            $this->dispatch('showNotification', 'Agregar productos al carrito', 'warning');
            return;
        } else {
            $this->dispatch('confirmClearCart', type: 'Eliminar', name: 'PRODUCTOS');
        }

    }

    public function deleteAllConfirmedCart()
    {
        Cart::destroy();
        $this->efectivo = 0;
        $this->change = 0;
        $this->updateTotalPrice();
        $this->itemsQuantity = Cart::count();
        $this->tipoPago = 0;
        $this->vendedorSeleccionado = 0;
        $this->dispatch('showNotification', 'Se eliminaron Todos los Productos del carrito', 'error');
    }


    public function saveSale()
    {
        $taxData = $this->calculateGlobalTax();
        //dd($taxData);
        if ($this->totalPrice <= 0) {
            $this->dispatch('showNotification', 'Agregar Productos a la venta', 'dark');
            return;
        }

        if ($this->discount < 0) {
            $this->dispatch('showNotification', 'El DESCUENTO debe verificarse', 'warning');
            return;
        }
        if (isset($taxData)) {
            $this->totalTaxes = $taxData['totalTaxes'];
        } else {
            $this->dispatch('showNotification', 'No existe ningun IMPUESTO en la venta', 'warning');
            return;
        }
        if (isset($this->tipoPago) && !empty($this->tipoPago)) {
            $tipoPagoSeleccionado = $this->translateTipoPago($this->tipoPago);
        } else {
            $this->dispatch('showNotification', 'Debe seleccionar el TIPO DE PAGO que utilizará', 'warning');
            return;
        }

        if (isset($this->vendedorSeleccionado)) {
            $vendedorAgregado = $this->vendedorSeleccionado;
            if ($vendedorAgregado == 0) {
                $vendedorAgregado = '0';
            } elseif ($vendedorAgregado >= 1) {
                $this->resetUI();
            }
        } else {
            $vendedorAgregado = '0';
            //$this->dispatch('showNotification', 'Debe seleccionar un tipo de cliente o vendedor correcto', 'warning');
            //return;  //Modificado para que Guarde sin imprimir y no marque alerta de C/F
        }

        DB::beginTransaction();

        try {
            $sale = Sale::create([
                'total' => $this->totalPrice,
                'items' => $this->itemsQuantity,
                'cash' => $this->efectivo,
                'status' => $tipoPagoSeleccionado,
                'change' => $this->change,
                'seller' => $vendedorAgregado,
                'taxes' => $taxData['totalTaxes'],
                'customer_data' => [
                    'name' => $this->customer_name,
                    'nit' => $this->customer_nit,
                    'method_page' => $this->customer_method_page,
                    'ref_page' => $this->customer_ref_page,
                    'address' => $this->customer_address,
                ],
                'user_id' => Auth()->user()->id
            ]);

            if ($sale) {
                $items = Cart::content();
                foreach ($items as $item) {
                    SaleDetail::create([
                        'price' => $item->price,
                        'quantity' => $item->qty,
                        'product_id' => $item->id,
                        'sale_id' => $sale->id,
                        'discount' => $this->discount,
                    ]);

                    $product = Product::find($item->id);
                    $product->stock = $product->stock - $item->quantity;
                    $product->save();
                }

            }

            DB::commit();

            Cart::destroy();
            $this->efectivo = 0;
            $this->change = 0;
            $this->updateTotalPrice();
            $this->itemsQuantity = Cart::count();
            $this->tipoPago = 0;
            $this->vendedorSeleccionado = 0;
            $this->getNextSaleNumber();
            $this->dispatch('noty-done', type: 'success', message: 'Venta ' . $this->nextSaleNumber - 1 . ' realizada con éxito ');
            //return redirect()->to('pos');
            //$this->emit('print-ticket', $sale->id);

        } catch (Exception $e) {
            DB::rollback();
            //$this->dispatch('sale-error', $e->getMessage());
            $this->dispatch('showNotification', $e->getMessage(), 'error');
        }
    }

    public function saveSaleAndPrint()
    {
        if ($this->efectivo <= 0) {
            $this->dispatch('showNotification', 'Debes ingresar el EFECTIVO ', 'warning');
            return;
        }
        if ($this->totalPrice > $this->efectivo) {
            $this->dispatch('showNotification', 'El efectivo debe ser MAYOR o IGUAL al total', 'warning');
            return;
        }
        if ($this->tipoPago == 0 || $this->tipoPago == null) {
            $this->dispatch('showNotification', 'Debe seleccionar el ESTADO DE PAGO de la VENTA', 'warning');
            return;
        }

        $this->saveSale();
        $this->resetUI();
        $this->nextSaleNumber = Sale::latest('id')->first()->id ?? null;
        $this->dispatch('printSaleAfterDelay');
    }

    public function printSale()
    {
        // Intentamos obtener el número de la venta nuevamente
        $nextSaleNumber = $this->nextSaleNumber;
        $sale = Sale::with('details')->find($nextSaleNumber);
        //dd($sale);

        // Si la venta aún no está lista, intentamos de nuevo después de un momento
        if (!$sale) {
            sleep(2); // Esperar 2 segundos
            $sale = Sale::where('id', $nextSaleNumber)->with('details')->first();
        }

        // Si aún no se encuentra, mostramos un mensaje de error
        if (!$sale) {
            $this->dispatch('showNotification', 'La venta no se encontró. Intente nuevamente.', 'error');
            return;
        }
        //dd($sale);

        // Generar la URL del reporte
        $url = route('report.venta', [
            'change' => $sale->change,
            'efectivo' => $sale->cash,
            'seller' => getNameSeller($sale->seller),
            'nextSaleNumber' => $sale->id,
            'totalTaxes' => $sale->taxes,
            'discount' => $sale->details->first()->discount,
            'customer_data' => urlencode(json_encode($sale->customer_data))
        ]);

        // Enviamos la URL al frontend para imprimir
        $this->dispatch('printSale', $url);
    }

    public function resetUI()
    {
        $this->customer_name = '';
        $this->customer_nit = '';
        $this->customer_method_page = '';
        $this->customer_ref_page = '';
        $this->customer_address = '';
    }

    public function updatedVendedorSeleccionado($value)
    {
        $this->deleteCustomer();
    }

}
