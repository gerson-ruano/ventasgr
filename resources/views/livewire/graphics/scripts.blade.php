<script>
    (() => {

        'use strict';

        const chartColors = {
            primary: 'rgb(45, 76, 110)',
            primaryTransparent: 'rgba(45, 76, 110, 0.12)',

            blue: 'rgb(59, 130, 246)',
            blueTransparent: 'rgba(59, 130, 246, 0.70)',

            green: 'rgb(34, 197, 94)',
            greenTransparent: 'rgba(34, 197, 94, 0.70)',

            red: 'rgb(239, 68, 68)',
            redTransparent: 'rgba(239, 68, 68, 0.70)',

            yellow: 'rgb(245, 158, 11)',
            yellowTransparent: 'rgba(245, 158, 11, 0.70)',

            purple: 'rgb(139, 92, 246)',
            purpleTransparent: 'rgba(139, 92, 246, 0.70)',

            pink: 'rgb(236, 72, 153)',
            pinkTransparent: 'rgba(236, 72, 153, 0.70)',

            cyan: 'rgb(6, 182, 212)',
            cyanTransparent: 'rgba(6, 182, 212, 0.70)'
        };


        const getDashboardData = () => {

            const element = document.getElementById('dashboard-data');

            if (!element) {
                console.error('No existe #dashboard-data');
                return {};
            }

            try {

                const data = JSON.parse(element.textContent.trim());

                console.log('DATOS DASHBOARD:', data);

                return data;

            } catch (error) {

                console.error(
                    'Error procesando dashboard-data:',
                    error
                );

                return {};
            }
        };


        const toNumber = value => {

            const number = Number(value);

            return Number.isFinite(number)
                ? number
                : 0;
        };


        const formatNumber = value => {

            return toNumber(value)
                .toLocaleString('es-GT');
        };


        const formatCurrency = value => {

            return 'Q ' +
                toNumber(value).toLocaleString(
                    'es-GT',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );
        };


        const createChart = (canvasId, config) => {

            if (!config) {
                console.warn(
                    `No existe configuración para ${canvasId}`
                );

                return;
            }

            const canvas = document.getElementById(canvasId);

            if (!canvas) {
                console.warn(
                    `No existe canvas #${canvasId}`
                );

                return;
            }


            const previousChart =
                Chart.getChart(canvas);

            if (previousChart) {
                previousChart.destroy();
            }


            new Chart(canvas, config);
        };


        // ============================================================
        // VENTAS RECIENTES
        // ============================================================

        const chartUltimosDiasConfig = data => {

            if (!Array.isArray(data) || data.length === 0) {
                return null;
            }

            return {

                type: 'line',

                data: {

                    labels: data.map(
                        item => item.date
                    ),

                    datasets: [{
                        label: 'Ventas',

                        data: data.map(
                            item => toNumber(item.sales)
                        ),

                        borderColor:
                        chartColors.primary,

                        backgroundColor:
                        chartColors.primaryTransparent,

                        fill: true,

                        tension: 0.35,

                        borderWidth: 2,

                        pointRadius: 4
                    }]
                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {
                            display: false
                        }
                    },

                    scales: {

                        y: {
                            beginAtZero: true,

                            ticks: {
                                precision: 0
                            }
                        },

                        x: {

                            grid: {
                                display: false
                            }
                        }
                    }
                }
            };
        };


        // ============================================================
        // BAJO STOCK
        // ============================================================

        const chartStockConfig = data => {

            if (!Array.isArray(data) || data.length === 0) {
                return null;
            }


            const products = [...data]
                .sort(
                    (a, b) =>
                        toNumber(a.stock) -
                        toNumber(b.stock)
                )
                .slice(0, 10);


            return {

                type: 'bar',

                data: {

                    labels: products.map(
                        product => product.name
                    ),

                    datasets: [{

                        label: 'Existencias',

                        data: products.map(
                            product =>
                                toNumber(product.stock)
                        ),

                        backgroundColor:
                        chartColors.redTransparent,

                        borderColor:
                        chartColors.red,

                        borderWidth: 1,

                        borderRadius: 5
                    }]
                },

                options: {

                    indexAxis: 'y',

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {
                            display: false
                        }
                    },

                    scales: {

                        x: {

                            beginAtZero: true,

                            ticks: {
                                precision: 0
                            }
                        },

                        y: {

                            grid: {
                                display: false
                            }
                        }
                    }
                }
            };
        };


        // ============================================================
        // PRODUCTOS MÁS VENDIDOS
        // ============================================================

        const chartProductTopConfig = data => {

            if (!Array.isArray(data) || data.length === 0) {
                return null;
            }


            const products = [...data]
                .sort(
                    (a, b) =>
                        toNumber(b.total_quantity) -
                        toNumber(a.total_quantity)
                )
                .slice(0, 10);


            return {

                type: 'bar',

                data: {

                    labels: products.map(
                        product => product.name
                    ),

                    datasets: [{

                        label: 'Unidades vendidas',

                        data: products.map(
                            product =>
                                toNumber(
                                    product.total_quantity
                                )
                        ),

                        backgroundColor:
                        chartColors.blueTransparent,

                        borderColor:
                        chartColors.blue,

                        borderWidth: 1,

                        borderRadius: 5
                    }]
                },

                options: {

                    indexAxis: 'y',

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {
                            display: false
                        }
                    },

                    scales: {

                        x: {
                            beginAtZero: true,

                            ticks: {
                                precision: 0
                            }
                        },

                        y: {

                            grid: {
                                display: false
                            }
                        }
                    }
                }
            };
        };


        // ============================================================
        // VENTAS POR USUARIO
        // ============================================================

        const chartTopUsersConfig = data => {

            if (!Array.isArray(data) || data.length === 0) {
                return null;
            }


            const users = [...data]
                .sort(
                    (a, b) =>
                        toNumber(b.sales_count) -
                        toNumber(a.sales_count)
                )
                .slice(0, 10);


            return {

                type: 'bar',

                data: {

                    labels: users.map(
                        user => user.user_name
                    ),

                    datasets: [{

                        label: 'Ventas realizadas',

                        data: users.map(
                            user =>
                                toNumber(user.sales_count)
                        ),

                        backgroundColor:
                        chartColors.purpleTransparent,

                        borderColor:
                        chartColors.purple,

                        borderWidth: 1,

                        borderRadius: 5
                    }]
                },

                options: {

                    indexAxis: 'y',

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {
                            display: false
                        }
                    },

                    scales: {

                        x: {

                            beginAtZero: true,

                            ticks: {
                                precision: 0
                            }
                        },

                        y: {

                            grid: {
                                display: false
                            }
                        }
                    }
                }
            };
        };


        // ============================================================
        // VENTAS ANUALES
        // ============================================================

        const chartTendenciaAnualConfig = data => {

            if (!Array.isArray(data) || data.length === 0) {
                return null;
            }


            return {

                type: 'line',

                data: {

                    labels: data.map(
                        item => item.month
                    ),

                    datasets: [{

                        label: 'Ventas',

                        data: data.map(
                            item => toNumber(item.total)
                        ),

                        borderColor:
                        chartColors.primary,

                        backgroundColor:
                        chartColors.primaryTransparent,

                        borderWidth: 3,

                        fill: true,

                        tension: 0.35,

                        pointRadius: 4
                    }]
                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {
                            display: false
                        }
                    },

                    scales: {

                        y: {
                            beginAtZero: true
                        },

                        x: {

                            grid: {
                                display: false
                            }
                        }
                    }
                }
            };
        };


        // ============================================================
        // RESUMEN FINANCIERO
        // ============================================================

        const chartIngresosConfig = data => {

            if (!Array.isArray(data) || data.length === 0) {
                return null;
            }


            const ganancias = toNumber(data[0]);
            const activo = toNumber(data[1]);
            const gastos = toNumber(data[2]);


            return {

                type: 'bar',

                data: {

                    labels: [
                        'Activo',
                        'Gastos',
                        'Ganancias'
                    ],

                    datasets: [{

                        data: [
                            activo,
                            gastos,
                            ganancias
                        ],

                        backgroundColor: [

                            chartColors.greenTransparent,

                            chartColors.redTransparent,

                            chartColors.blueTransparent
                        ],

                        borderColor: [

                            chartColors.green,

                            chartColors.red,

                            chartColors.blue
                        ],

                        borderWidth: 1,

                        borderRadius: 6
                    }]
                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {
                            display: false
                        },

                        tooltip: {

                            callbacks: {

                                label: context =>
                                    formatCurrency(
                                        context.raw
                                    )
                            }
                        }
                    },

                    scales: {

                        y: {

                            beginAtZero: true,

                            ticks: {

                                callback: value =>
                                    'Q ' +
                                    formatNumber(value)
                            }
                        },

                        x: {

                            grid: {
                                display: false
                            }
                        }
                    }
                }
            };
        };


        // ============================================================
// ESTADOS DE PAGO
// ============================================================

        const paymentStatusNames = {
            PAID: 'Pagado',
            PENDING: 'Pendiente',
            CANCELLED: 'Cancelado'
        };

        const formatPaymentStatus = estado => {

            if (!estado) {
                return 'Sin especificar';
            }

            const key = String(estado)
                .trim()
                .toUpperCase();

            return paymentStatusNames[key] ?? estado;
        };


        const chartTipoPagoConfig = data => {

            if (!Array.isArray(data) || data.length === 0) {
                return null;
            }

            return {

                type: 'doughnut',

                data: {

                    labels: data.map(
                        item => formatPaymentStatus(
                            item.tipo_pago
                        )
                    ),

                    datasets: [{

                        data: data.map(
                            item => toNumber(item.total)
                        ),

                        backgroundColor: [
                            chartColors.greenTransparent,
                            chartColors.yellowTransparent,
                            chartColors.redTransparent
                        ],

                        borderColor: '#ffffff',

                        borderWidth: 2
                    }]
                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    cutout: '60%',

                    plugins: {

                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            };
        };


        // ============================================================
        // INICIALIZACIÓN
        // ============================================================

        const initializeCharts = () => {

            console.log(
                'Inicializando dashboard...'
            );


            if (typeof Chart === 'undefined') {

                console.error(
                    'ERROR: Chart.js no está cargado'
                );

                return;
            }


            console.log(
                'Chart.js encontrado:',
                Chart.version
            );


            const data =
                getDashboardData();


            console.log(
                'Datos obtenidos:',
                data
            );


            createChart(
                'chartUltimosDias',
                chartUltimosDiasConfig(
                    data.salesData
                )
            );


            createChart(
                'chartStock',
                chartStockConfig(
                    data.stockProducts
                )
            );


            createChart(
                'chartProductTop',
                chartProductTopConfig(
                    data.productSales
                )
            );


            createChart(
                'chartTopUsers',
                chartTopUsersConfig(
                    data.topUsers
                )
            );


            createChart(
                'chartTendenciaAnual',
                chartTendenciaAnualConfig(
                    data.salesMonths
                )
            );


            createChart(
                'chartIngresos',
                chartIngresosConfig(
                    data.totalMoney
                )
            );


            createChart(
                'chartTipoPago',
                chartTipoPagoConfig(
                    data.ventasTipoPago
                )
            );


            console.log(
                'Dashboard inicializado'
            );
        };


        // ============================================================
        // EJECUTAR
        // ============================================================

        if (document.readyState === 'loading') {

            document.addEventListener(
                'DOMContentLoaded',
                initializeCharts
            );

        } else {

            initializeCharts();
        }


        document.addEventListener(
            'livewire:navigated',
            initializeCharts
        );

    })();
</script>
