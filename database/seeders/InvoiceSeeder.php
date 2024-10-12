<?php

namespace Database\Seeders;

use App\Models\Invoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create invoices for each customer
        Invoice::create([
            'customer_id' => 1,
            'amount' => 100.00,
            'description' => 'Web Design Services',
            'status' => 'paid',
            'invoice_date' => now(),
        ]);

        Invoice::create([
            'customer_id' => 2,
            'amount' => 250.00,
            'description' => 'App Development',
            'status' => 'pending',
            'invoice_date' => now(),
        ]);

        Invoice::create([
            'customer_id' => 3,
            'amount' => 150.00,
            'description' => 'SEO Services',
            'status' => 'overdue',
            'invoice_date' => now(),
        ]);
    }
}
