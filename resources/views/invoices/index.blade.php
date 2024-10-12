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
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $invoices->appends(['per_page' => $perPage])->links() }}
    </div>
</div>
@endsection
