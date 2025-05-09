// Pastikan ChartDataLabels sudah diregister di scope global,
// atau register di sini jika belum.
// Chart.register(ChartDataLabels); // Jika belum ada di app.js atau tempat lain

function initDashboardCharts() {
    const cfg = window.dashboardData;
    if (!cfg) {
        console.error("Dashboard data (window.dashboardData) not found.");
        return;
    }

    const isDarkMode = document.documentElement.classList.contains('dark');

    const commonChartOptions = (isIndexAxisY = false) => ({
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                ticks: { color: isDarkMode ? '#9ca3af' : '#6b7280' },
                grid: { color: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' }
            },
            y: {
                beginAtZero: true,
                ticks: { color: isDarkMode ? '#9ca3af' : '#6b7280' },
                grid: { color: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' }
            }
        },
        plugins: {
            legend: {
                labels: { color: isDarkMode ? '#cbd5e1' : '#4b5563' }
            },
            tooltip: {
                backgroundColor: isDarkMode ? 'rgba(55, 65, 81, 0.9)' : 'rgba(31, 41, 55, 0.9)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                titleFont: { size: 14, weight: 'bold' },
                bodyFont: { size: 12 },
                padding: 10,
                cornerRadius: 4,
            }
        }
    });

    const currencyFormatter = (value) => {
        if (value === null || value === undefined || value === 0) return '';
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
    };
    const numberFormatter = (value) => {
        if (value === null || value === undefined || value === 0) return '';
        return value.toLocaleString('id-ID');
    };
    const datalabelsDefaultColor = isDarkMode ? '#e5e7eb' : '#1f2937';

    // Chart 1: Keuntungan Mingguan
    const weeklyProfitChartEl = document.getElementById('weeklyProfitChart');
    if (weeklyProfitChartEl) {
        new Chart(weeklyProfitChartEl, {
            type: 'bar',
            data: {
                labels: cfg.weeklyProfitLabels,
                datasets: [{
                    label: 'Keuntungan (Rp)',
                    data: cfg.weeklyProfitData,
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                ...commonChartOptions(),
                plugins: {
                    ...commonChartOptions().plugins,
                    legend: { display: false },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: currencyFormatter,
                        font: { weight: 'bold', size: 10 },
                        color: datalabelsDefaultColor
                    }
                }
            }
        });
    }

    // Chart 2: Status Pemesanan (PIE CHART DIHAPUS)
    // const statusChartEl = document.getElementById('statusChart'); // DIHAPUS
    // if (statusChartEl) { ... } // SELURUH BLOK INI DIHAPUS

    // Chart 3: Pendapatan per Bulan
    const revenueChartEl = document.getElementById('revenueChart');
    if (revenueChartEl) {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        new Chart(revenueChartEl, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: cfg.revenue,
                    backgroundColor: 'rgba(16, 185, 129, 0.6)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                ...commonChartOptions(),
                plugins: {
                    ...commonChartOptions().plugins,
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: currencyFormatter,
                        font: { weight: 'bold', size: 10 },
                        color: datalabelsDefaultColor
                    }
                }
            }
        });
    }

    // Chart 4: Top 5 Layanan
    const topServicesChartEl = document.getElementById('topServicesChart');
    if (topServicesChartEl) {
        new Chart(topServicesChartEl, {
            type: 'bar',
            data: {
                labels: cfg.topServices.labels,
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: cfg.topServices.data,
                    backgroundColor: 'rgba(245, 158, 11, 0.6)',
                    borderColor: 'rgba(245, 158, 11, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                ...commonChartOptions(true),
                indexAxis: 'y',
                plugins: {
                    ...commonChartOptions(true).plugins,
                    legend: { display: false },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        formatter: numberFormatter,
                        font: { weight: 'bold', size: 10 },
                        color: datalabelsDefaultColor
                    }
                },
                scales: {
                     x: {
                        beginAtZero: true,
                        ticks: { color: isDarkMode ? '#9ca3af' : '#6b7280' },
                        grid: { color: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' }
                    },
                    y: {
                        ticks: { color: isDarkMode ? '#9ca3af' : '#6b7280' },
                        grid: { display: false } // Sembunyikan grid y-axis untuk horizontal bar
                    }
                }
            }
        });
    }
}

if (typeof ChartDataLabels !== 'undefined') {
    Chart.register(ChartDataLabels);
} else {
    console.warn('ChartDataLabels plugin is not loaded. Ensure it is included in your assets.');
}

document.addEventListener('DOMContentLoaded', initDashboardCharts);
