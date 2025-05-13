document.addEventListener("DOMContentLoaded", () => {
    // STO Chart
    if (window.stoData) {
        const dates = window.stoData.map(item => item.date);
        const totals = window.stoData.map(item => item.total);

        new ApexCharts(document.querySelector("#chart-sto"), {
            chart: {
                type: 'line',
                height: 350
            },
            series: [{
                name: 'Jumlah STO',
                data: totals
            }],
            xaxis: {
                categories: dates
            },
            stroke: {
                curve: 'smooth'
            },
            colors: ['#008ffb']
        }).render();
    }

    // Daily Stock per Customer
    if (window.dailyStock) {
        const customers = Object.keys(window.dailyStock);
        const totals = Object.values(window.dailyStock);

        new ApexCharts(document.querySelector("#chart-daily-stock"), {
            chart: {
                type: 'bar',
                height: 350
            },
            series: [{
                name: 'Total Qty',
                data: totals
            }],
            xaxis: {
                categories: customers
            },
            colors: ['#00e396']
        }).render();
    }
});
