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
                        <h5 class="card-title">STO</h5>

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
                                            chartContainer.innerHTML = ""; // Clear chart first

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
                                                        horizontal: false
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                xaxis: {
                                                    categories: result.categories
                                                }
                                            }).render();
                                        });
                                }

                                // Load awal
                                loadStoChart();

                                // Auto-refresh tiap 10 detik
                                setInterval(loadStoChart, 10000);
                            });
                        </script>

                        <!-- End Bar Chart -->
                    </div>
                </div>
            </div>

            {{-- daily --}}
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daily Stok</h5>
                        <!-- Bar Chart -->
                        <div id="barChart"></div>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                function loadDailyChart() {
                                    const monthInput = document.getElementById('monthSelect');
                                    const customerInput = document.getElementById('custForecast');
                                    const month = monthInput.value;
                                    const customer = customerInput.value;

                                    fetch(`/dashboard/daily-chart-data?month=${month}&customer=${customer}`)
                                        .then(response => response.json())
                                        .then(result => {
                                            const chartContainer = document.querySelector("#barChart");
                                            chartContainer.innerHTML = ""; // clear previous chart

                                            if (!result.series || result.series.length === 0) {
                                                chartContainer.innerHTML =
                                                    "<p class='text-center'>No data available for the selected filters.</p>";
                                                return;
                                            }

                                            new ApexCharts(chartContainer, {
                                                series: result.series,
                                                chart: {
                                                    type: 'bar',
                                                    height: 450,
                                                    stacked: true
                                                },
                                                plotOptions: {
                                                    bar: {
                                                        borderRadius: 4,
                                                        horizontal: true
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: true
                                                },
                                                xaxis: {
                                                    title: {
                                                        text: 'Total Qty'
                                                    },
                                                    categories: result.categories
                                                },
                                                yaxis: {
                                                    title: {
                                                        text: 'Inv id'
                                                    },
                                                    labels: {
                                                        style: {
                                                            fontSize: '14px'
                                                        }
                                                    }
                                                },
                                                tooltip: {
                                                    custom: function({
                                                        series,
                                                        seriesIndex,
                                                        dataPointIndex,
                                                        w
                                                    }) {
                                                        const data = w.config.series[seriesIndex].data[dataPointIndex];
                                                        return `<div style="padding:10px">
                                        <strong>Part: ${data?.x ?? '-'}</strong><br>
                                        Total Qty: ${data?.y ?? 0}<br>
                                        Date: ${data?.tanggal ?? '-'}
                                    </div>`;
                                                    }
                                                }
                                            }).render();
                                        });
                                }

                                // First load
                                loadDailyChart();
                                // Refresh every 10 seconds
                                setInterval(loadDailyChart, 10000);
                            });
                        </script>
                        <!-- End Bar Chart -->
                    </div>
                </div>
            </div>
            <!-- Bar Chart -->
        </div>

    </section>
@endsection
