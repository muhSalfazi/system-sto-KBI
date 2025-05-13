function loadChart(elementId, dataObject, title) {
    const categories = Object.keys(dataObject);
    const seriesData = Object.values(dataObject);

    var options = {
        chart: { type: 'bar', height: 300 },
        series: [{
            name: title,
            data: seriesData
        }],
        xaxis: {
            categories: categories
        }
    };

    var chart = new ApexCharts(document.querySelector(elementId), options);
    chart.render();
}

document.addEventListener("DOMContentLoaded", () => {
    loadChart("#stoChart", stoData, 'STO per Customer');
    loadChart("#dailyStockChart", dailyStockData, 'Daily Stock per Customer');
});
