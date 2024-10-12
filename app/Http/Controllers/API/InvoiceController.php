<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Auth;
use DB;
class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        // Validation for the request
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'status' => 'required|in:paid,pending,overdue',
            'invoice_date' => 'required|date',
        ]);

        // Store the invoice
        $invoice = Invoice::create([
            'customer_id' => $request->customer_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => $request->status,
            'invoice_date' => $request->invoice_date,
        ]);

        $this->logAction('create', Auth::user(), 'Admin');

        return response()->json([
            'message' => 'Invoice created successfully',
            'invoice' => $invoice
        ], 201);
    }

    // Update an existing invoice
    public function update(Request $request, Invoice $invoice)
    {
        // Validation for the request
        $request->validate([
            'amount' => 'sometimes|required|numeric|min:0',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:paid,pending,overdue',
            'invoice_date' => 'sometimes|required|date',
        ]);

        $invoice->update($request->only(['amount', 'description', 'status', 'invoice_date']));

        $this->logAction('update', Auth::user(), Auth::user()->getRoleNames()->first());

        $customer = $invoice->customer;
        $customer->notify(new \App\Notifications\InvoiceUpdatedNotification($invoice));

        return response()->json([
            'message' => 'Invoice updated successfully',
            'invoice' => $invoice
        ], 200);
    }

    // Delete an invoice
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        $this->logAction('delete', Auth::user(), Auth::user()->getRoleNames()->first());

        return response()->json(['message' => 'Invoice deleted successfully'], 200);
    }

    protected function logAction($action, $user, $role)
    {
        \DB::table('logbook')->insert([
            'action' => $action,
            'user_id' => $user->id,
            'role' => $role,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
