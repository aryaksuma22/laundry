// Dashboard.js — render 4 chart, expose initDashboardCharts()

Chart.register(ChartDataLabels);

function initDashboardCharts() {
    const cfg = window.dashboardData;
    if (!cfg) return;
    if (!document.getElementById('ordersChart')) return;

    // 1) Pesanan per Tahun (line)
    new Chart(document.getElementById('ordersChart'), {
        type: 'line',
        data: {
            labels: cfg.yearLabels,
            datasets: [{
                label: 'Pesanan / Tahun',
                data: cfg.yearlyOrders,
                fill: false,
                tension: 0.3,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } },
            plugins: {
                datalabels: { anchor: 'end', align: 'top', formatter: v => v, font: { weight: 'bold' } },
                legend: { display: false }
            }
        }
    });

    // 2) Status Pemesanan (pie)
    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: cfg.status.labels,
            datasets: [{ data: cfg.status.data }]
        },
        options: {
            plugins: {
                datalabels: { formatter: v => v, font: { weight: 'bold' } },
                legend: { position: 'bottom' }
            }
        }
    });

    // 3) Pendapatan per Bulan (bar)
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{ label: 'Pendapatan (Rp)', data: cfg.revenue }]
        },
        options: {
            scales: { y: { beginAtZero: true } },
            plugins: { datalabels: { anchor: 'end', align: 'top', formatter: v => v } }
        }
    });

    // 4) Top 5 Layanan (bar)
    new Chart(document.getElementById('topServicesChart'), {
        type: 'bar',
        data: {
            labels: cfg.topServices.labels,
            datasets: [{ label: 'Jumlah Pesanan', data: cfg.topServices.data }]
        },
        options: {
            scales: { y: { beginAtZero: true } },
            plugins: { datalabels: { anchor: 'end', align: 'top', formatter: v => v }, legend: { display: false } }
        }
    });
}

window.initDashboardCharts = initDashboardCharts;
document.addEventListener('DOMContentLoaded', initDashboardCharts);
