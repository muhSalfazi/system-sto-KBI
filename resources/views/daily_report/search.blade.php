@extends('layouts.user')

@section('contents')
  <div class="container">
    <div class="card p-2 p-md-4 mt-4 shadow-lg">
      <h5 class="card-title text-white">Search Results</h5>
      
      <!-- Search Form -->
      <form action="{{ route('sto.search') }}" method="GET" class="mb-4">
        <div class="input-group">
          <input type="text" name="query" class="form-control" placeholder="Search for parts..." required>
          <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Search</button>
          </div>
        </div>
      </form>
      
      @if ($results->isEmpty())
        <p class="text-white">No results found.</p>
      @else
        <table class="table table-bordered text-center align-middle">
          <thead class="thead-light">
            <tr>
              <th>ID Inventory</th>
              <th>Part Name</th>
              <th>Part Number</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($results as $result)
              <tr>
                <td>{{ $result->inventory_id }}</td>
                <td>{{ $result->part_name }}</td>
                <td>{{ $result->part_number }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
      <a href="{{ route('sto.index') }}" class="btn btn-success mt-3">Back</a>
    </div>
  </div>
@endsection