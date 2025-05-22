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
            {{-- form buat form filter bulan+customer --}}
            <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
                <div class="col-12">
                    <h5 class="card-title">Input Date & Customer</h5>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="monthSelect">STO Report</label>
                            <select name="month" id="monthSelect" class="form-control">
                                @for ($i = -5; $i <= 2; $i++)
                                    {{-- dari 5 bulan lalu sampai 2 bulan ke depan --}}
                                    @php
                                        $monthValue = now()->addMonths($i)->format('Y-m');
                                    @endphp
                                    <option value="{{ $monthValue }}"
                                        {{ (request('month') ?? now()->format('Y-m')) === $monthValue ? 'selected' : '' }}>
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
            {{-- endform  buat filter customer+bulan --}}
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
                        <h5 class="card-title">Monthly Report Stock</h5>

                        <div class="row g-2 align-items-end mb-3">
                            <div class="col-md-4">
                                <label for="dateSelect">Date Filter</label>
                                <input type="date" id="dateSelect" class="form-control">
                            </div>
                        </div>

                        <!-- Bar Chart -->
                        <div id="dailychart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                const dateInput = document.getElementById("dateSelect");
                                const monthSelect = document.getElementById("monthSelect");
                                const customerSelect = document.getElementById("custForecast");
                                const chartContainer = document.querySelector("#dailychart");

                                function loadStoChart() {
                                    const month = monthSelect.value;
                                    const customer = customerSelect.value;
                                    const date = dateInput.value;

                                    let url = `/dashboard/sto-chart-data?customer=${customer}`;
                                    if (month) {
                                        url += `&month=${month}`;
                                    }
                                    if (month && date) {
                                        url += `&date=${date}`;
                                    }

                                    fetch(url)
                                        .then(response => response.json())
                                        .then(result => {
                                            chartContainer.innerHTML = "";

                                            if (!result.data || result.data.length === 0) {
                                                chartContainer.innerHTML = `
                        <div class="text-center text-muted mt-3">
                            <p><strong>Data tidak tersedia</strong> untuk filter yang dipilih.</p>
                        </div>`;
                                                return;
                                            }

                                            new ApexCharts(chartContainer, {
                                                series: [{
                                                    name: "Total Qty",
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
                                            chartContainer.innerHTML = `
                    <div class="text-center text-danger mt-3">
                        <p><strong>Terjadi kesalahan saat memuat data chart.</strong></p>
                    </div>`;
                                        });
                                }

                                // Event: perubahan filter langsung memuat grafik
                                monthSelect.addEventListener("change", loadStoChart);
                                customerSelect.addEventListener("change", loadStoChart);
                                dateInput.addEventListener("change", loadStoChart);

                                // Load awal (berdasarkan bulan & customer default)
                                loadStoChart();
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
                        <h5 class="card-title">Weekly</h5>

                        {{-- Filter Minggu --}}
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select id="weekSelect" class="form-select">
                                    <option value="1">Week 1 (1-7)</option>
                                    <option value="2">Week 2 (8–14)</option>
                                    <option value="3">Week 3 (15–21)</option>
                                    <option value="4">Week 4 (22–28)</option>
                                    <option value="5">Week 5 (29–31)</option>
                                </select>
                            </div>
                        </div>


                        <div id="stockComparisonChart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                const chartContainer = document.querySelector("#stockComparisonChart");
                                const weekSelect = document.getElementById("weekSelect");
                                const monthSelect = document.getElementById("monthSelect");


                                function updateWeekOptions() {
                                    const selectedMonth = monthSelect.value; // format: yyyy-mm
                                    const [year, month] = selectedMonth.split('-').map(Number);
                                    const daysInMonth = new Date(year, month, 0).getDate();

                                    const totalWeeks = Math.ceil(daysInMonth / 7);
                                    const weekRanges = [];

                                    for (let i = 0; i < totalWeeks; i++) {
                                        const start = i * 7 + 1;
                                        const end = Math.min((i + 1) * 7, daysInMonth);
                                        weekRanges.push({
                                            week: i + 1,
                                            label: `Week ${i + 1} (${start}–${end})`
                                        });
                                    }

                                    weekSelect.innerHTML = `<option value="">Select Week</option>`;
                                    weekRanges.forEach(w => {
                                        const opt = document.createElement('option');
                                        opt.value = w.week;
                                        opt.textContent = w.label;
                                        weekSelect.appendChild(opt);
                                    });
                                }

                                function loadStoChart() {
                                    const month = monthSelect.value;
                                    const customer = document.getElementById("custForecast").value;
                                    const week = weekSelect.value;

                                    fetch(`/dashboard/daily-chart-data?month=${month}&customer=${customer}&week=${week}`)
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

                                            const invColors = {};
                                            const colorPalette = ['#1f77b4', '#2ca02c', '#ff7f0e', '#d62728', '#9467bd', '#8c564b',
                                                '#e377c2', '#7f7f7f', '#bcbd22', '#17becf'
                                            ];
                                            let colorIndex = 0;

                                            const minMap = new Map();
                                            const maxMap = new Map();

                                            result.series[0].data.forEach(d => minMap.set(d.x, d.y));
                                            result.series[2].data.forEach(d => maxMap.set(d.x, d.y));

                                            const actualData = result.series[1].data.map((d, i) => {
                                                const inv = String(d.x);
                                                if (!invColors[inv]) {
                                                    invColors[inv] = colorPalette[colorIndex % colorPalette.length];
                                                    colorIndex++;
                                                }

                                                return {
                                                    x: inv,
                                                    y: d.y,
                                                    inv: inv,
                                                    label: inv,
                                                    min: result.series[0].data[i]?.y || 0,
                                                    max: result.series[2].data[i]?.y || 0
                                                };
                                            });

                                            const annotations = [];
                                            [...minMap.entries()].forEach(([invId, val]) => {
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

                                            [...maxMap.entries()].forEach(([invId, val]) => {
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
                                                        distributed: true
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                xaxis: {
                                                    type: 'category',
                                                    labels: {
                                                        rotate: -45,
                                                        style: {
                                                            fontSize: '10px'
                                                        }
                                                    },
                                                    title: {
                                                        text: 'Inventory ID',
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
                                                colors: actualData.map(d => invColors[d.inv]),
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
                                                                font-family: 'Segoe UI';
                                                                border-left: 4px solid #007bff;
                                                                min-width: 200px;
                                                            ">
                                                                <div style="font-weight: bold; font-size: 14px; color: #333; margin-bottom: 6px;">
                                                                    Inventory: ${data.label}
                                                                </div>
                                                                <div style="font-size: 13px; color: #555;">
                                                                    <span style="display: inline-block; width: 70px;">Min:</span> <strong style="color: #00BFFF;">${data.min} pcs</strong><br/>
                                                                    <span style="display: inline-block; width: 70px;">Act :</span> <strong style="color: #FFA500;">${data.y} pcs</strong><br/>
                                                                    <span style="display: inline-block; width: 70px;">Max:</span> <strong style="color: #FF0000;">${data.max} pcs</strong>
                                                                </div>
                                                            </div>
                                                        `;
                                                    }
                                                },
                                                legend: {
                                                    show: false
                                                },
                                                grid: {
                                                    borderColor: '#e7e7e7',
                                                    row: {
                                                        colors: ['#f3f3f3', 'transparent'],
                                                        opacity: 0.5
                                                    }
                                                }
                                            });

                                            chartContainer.innerHTML = '';
                                            chart.render();
                                        });
                                }

                                // Init minggu dan grafik saat load
                                updateWeekOptions();
                                loadStoChart();

                                // Event filter
                                weekSelect.addEventListener("change", loadStoChart);
                                document.getElementById("custForecast").addEventListener("change", loadStoChart);
                                monthSelect.addEventListener("change", () => {
                                    updateWeekOptions();
                                    loadStoChart();
                                });
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
                        <h5 class="card-title">Today’s Stock - {{ now()->format('F Y') }}</h5>
                        <div class="row mb-3">
                            {{-- filter buat Kategori --}}
                            <div class="col-md-4">
                                <label for="categorySelect" class="form-label">Filter by Category</label>
                                <select id="categorySelect" name="category" class="form-select">
                                    <option value="">-- Semua Kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- end filter buat kategori --}}
                        </div>

                        <!-- Bar Chart -->
                        <div id="stockPerDayChart" style="height: 400px;"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const chartContainer = document.querySelector("#stockPerDayChart");
                                const categorySelect = document.getElementById("categorySelect");
                                const monthSelect = document.getElementById("monthSelect");
                                const customerSelect = document.getElementById("custForecast");

                                function loadStockPerDayChart() {
                                    const month = monthSelect?.value;
                                    const customer = customerSelect?.value;
                                    const category = categorySelect?.value;

                                    fetch(`/dashboard/daily-stock-perday-data?month=${month}&customer=${customer}&category=${category}`)
                                        .then(res => res.json())
                                        .then(result => {
                                            chartContainer.innerHTML = ''; // bersihkan chart lama

                                            if (!result.series || result.series.length === 0 || result.series.every(s => !s.data ||
                                                    s.data.length === 0)) {
                                                chartContainer.innerHTML =
                                                    "<p class='text-center'>Tidak ada inventory untuk kategori atau customer ini.</p>";
                                                return;
                                            }

                                            const chart = new ApexCharts(chartContainer, {
                                                chart: {
                                                    type: 'bar',
                                                    height: 500,
                                                    toolbar: {
                                                        show: true
                                                    }
                                                },
                                                plotOptions: {
                                                    bar: {
                                                        horizontal: true,
                                                        barHeight: '60%',
                                                        distributed: true
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                stroke: {
                                                    show: true,
                                                    width: 1,
                                                    colors: ['transparent']
                                                },
                                                series: result.series,
                                                xaxis: {
                                                    title: {
                                                        text: 'Day',
                                                        style: {
                                                            fontWeight: 600
                                                        }
                                                    },
                                                    labels: {
                                                        formatter: val => `${val}`,
                                                        style: {
                                                            fontSize: '10px'
                                                        }
                                                    }
                                                },
                                                yaxis: {
                                                    labels: {
                                                        style: {
                                                            fontSize: '12px'
                                                        }
                                                    },
                                                    title: {
                                                        text: 'Inventory ID',
                                                        style: {
                                                            fontWeight: 600
                                                        }
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
                                                        const label = point?.x ??
                                                            `Inv: ${w.config.series[seriesIndex].name}`;

                                                        const qty = point?.y ?? 0;

                                                        return `
                                                            <div style="
                                                                background: white;
                                                                padding: 10px 15px;
                                                                border-radius: 8px;
                                                                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                                                                font-family: 'Segoe UI';
                                                                border-left: 4px solid #007bff;
                                                                min-width: 200px;">
                                                                <strong>${label}</strong><br/>
                                                                Qty Day: ${qty}
                                                            </div>`;
                                                    }
                                                },
                                                colors: ['#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd', '#8c564b']
                                            });

                                            chart.render();
                                        })
                                        .catch(error => {
                                            console.error("Error loading chart:", error);
                                            chartContainer.innerHTML = "<p class='text-danger text-center'>Gagal memuat chart.</p>";
                                        });
                                }

                                categorySelect?.addEventListener('change', loadStockPerDayChart);
                                monthSelect?.addEventListener('change', loadStockPerDayChart);
                                customerSelect?.addEventListener('change', loadStockPerDayChart);

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
