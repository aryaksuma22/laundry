// Pastikan ChartDataLabels sudah diregister di scope global,
// atau register di sini jika belum.
// Chart.register(ChartDataLabels); // Jika belum ada di app.js atau tempat lain

function initDashboardCharts() {
    const cfg = window.dashboardData;
    if (!cfg) {
        console.error("Dashboard data (window.dashboardData) not found.");
        return;
    }

    // Helper untuk deteksi dark mode (sesuaikan jika implementasi Anda berbeda)
    const isDarkMode = document.documentElement.classList.contains('dark');

    // Opsi Umum untuk Chart
    const commonChartOptions = (isIndexAxisY = false) => ({
        responsive: true,
        maintainAspectRatio: false, // Penting karena kontainer canvas punya tinggi tetap
        scales: {
            x: {
                ticks: { color: isDarkMode ? '#9ca3af' : '#6b7280' }, // Abu-abu untuk dark/light
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
                backgroundColor: isDarkMode ? 'rgba(55, 65, 81, 0.9)' : 'rgba(31, 41, 55, 0.9)', // Warna tooltip
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                titleFont: { size: 14, weight: 'bold' },
                bodyFont: { size: 12 },
                padding: 10,
                cornerRadius: 4,
            }
        }
    });

    // Formatter untuk Data Labels
    const currencyFormatter = (value) => {
        if (value === null || value === undefined || value === 0) return '';
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
    };
    const numberFormatter = (value) => {
        if (value === null || value === undefined || value === 0) return '';
        return value.toLocaleString('id-ID');
    };
    const datalabelsDefaultColor = isDarkMode ? '#e5e7eb' : '#1f2937'; // Warna teks datalabel

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
                    backgroundColor: 'rgba(59, 130, 246, 0.6)', // Tailwind's blue-500
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 4, // Bar yang sedikit rounded
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

    // Chart 2: Status Pemesanan
    const statusChartEl = document.getElementById('statusChart');
    if (statusChartEl) {
        new Chart(statusChartEl, {
            type: 'pie',
            data: {
                labels: cfg.status.labels,
                datasets: [{
                    data: cfg.status.data,
                    backgroundColor: [
                        'rgba(236, 72, 153, 0.7)', 'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)', 'rgba(99, 102, 241, 0.7)',
                        'rgba(239, 68, 68, 0.7)',  'rgba(14, 165, 233, 0.7)'
                    ],
                    borderColor: isDarkMode ? '#4b5563' : '#ffffff', // Border antar slice
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    ...commonChartOptions().plugins,
                    legend: { position: 'bottom', labels: { color: isDarkMode ? '#cbd5e1' : '#4b5563' } },
                    datalabels: {
                        formatter: (value, context) => {
                            if (value === 0) return '';
                            const sum = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value * 100 / sum).toFixed(1) + "%";
                            return `${value}\n(${percentage})`;
                        },
                        color: '#ffffff',
                        font: { weight: 'bold', size: 11 },
                        textAlign: 'center'
                    }
                }
            }
        });
    }

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
                    backgroundColor: 'rgba(16, 185, 129, 0.6)', // Tailwind's emerald-500
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                ...commonChartOptions(),
                plugins: {
                    ...commonChartOptions().plugins,
                    // legend display true by default, bisa di-override jika hanya 1 dataset
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
            type: 'bar', // Bisa juga 'bar' dengan indexAxis: 'y' untuk horizontal
            data: {
                labels: cfg.topServices.labels,
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: cfg.topServices.data,
                    backgroundColor: 'rgba(245, 158, 11, 0.6)', // Tailwind's amber-500
                    borderColor: 'rgba(245, 158, 11, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                ...commonChartOptions(true), // true untuk indexAxis: 'y' jika diinginkan
                indexAxis: 'y', // Membuat bar chart horizontal
                plugins: {
                    ...commonChartOptions(true).plugins,
                    legend: { display: false },
                    datalabels: {
                        anchor: 'end',
                        align: 'end', // Sesuaikan untuk horizontal bar
                        formatter: numberFormatter,
                        font: { weight: 'bold', size: 10 },
                        color: datalabelsDefaultColor
                    }
                },
                scales: { // Perlu penyesuaian untuk indexAxis: 'y'
                     x: { // Sumbu X sekarang adalah nilai
                        beginAtZero: true,
                        ticks: { color: isDarkMode ? '#9ca3af' : '#6b7280' },
                        grid: { color: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' }
                    },
                    y: { // Sumbu Y sekarang adalah kategori
                        ticks: { color: isDarkMode ? '#9ca3af' : '#6b7280' },
                        grid: { color: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' }
                    }
                }
            }
        });
    }
}

// Pastikan plugin datalabels sudah ter-register
if (typeof ChartDataLabels !== 'undefined') {
    Chart.register(ChartDataLabels);
} else {
    console.warn('ChartDataLabels plugin is not loaded. Ensure it is included in your assets.');
}

document.addEventListener('DOMContentLoaded', initDashboardCharts);

// Jika Anda memiliki mekanisme untuk mengubah tema (dark/light) tanpa reload halaman,
// Anda mungkin perlu menghancurkan dan membuat ulang chart.
// Misal: document.addEventListener('themeChanged', () => { Chart.instances.forEach(c => c.destroy()); initDashboardCharts(); });
