document.addEventListener('DOMContentLoaded', () => {
    const apiUrl = 'http://localhost:8000/api/facturas';
    const yearFilter = document.getElementById('yearFilter');
    const monthFilter = document.getElementById('monthFilter');
    const ctx = document.getElementById('invoiceChart').getContext('2d');

    let invoices = [];
    let chart;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            invoices = data;
            populateFilters();
            drawChart(invoices);
        });

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
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Facturado',
                    data: totals,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
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

    yearFilter.addEventListener('change', filterData);
    monthFilter.addEventListener('change', filterData);

    function filterData() {
        const selectedYear = yearFilter.value;
        const selectedMonth = monthFilter.value;

        const filteredData = invoices.filter(invoice => {
            const date = new Date(invoice.Fecha);
            const yearMatches = selectedYear ? date.getFullYear() == selectedYear : true;
            const monthMatches = selectedMonth ? date.getMonth() + 1 == selectedMonth : true;
            return yearMatches && monthMatches;
        });

        drawChart(filteredData);
    }
});
