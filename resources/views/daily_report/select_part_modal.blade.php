@extends('layouts.user')

@section('contents')
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-body">
                <h5 class="mb-3 mt-2">Ditemukan lebih dari satu part untuk Inventory ID tersebut</h5>
                <p class="mb-4">Silakan pilih part yang sesuai dengan customer:</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Customer</th>
                                <th class="text-center">Part Name</th>
                                <th class="text-center">Part Number</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($parts as $part)
                                @php
                                    $inventory = $part->inventories->sortByDesc('created_at')->first();
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $part->customer->username ?? '-' }}</td>
                                    <td class="text-center">{{ $part->Part_name }}</td>
                                    <td class="text-center">{{ $part->Part_number }}</td>
                                    <td class="text-center">{{ $part->category->name ?? '-' }}</td>
                                    <td class="text-center">
                                        @if ($inventory)
                                            <a href="{{ route('sto.edit.report', ['inventory_id' => $inventory->id]) }}"
                                                class="btn btn-sm btn-primary ">
                                                Pilih
                                            </a>
                                        @else
                                            <span class="text-danger">Inventory tidak ditemukan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <a href="{{ route('dailyreport.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            </div>
        </div>
    </div>
@endsection
