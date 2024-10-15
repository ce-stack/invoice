<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Log;
use Illuminate\Http\Request;
use Auth;
class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $invoices = Invoice::with('customer')->paginate($perPage);
        return view('invoices.index', compact('invoices', 'perPage'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('invoices.create' , compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'status' => 'required|in:paid,pending,overdue',
            'invoice_date' => 'required|date',
        ]);

        $invoice = Invoice::create($validated);

        Log::create([
            'user_id' => auth()->id(),
            'invoice_id' => $invoice->id,
            'action' => 'created',
            'role' => auth()->user()->getRoleNames()->first(),
        ]);

        return redirect()->route('invoices.index');
    }

    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'status' => 'required|in:paid,pending,overdue',
            'invoice_date' => 'required|date',
        ]);

        $invoice->update($validated);

        Log::create([
            'user_id' => auth()->id(),
            'invoice_id' => $invoice->id,
            'action' => 'updated',
            'role' => auth()->user()->getRoleNames()->first(),
        ]);

        // Send Email Notification
        $invoice->customer->notify(new \App\Notifications\InvoiceUpdatedNotification($invoice));

        return redirect()->route('invoices.index');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        Log::create([
            'user_id' => auth()->id(),
            'invoice_id' => $invoice->id,
            'action' => 'deleted',
            'role' => auth()->user()->getRoleNames()->first(),
        ]);

        return redirect()->route('invoices.index');
    }
}
