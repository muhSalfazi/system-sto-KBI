@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="pagetitle animate__animated animate__fadeInLeft">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            {{-- form STO --}}
            <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
                <div class="col-12">
                    <h5 class="card-title">Input Date & Customer</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="monthSelect">Select Month</label>
                            <select name="month" id="monthSelect" class="form-control">
                                @for ($i = 0; $i < 12; $i++)
                                    @php
                                        $monthValue = now()->addMonths($i)->format('Y-m');
                                    @endphp
                                    <option value="{{ $monthValue }}"
                                        {{ request('month') === $monthValue ? 'selected' : '' }}>
                                        {{ now()->addMonths($i)->format('F Y') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="custForecast">Select Customer</label>
                            <select name="customer" id="custForecast" class="form-control">
                                <option value="">All Customers</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->username }}"
                                        {{ request('customer') === $customer->username ? 'selected' : '' }}>
                                        {{ $customer->username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('filterForm');
                    document.getElementById('monthSelect').addEventListener('change', () => form.submit());
                    document.getElementById('custForecast').addEventListener('change', () => form.submit());
                });
            </script>
            {{-- end --}}
            {{-- STO --}}
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">STO Report</h5>

                        <!-- Bar Chart -->
                        <div id="dailychart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                function loadStoChart() {
                                    const month = document.getElementById('monthSelect').value;
                                    const customer = document.getElementById('custForecast').value;

                                    fetch(`/dashboard/sto-chart-data?month=${month}&customer=${customer}`)
                                        .then(response => response.json())
                                        .then(result => {
                                            const chartContainer = document.querySelector("#dailychart");
                                            chartContainer.innerHTML = ""; // Clear existing content

                                            const hasData = result.data && result.data.length > 0;

                                            if (!hasData) {
                                                chartContainer.innerHTML = `
              <div class="text-center text-muted mt-3">
                <p><strong>Data tidak tersedia</strong> untuk bulan atau customer yang dipilih.</p>
              </div>`;
                                                return;
                                            }

                                            new ApexCharts(chartContainer, {
                                                series: [{
                                                    name: "Plan Stock",
                                                    data: result.data
                                                }],
                                                chart: {
                                                    type: 'bar',
                                                    height: 450
                                                },
                                                plotOptions: {
                                                    bar: {
                                                        borderRadius: 4,
                                                        horizontal: false,
                                                        distributed: true
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                xaxis: {
                                                    categories: result.categories
                                                }
                                            }).render();
                                        })
                                        .catch(error => {
                                            console.error("Gagal memuat data chart:", error);
                                            document.querySelector("#dailychart").innerHTML = `
            <div class="text-center text-danger mt-3">
              <p><strong>Terjadi kesalahan saat memuat data chart.</strong></p>
            </div>`;
                                        });
                                }

                                // Load awal
                                loadStoChart();

                                // Auto-refresh tiap 10 detik
                                setInterval(loadStoChart, 20000);
                            });
                        </script>

                        <!-- End Bar Chart -->
                    </div>
                </div>
            </div>

            {{-- report daily --}}
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daily (Per Item)</h5>
                        <div id="stockComparisonChart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                const chartContainer = document.querySelector("#stockComparisonChart");

                                function loadStoChart() {
                                    const month = document.getElementById("monthSelect").value;
                                    const customer = document.getElementById("custForecast").value;

                                    fetch(`/dashboard/daily-chart-data?month=${month}&customer=${customer}`)
                                        .then(res => res.json())
                                        .then(result => {
                                            if (!result.series || result.series[1].data.length === 0) {
                                                chartContainer.innerHTML = "<p class='text-center'>Data tidak tersedia.</p>";
                                                return;
                                            }

                                            const allActual = result.series[1].data.map(d => d.y);
                                            const allMax = result.series[2].data.map(d => d.y);
                                            const allMin = result.series[0].data.map(d => d.y);
                                            const yMax = Math.max(...allActual, ...allMax) + 10;
                                            const yMin = Math.min(...allMin, 0) - 5;

                                            const minMap = new Map();
                                            const maxMap = new Map();

                                            result.series[0].data.forEach(d => {
                                                const invId = d.x.split(" - ")[1];
                                                minMap.set(`${d.x}`, d.y);
                                            });

                                            result.series[2].data.forEach(d => {
                                                const invId = d.x.split(" - ")[1];
                                                maxMap.set(`${d.x}`, d.y);
                                            });

                                            const annotations = [];

                                            // Garis batas MIN & MAX
                                            [...minMap.entries()].forEach(([label, val]) => {
                                                const invId = label.split(" - ")[1];
                                                annotations.push({
                                                    y: val,
                                                    borderColor: '#00BFFF',
                                                    strokeDashArray: 6,
                                                    label: {
                                                        borderColor: '#00BFFF',
                                                        style: {
                                                            color: '#fff',
                                                            background: '#00BFFF'
                                                        },
                                                        text: `Min (${invId})`
                                                    }
                                                });
                                            });

                                            [...maxMap.entries()].forEach(([label, val]) => {
                                                const invId = label.split(" - ")[1];
                                                annotations.push({
                                                    y: val,
                                                    borderColor: '#FF0000',
                                                    strokeDashArray: 6,
                                                    label: {
                                                        borderColor: '#FF0000',
                                                        style: {
                                                            color: '#fff',
                                                            background: '#FF0000'
                                                        },
                                                        text: `Max (${invId})`
                                                    }
                                                });
                                            });

                                            // Buat warna dinamis berdasarkan inv_id
                                            const invColors = {};
                                            const colorPalette = [
                                                '#1f77b4', '#2ca02c', '#ff7f0e', '#d62728',
                                                '#9467bd', '#8c564b', '#e377c2', '#7f7f7f',
                                                '#bcbd22', '#17becf'
                                            ];
                                            let colorIndex = 0;

                                            const actualData = result.series[1].data.map((d, i) => {
                                                const tanggal = d.x.split(" - ")[0];
                                                const inv = d.x.split(" - ")[1];
                                                const fullKey = `${tanggal} - ${inv}`;

                                                if (!invColors[inv]) {
                                                    invColors[inv] = colorPalette[colorIndex % colorPalette.length];
                                                    colorIndex++;
                                                }

                                                return {
                                                    x: tanggal,
                                                    y: d.y,
                                                    fillColor: invColors[inv],
                                                    inv: inv,
                                                    label: fullKey,
                                                    min: result.series[0].data[i]?.y || 0,
                                                    max: result.series[2].data[i]?.y || 0
                                                };
                                            });

                                            const chart = new ApexCharts(chartContainer, {
                                                chart: {
                                                    type: 'bar',
                                                    height: 500,
                                                    toolbar: {
                                                        show: true
                                                    },
                                                    zoom: {
                                                        enabled: true
                                                    }
                                                },
                                                series: [{
                                                    name: 'Actual',
                                                    data: actualData
                                                }],
                                                annotations: {
                                                    yaxis: annotations
                                                },
                                                plotOptions: {
                                                    bar: {
                                                        columnWidth: '55%',
                                                        distributed: true // penting agar warna per item aktif
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                xaxis: {
                                                    type: 'category',
                                                    labels: {
                                                        rotate: 0,
                                                        style: {
                                                            fontSize: '10px'
                                                        }
                                                    },
                                                    title: {
                                                        text: 'Days',
                                                        style: {
                                                            fontWeight: 600
                                                        }
                                                    }
                                                },
                                                yaxis: {
                                                    min: yMin < 0 ? 0 : yMin,
                                                    max: yMax,
                                                    title: {
                                                        text: 'Total Qty',
                                                        style: {
                                                            fontWeight: 600
                                                        }
                                                    },
                                                    labels: {
                                                        formatter: val => `${Math.round(val)} pcs`
                                                    }
                                                },
                                                fill: {
                                                    type: 'solid'
                                                },
                                                colors: actualData.map(d => d.fillColor),
                                                tooltip: {
                                                    shared: false,
                                                    intersect: true,
                                                    custom: function({
                                                        series,
                                                        seriesIndex,
                                                        dataPointIndex,
                                                        w
                                                    }) {
                                                        const data = w.config.series[seriesIndex].data[dataPointIndex];
                                                        return `
  <div style="
      background: white;
      padding: 10px 15px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      border-left: 4px solid #007bff;
      min-width: 200px;
  ">
    <div style="font-weight: bold; font-size: 14px; color: #333; margin-bottom: 6px;">
      ${data.label}
    </div>
    <div style="font-size: 13px; color: #555;">
      <span style="display: inline-block; width: 70px;">Min:</span> <strong style="color: #00BFFF;">${data.min} pcs</strong><br/>
      <span style="display: inline-block; width: 70px;">Actual:</span> <strong style="color: #FFA500;">${data.y} pcs</strong><br/>
      <span style="display: inline-block; width: 70px;">Max:</span> <strong style="color: #FF0000;">${data.max} pcs</strong>
    </div>
  </div>
`;

                                                    }
                                                },
                                                legend: {
                                                    show: false,
                                                    position: 'top',
                                                    horizontalAlign: 'center',
                                                    formatter: function(seriesName, opts) {
                                                        return opts.w.globals.initialSeries[0].data[opts.seriesIndex]
                                                            ?.inv || seriesName;
                                                    }
                                                },
                                                grid: {
                                                    borderColor: '#e7e7e7',
                                                    row: {
                                                        colors: ['#f3f3f3', 'transparent'],
                                                        opacity: 0.5
                                                    }
                                                }
                                            });

                                            chartContainer.innerHTML = ''; // reset container
                                            chart.render();
                                        });
                                }

                                // Load pertama kali
                                loadStoChart();

                                // Auto-refresh setiap 10 detik
                                setInterval(loadStoChart, 20000);
                            });
                        </script>

                    </div>


                </div>

            </div>
            <!-- Bar Chart -->


            {{-- daily stock --}}
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daily Stock</h5>

                        <!-- Bar Chart -->
                          <div id="stockPerDayChart" style="height: 400px;"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const chartContainer = document.querySelector("#stockPerDayChart");

                                function loadStockPerDayChart() {
                                    const month = document.getElementById("monthSelect")?.value;
                                    const customer = document.getElementById("custForecast")?.value;

                                    fetch(`/dashboard/daily-stock-perday-data?month=${month}&customer=${customer}`)
                                        .then(res => res.json())
                                        .then(result => {
                                            if (!result.series || result.series.length === 0) {
                                                chartContainer.innerHTML = "<p class='text-center'>Data tidak tersedia.</p>";
                                                return;
                                            }

                                            const chart = new ApexCharts(chartContainer, {
                                                chart: {
                                                    type: 'line',
                                                    height: 400,
                                                    toolbar: {
                                                        show: true
                                                    }
                                                },
                                                series: result.series,
                                                xaxis: {
                                                    title: {
                                                        text: 'Hari ke-',
                                                        style: {
                                                            fontWeight: 600
                                                        }
                                                    },
                                                    labels: {
                                                        style: {
                                                            fontSize: '10px'
                                                        }
                                                    }
                                                },
                                                yaxis: {
                                                    title: {
                                                        text: 'Stock per Day',
                                                        style: {
                                                            fontWeight: 600
                                                        }
                                                    },
                                                    labels: {
                                                        formatter: val => `${val} `
                                                    }
                                                },
                                                tooltip: {
                                                    shared: false,
                                                    custom: function({
                                                        series,
                                                        seriesIndex,
                                                        dataPointIndex,
                                                        w
                                                    }) {
                                                        const point = w.config.series[seriesIndex].data[dataPointIndex];
                                                        return `
                                <div style="
      background: white;
      padding: 10px 15px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      border-left: 4px solid #007bff;
      min-width: 200px;">
                                    <strong>${point.label}</strong><br/>
                                    Qty Stock: ${point.y}
                                </div>`;
                                                    }
                                                },
                                                stroke: {
                                                    curve: 'smooth'
                                                },
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                colors: ['#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd', '#8c564b']
                                            });

                                            chartContainer.innerHTML = '';
                                            chart.render();
                                        });
                                }

                                loadStockPerDayChart();
                                setInterval(loadStockPerDayChart, 20000);
                            });
                        </script>


                        <!-- End Bar Chart -->

                    </div>
                </div>
            </div>



        </div>

    </section>
@endsection
