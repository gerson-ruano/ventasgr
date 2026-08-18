<livewire:components.notification/>
@livewireScripts
<script>

    document.addEventListener('DOMContentLoaded', function () {
        // Escuchar el evento `Eliminar` con confirmación
        window.Confirm = function (id, entityName, name, products = 0, exist = 0,) {
            if (products > 0) {
                Swal.fire({
                    title: `No se puede eliminar '${name}' de ${entityName}?`,
                    text: `Tiene '${exist}' existentes `,
                    icon: 'warning'
                });
                return;
            }

            Swal.fire({
                title: `¿Estás seguro de eliminar '${name}' de ${entityName}?`,
                text: "¡No podrás revertir esto!",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#a10c10",
                cancelButtonColor: "#727885",
                confirmButtonText: "Sí, eliminarla!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteRow', {
                        id: id
                    });
                    /*Swal.fire({
                        //position: "top-end",
                        icon: "success",
                        title: `La categoría "${categoryname}" ha sido eliminada?`,
                        showConfirmButton: false,
                        timer: 1500
                    });*/
                }
            });
        }
        // Escuchar el evento `added`
        Livewire.on('noty-added', (data) => {
            //console.log('Event Data:', data);
            if (data && data.type && data.name) {
                Swal.fire({
                    icon: "success",
                    iconColor: "#0ca152", //color verde oscuro
                    title: `se agrego ${data.type} "${data.name}" con exito!`,
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                console.error(
                    'Los datos recibidos del evento Livewire "noty-added" no tienen el formato esperado.',
                    data);
            }
        });

        // Escuchar el evento `updated`
        Livewire.on('noty-updated', (data) => {
            if (data && data.type && data.name) {
                Swal.fire({
                    icon: "success",
                    iconColor: "#0c4ca1", //color Azul oscuro
                    title: `se actualizó ${data.type} "${data.name}" con exito!`,
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                console.error(
                    'Los datos recibidos del evento Livewire "noty-updated" no tienen el formato esperado.',
                    data);
            }
        });

        //Escuchar el evento `deleted`
        Livewire.on('noty-deleted', (data) => {
            if (data && data.type && data.name) {
                Swal.fire({
                    icon: "error",
                    title: `se eliminó ${data.type} "${data.name}" exitosamente!`,
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                console.error(
                    'Los datos recibidos del evento Livewire "noty-deleted" no tienen el formato esperado.',
                    data);
            }
        });

        // Escuchar el evento `not-found`
        Livewire.on('noty-not-found', (data) => {
            if (data && data.type && data.name) {
                Swal.fire({
                    icon: "error",
                    title: `la ${data.type} con "${data.id}" no se encuentra!`,
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                console.error(
                    'Los datos recibidos del evento Livewire "noty-not-found" no tienen el formato esperado.',
                    data);
            }
        });

        // Escuchar el evento `not-error`
        Livewire.on('noty-error', (data) => {
            if (data && data.type && data.name) {
                Swal.fire({
                    icon: "error",
                    title: `la ${data.type} con "${data.name}" no se encuentra!`,
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                console.error(
                    'Los datos recibidos del evento Livewire "noty-error" no tienen el formato esperado.',
                    data);
            }
        });

        // Escuchar el evento `not-permission`
        Livewire.on('noty-permission', (data) => {
            if (data && data.type && data.name && data.permission) {
                Swal.fire({
                    icon: "error",
                    title: `${data.type} no tiene "${data.name}" para "${data.permission}"`,
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                console.error(
                    'Los datos recibidos del evento Livewire "noty-error" no tienen el formato esperado.',
                    data);
            }
        });

        // Escuchar el evento `not-api-error` muestra los errores de API FACTUS
        Livewire.on('noty-api-error', (data) => {
            mostrarMensajeError(data);
        });

        function mostrarMensajeError(data) {
            if (data && data.type && data.name && data.details) {
                Swal.fire({
                    icon: "error",
                    width: '900px',
                    title: `${data.type} "${data.name}" => "${data.details}"`,
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Intentar nuevamente',
                    // Otras opciones de personalización de SweetAlert2
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Lógica a ejecutar si se hace clic en "Intentar nuevamente"
                        // Por ejemplo, reintentar la acción que causó el error
                    }
                });
            } else {
                console.error(
                    'Los datos recibidos del evento Livewire "noty-api-error" no tienen el formato esperado.',
                    data);
            }
        }

        //Confirmar la sincronizacion de todos los permisos ASIGNAR
        Livewire.on('confirmSyncAll', (data) => {
            //console.log('Event Data:', data);
            if (data && data.type && data.name) {
                Swal.fire({
                    title: `¿Estás seguro de '${data.type}' todos los '${data.name}'?`,
                    text: "¡Podras revertir mas adelante!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#a10c10',
                    cancelButtonColor: '#727885',
                    confirmButtonText: 'Sí, sincronizar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('syncAllConfirmed');
                    }
                });
            } else {
                console.error(
                    'Los datos recibidos del evento Livewire "sincronizar todos permisos" no tienen el formato esperado.',
                    data);
            }
        });

        //Confirmar la Eliminacion de todos los productos del carrito
        Livewire.on('confirmClearCart', (data) => {
            //console.log('Event Data:', data);
            if (data && data.type && data.name) {
                Swal.fire({
                    title: `¿Estás seguro de '${data.type}' todos los '${data.name}'?`,
                    text: "¡Podras revertir mas adelante!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#a10c10',
                    cancelButtonColor: '#727885',
                    confirmButtonText: 'Sí, Eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteAllConfirmed');
                    }
                });
            } else {
                console.error(
                    'Los datos recibidos del evento Livewire "sincronizar todos permisos" no tienen el formato esperado.',
                    data);
            }
        });


        //Notifiaciones SWIFTALERT SUCCESS
        Livewire.on('noty-done', (event) => {
            const {type, message} = event;

            // Determinar el título y el ícono en función del tipo
            let title;
            let icon;

            switch (type) {
                case 'success':
                    title = '¡Éxito!';
                    icon = 'success';
                    break;
                case 'error':
                    title = '¡Error!';
                    icon = 'error';
                    break;
                case 'info':
                    title = 'Info';
                    icon = 'info';
                    break;
                case 'warning':
                    title = 'Advertencia';
                    icon = 'warning';
                    break;
                default:
                    title = 'Notificación';
                    icon = 'question';
                    break;
            }

            // Mostrar la alerta
            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                },
                willClose: () => {
                    // Callback opcional al cerrar la alerta
                }
            });
        });

        //Notificaciones informativas INFERIOR DERECHO
        let hideNotificationTimeout;
        Livewire.on('notification-auto-hide', event => {
            if (hideNotificationTimeout) {
                clearTimeout(hideNotificationTimeout);
            }
            hideNotificationTimeout = setTimeout(() => {
                Livewire.dispatch('closeNotification');
            }, 3000); // Ajusta el tiempo de espera en milisegundos
        });


        //Eventos para el Menu de Modulos
        const detailsList = document.querySelectorAll('details');
        // Cerrar detalles cuando se hace clic fuera de ellos
        document.addEventListener('click', function (event) {
            detailsList.forEach(function (details) {
                if (details.open && !details.contains(event.target)) {
                    details.open = false;
                }
            });
        });
        // Asegurarse de que solo un <details> se abre a la vez
        detailsList.forEach(function (details) {
            details.addEventListener('toggle', function () {
                if (details.open) {
                    detailsList.forEach(function (otherDetails) {
                        if (otherDetails !== details && otherDetails.open) {
                            otherDetails.open = false;
                        }
                    });
                }
            });
        });
    });

    //Define la forma de vista de impresion PDF de navegador
    function openPdfWindow(url) {
        var width = 800;
        var height = 700;
        var left = (screen.width - width) / 2;
        var top = (screen.height - height) / 2;

        window.open(url, 'PDF', 'width=' + width + ', height=' + height + ', top=' + top + ', left=' + left + ', resizable=yes, scrollbars=yes');

        setTimeout(function () {
            Livewire.dispatch('closeModal');  // Emite el evento de cierre del modal
        }, 100);  // Espera 500 ms para asegurarse de que el PDF se ha abierto primero
    }

    // ============================================================
    // PDF TÉRMICO
    // Ticket de 80 mm
    // ============================================================

    function openThermalPdfWindow(url) {

        /*
         * Para un ticket de 80 mm no necesitamos
         * una ventana de 800 px.
         */
        var width = 340;
        var height = 650;

        var left = (screen.width - width) / 2;
        var top = (screen.height - height) / 2;

        window.open(
            url,
            'TicketVenta',
            'width=' + width +
            ',height=' + height +
            ',top=' + top +
            ',left=' + left +
            ',resizable=yes' +
            ',scrollbars=yes'
        );
    }


    // ============================================================
    // IMPRESIÓN POST-VENTA
    // ============================================================

    Livewire.on('printSale', function (event) {

        /*
         * Compatible con:
         *
         * $this->dispatch('printSale', url: $url);
         *
         * y también con la forma anterior.
         */
        var url =
            event?.url ??
            event?.[0]?.url ??
            event;

        if (!url) {

            console.error(
                'No se recibió la URL del ticket.'
            );

            Livewire.dispatch('ShowNotification');

            return;
        }


        /*
         * No es necesario esperar 2 segundos.
         */
        setTimeout(function () {

            openThermalPdfWindow(url);

        }, 300);

    });

    // Manejar el evento de impresión POST-VENTA
    /*Livewire.on('printSale', function (url) {
        if (url) {

            var width = 800;
            var height = 700;
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;

            setTimeout(function () {
                //window.open(url, '_blank'); // Abrir en una nueva pestaña
                window.open(url, 'PDF', 'width=' + width + ', height=' + height + ', top=' + top + ', left=' + left + ', resizable=yes, scrollbars=yes');
            }, 2000); // 2000 milisegundos de retraso

        } else {
            Livewire.dispatch('ShowNotification');
            //alert('No se pudo generar la URL de impresión.');
        }
    });*/

    // Manejar el evento de cambio de TEMA
    document.addEventListener('DOMContentLoaded', function () {
        const html = document.documentElement;
        const themeToggle = document.getElementById('themeToggle');

        if (themeToggle) {
            themeToggle.checked = html.classList.contains('dark');

            themeToggle.addEventListener('change', function () {
                const isDark = this.checked;
                const newTheme = isDark ? 'dark' : 'light';

                html.setAttribute('data-theme', newTheme);
                html.classList.toggle('dark', isDark);
                localStorage.setItem('theme', newTheme);

                // Guardar en base de datos
                fetch("{{ route('user.update-theme') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({tema: isDark ? 0 : 1}) // dark=0, light=1
                }).then(() => {
                    console.log('Tema actualizado en base de datos');
                });
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        @auth
        const userTheme = {{ auth()->user()->tema }}; // 0 = dark, 1 = light
        const newTheme = userTheme === 0 ? 'dark' : 'light';
        localStorage.setItem('theme', newTheme);
        @else
        localStorage.removeItem('theme'); // Por default será light
        @endauth
    });
</script>
