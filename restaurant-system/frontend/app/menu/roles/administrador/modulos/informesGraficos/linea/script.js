document.addEventListener('DOMContentLoaded', () => {
    const apiUrl = 'http://localhost:8000/api/facturas';
    const ctx = document.getElementById('lineChart').getContext('2d');
    const yearFilter = document.getElementById('yearFilter');
    const monthFilter = document.getElementById('monthFilter');
    const applyFiltersButton = document.getElementById('applyFilters');

    let invoices = [];
    let chart;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            invoices = data;
            populateFilters();
            drawChart(invoices);
        })
        .catch(error => console.error('Error fetching data:', error));

    function populateFilters() {
        const years = [...new Set(invoices.map(invoice => new Date(invoice.Fecha).getFullYear()))];
        years.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearFilter.appendChild(option);
        });

        const months = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        months.forEach((month, index) => {
            const option = document.createElement('option');
            option.value = index + 1;
            option.textContent = month;
            monthFilter.appendChild(option);
        });
    }

    applyFiltersButton.addEventListener('click', () => {
        const selectedYear = yearFilter.value;
        const selectedMonth = monthFilter.value;

        let filteredInvoices = invoices;

        if (selectedYear) {
            filteredInvoices = filteredInvoices.filter(invoice => new Date(invoice.Fecha).getFullYear() == selectedYear);
        }

        if (selectedMonth) {
            filteredInvoices = filteredInvoices.filter(invoice => new Date(invoice.Fecha).getMonth() + 1 == selectedMonth);
        }

        drawChart(filteredInvoices);
    });

    function drawChart(data) {
        if (chart) chart.destroy();

        const groupedData = data.reduce((acc, invoice) => {
            const date = invoice.Fecha;
            if (!acc[date]) {
                acc[date] = 0;
            }
            acc[date] += parseFloat(invoice.Total);
            return acc;
        }, {});

        const labels = Object.keys(groupedData).sort((a, b) => new Date(a) - new Date(b));
        const totals = labels.map(date => groupedData[date]);

        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Facturado',
                    data: totals,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
