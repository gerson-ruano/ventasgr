<script>
    // Elimina la línea "import Chart from 'chart.js/auto';" del principio
    // En su lugar, asegúrate que Chart.js esté cargado globalmente

    // Configuración común para todas las gráficas
    const commonChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        }
    };

    // Función para obtener datos del DOM de forma segura
    const getSafeJsonData = (elementId, attribute = 'textContent') => {
        const element = document.getElementById(elementId);
        if (!element) return null;
        try {
            return JSON.parse(attribute === 'textContent' ?
                element.textContent :
                element.getAttribute(attribute));
        } catch (e) {
            console.error(`Error parsing data for ${elementId}:`, e);
            return null;
        }
    };

    // Función para obtener datos de atributos específicos
    const getAttributeData = (elementId, attribute) => {
        const element = document.getElementById(elementId);
        if (!element) return null;
        try {
            return JSON.parse(element.getAttribute(attribute));
        } catch (e) {
            console.error(`Error parsing attribute ${attribute} for ${elementId}:`, e);
            return null;
        }
    };

    // Colores predefinidos para uso consistente
    const chartColors = {
        primary: 'rgb(45, 76, 110)',
        secondary: 'rgb(255, 99, 132)',
        success: 'rgb(75, 192, 192)',
        warning: 'rgb(255, 205, 86)',
        info: 'rgb(54, 162, 235)',
        gray: 'rgb(201, 203, 207)'
    };

    // Función para crear una gráfica
    const createChart = (canvasId, config) => {
        const canvas = document.getElementById(canvasId);
        if (!canvas) {
            console.warn(`Canvas ${canvasId} no encontrado`);
            return null;
        }
        const ctx = canvas.getContext('2d');
        if (!ctx) return null;
        return new Chart(ctx, {
            ...config,
            options: { ...commonChartOptions, ...config.options }
        });
    };

    // Función para calcular línea de tendencia
    const calculateTrendline = (x, y) => {
        if (!x || !y || x.length !== y.length || x.length === 0) return [];
        const n = x.length;
        const xySum = x.reduce((sum, xi, i) => sum + xi * y[i], 0);
        const xSum = x.reduce((sum, xi) => sum + xi, 0);
        const ySum = y.reduce((sum, yi) => sum + yi, 0);
        const xxSum = x.reduce((sum, xi) => sum + xi * xi, 0);

        const m = (n * xySum - xSum * ySum) / (n * xxSum - xSum * xSum);
        const b = (ySum - m * xSum) / n;

        return x.map(xi => m * xi + b);
    };

    // Reemplaza la función chartReportConfig con esta versión más flexible
    const chartReportConfig = (data) => {
        console.log('Datos recibidos en chartReport:', data);

        let labels = [];
        let values = [];

        // Detectar el formato de los datos
        if (Array.isArray(data)) {
            // Si es un array de objetos con name y stock
            if (data.length > 0 && data[0].hasOwnProperty('name') && data[0].hasOwnProperty('stock')) {
                labels = data.map(item => item.name);
                values = data.map(item => item.stock);
            }
            // Si es un array simple de números
            else if (data.length > 0 && typeof data[0] === 'number') {
                labels = data.map((_, index) => `Producto ${index + 1}`);
                values = data;
            }
            // Si es un array de objetos con otras propiedades
            else if (data.length > 0 && typeof data[0] === 'object') {
                // Intentar encontrar propiedades comunes
                const firstItem = data[0];
                if (firstItem.hasOwnProperty('nombre')) {
                    labels = data.map(item => item.nombre);
                    values = data.map(item => item.cantidad || item.stock || 0);
                } else {
                    // Usar índices como labels
                    labels = data.map((_, index) => `Producto ${index + 1}`);
                    values = data.map(item => Object.values(item)[0] || 0);
                }
            }
        }
        // Si es un objeto con labels y data
        else if (data && typeof data === 'object') {
            if (data.hasOwnProperty('labels') && data.hasOwnProperty('data')) {
                labels = data.labels;
                values = data.data;
            } else if (data.hasOwnProperty('labels') && data.hasOwnProperty('values')) {
                labels = data.labels;
                values = data.values;
            } else {
                // Convertir objeto a arrays
                labels = Object.keys(data);
                values = Object.values(data);
            }
        }

        // Si no hay datos, mostrar mensaje
        if (labels.length === 0 || values.length === 0) {
            console.error('No se pudieron procesar los datos para chartReport');
            return null;
        }

        return {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Stock Disponible',
                        data: values,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'PRODUCTOS EN STOCK',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad en Stock'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Productos'
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 10
                        }
                    }
                }
            }
        };
    };

    // Configuración para gráfica de stock (productos con bajo stock)
    const chartStockConfig = (data) => {
        if (!data.productosConMenosExistencias || !data.datosDeVentas) return null;

        return {
            type: 'bar',
            data: {
                labels: data.productosConMenosExistencias.map(p => p.name),
                datasets: [
                    {
                        label: 'Ventas',
                        data: data.datosDeVentas.map(v => v.total_quantity),
                        backgroundColor: chartColors.info
                    },
                    {
                        label: 'Existencias',
                        data: data.productosConMenosExistencias.map(p => p.stock),
                        backgroundColor: chartColors.secondary
                    }
                ]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'PRODUCTOS CON STOCK MINIMO'
                    }
                }
            }
        };
    };

    // Configuración para gráfica de productos más vendidos
    const chartProductTopConfig = (data) => {
        if (!data.productData) return null;

        return {
            type: 'bar',
            data: {
                labels: data.productData.map(p => p.name),
                datasets: [{
                    label: 'Cantidad Vendida',
                    data: data.productData.map(p => p.total_quantity),
                    backgroundColor: chartColors.warning
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'PRODUCTOS MÁS VENDIDOS'
                    }
                }
            }
        };
    };

    // Configuración para gráfica de ventas de usuarios
    const chartTopUsersConfig = () => {
        const userElement = document.getElementById('top-user-data');
        if (!userElement) return null;

        const userNames = userElement.getAttribute('data-user-names');
        const salesCounts = userElement.getAttribute('data-sales-counts');

        if (!userNames || !salesCounts) return null;

        return {
            type: 'bar',
            data: {
                labels: JSON.parse(userNames),
                datasets: [{
                    label: 'Ventas realizadas',
                    data: JSON.parse(salesCounts),
                    backgroundColor: chartColors.primary
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'VENTAS POR USUARIO'
                    }
                }
            }
        };
    };

    // Configuración para gráfica de ingresos
    const chartIngresosConfig = (data) => {
        if (!data.totalMoney) return null;

        return {
            type: 'doughnut',
            data: {
                labels: ['Activo', 'Gastos', 'Ganancias'],
                datasets: [{
                    data: [
                        data.totalMoney[1] || 0,
                        data.totalMoney[2] || 0,
                        data.totalMoney[0] || 0
                    ],
                    backgroundColor: [chartColors.success, chartColors.secondary, chartColors.primary]
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'DISTRIBUCIÓN DE INGRESOS'
                    }
                }
            }
        };
    };

    // Función principal para inicializar todas las gráficas
    const initializeCharts = () => {
        // Verificar que Chart.js está cargado
        if (typeof Chart === 'undefined') {
            console.error('Chart.js no está cargado. Asegúrate de incluir la librería.');
            return;
        }

        console.log('Inicializando gráficas...');

        // Obtener todos los datos necesarios
        const chartData = {
            daysOfWeek: getSafeJsonData('daysOfWeek'),
            salesData: getSafeJsonData('salesData'),
            datosDeVentas: getSafeJsonData('datosDeVentas'),
            productosConMenosExistencias: getSafeJsonData('productosConMenosExistencias'),
            salesMonths: getSafeJsonData('salesMonths'),
            totalMoney: getSafeJsonData('totalMoney'),
            totalStock: getSafeJsonData('totalStock'),
            productData: getSafeJsonData('productData'),
            ventasTipoPago: getSafeJsonData('ventasTipoPago')
        };

        console.log('Datos cargados:', chartData);

        // Crear cada gráfica solo si los datos necesarios están disponibles
        if (chartData.daysOfWeek && chartData.salesData) {
            createChart('chartUltimosDias', chartConfigs.ultimosDias(chartData));
        }

        if (chartData.datosDeVentas && chartData.productosConMenosExistencias) {
            const config = chartStockConfig(chartData);
            if (config) createChart('chartStock', config);
        }

        if (chartData.productData) {
            const config = chartProductTopConfig(chartData);
            if (config) createChart('chartProductTop', config);
        }

        // GRÁFICA 4 - PRODUCTOS EN STOCK (chartReport)
        if (chartData.totalStock) {
            console.log('Creando gráfica chartReport con datos:', chartData.totalStock);
            const config = chartReportConfig(chartData.totalStock);
            if (config) {
                createChart('chartReport', config);
            } else {
                console.error('No se pudo crear la configuración para chartReport');
            }
        } else {
            console.warn('No hay datos para chartReport (totalStock está vacío)');
        }

        // Configuración para ventas anuales
        if (chartData.salesMonths && chartData.salesMonths.length > 0) {
            createChart('chartTendenciaAnual', {
                type: 'line',
                data: {
                    labels: chartData.salesMonths.map(m => m.month),
                    datasets: [{
                        label: 'Ventas Mensuales',
                        data: chartData.salesMonths.map(m => m.total),
                        borderColor: chartColors.primary,
                        backgroundColor: chartColors.info,
                        fill: true
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'VENTAS ANUALES POR MES'
                        }
                    }
                }
            });
        }

        // Gráfica de tipo de pago
        if (chartData.ventasTipoPago && chartData.ventasTipoPago.length > 0) {
            createChart('chartTipoPago', {
                type: 'pie',
                data: {
                    labels: chartData.ventasTipoPago.map(v => v.tipo_pago),
                    datasets: [{
                        data: chartData.ventasTipoPago.map(v => v.total),
                        backgroundColor: [chartColors.primary, chartColors.success, chartColors.warning]
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'VENTAS POR TIPO DE PAGO'
                        }
                    }
                }
            });
        }

        // Gráfica de ingresos
        if (chartData.totalMoney) {
            const config = chartIngresosConfig(chartData);
            if (config) createChart('chartIngresos', config);
        }

        // Gráfica de top usuarios
        const topUsersConfig = chartTopUsersConfig();
        if (topUsersConfig) createChart('chartTopUsers', topUsersConfig);

        console.log('Inicialización de gráficas completada');
    };

    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeCharts);
    } else {
        initializeCharts();
    }
</script>
