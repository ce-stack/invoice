@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Invoices</h1>

    <form action="{{ route('invoices.index') }}" method="GET">
        <div class="form-group">
            <label for="per_page">Invoices per page:</label>
            <select name="per_page" id="per_page" class="form-control" onchange="this.form.submit()">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Status</th>
                <th>Invoice Date</th>
                <th>Actions</th> <!-- Add a new Actions column -->
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->customer->name }}</td>
                    <td>{{ $invoice->amount }}</td>
                    <td>{{ $invoice->description }}</td>
                    <td>{{ ucfirst($invoice->status) }}</td>
                    <td>{{ $invoice->invoice_date }}</td>
                    <td>
                        <!-- Edit button -->
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit">Edit</i> <!-- FontAwesome edit icon -->
                        </a>

                        <!-- Delete button -->
                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this invoice?')">
                                <i class="fas fa-trash-alt">Delete</i> <!-- FontAwesome delete icon -->
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $invoices->appends(['per_page' => $perPage])->links() }}
    </div>
</div>
@endsection
