@extends('layouts.user')

@section('contents')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-body">
            <h5 class="mb-3">Ditemukan lebih dari satu part untuk Inventory ID tersebut</h5>
            <p class="mb-4">Silakan pilih part yang sesuai dengan customer:</p>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Part Name</th>
                        <th>Part Number</th>
                        <th>Category</th>
                        <th>Plant</th>
                        <th>Area</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($parts as $part)
                        @php
                            $inventory = $part->inventories->sortByDesc('created_at')->first();
                        @endphp
                        <tr>
                            <td>{{ $part->customer->username ?? '-' }}</td>
                            <td>{{ $part->Part_name }}</td>
                            <td>{{ $part->Part_number }}</td>
                            <td>{{ $part->category->name ?? '-' }}</td>
                            <td>{{ $part->plant->name ?? '-' }}</td>
                            <td>{{ $part->area->nama_area ?? '-' }}</td>
                            <td>
                                @if ($inventory)
                                    <a href="{{ route('sto.edit.report', ['inventory_id' => $inventory->id]) }}"
                                        class="btn btn-sm btn-primary">
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

            <a href="{{ route('dailyreport.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>
@endsection
