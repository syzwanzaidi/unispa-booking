<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $pendingInvoices = Invoice::where('payment_status', 'Pending')->get();

        foreach ($pendingInvoices as $invoice) {
            if (!$invoice->payment()->exists()) {
                Payment::create([
                    'invoice_id' => $invoice->invoice_id,
                    'amount' => $invoice->total_price,
                    'payment_method' => $this->getRandomPaymentMethod(),
                    'payment_status' => 'Completed',
                    'payment_date' => Carbon::now()->subDays(rand(1, 30)),
                ]);
                $invoice->payment_status = 'Paid';
                $invoice->save();
            }
        }
        $latestInvoices = Invoice::orderBy('generated_at', 'desc')->take(2)->get();
        foreach ($latestInvoices as $invoice) {
            if ($invoice->payment_status === 'Paid') {
                $invoice->payment_status = 'Pending';
                $invoice->save();
                if ($invoice->payment) {
                    $invoice->payment->delete();
                }
            }
        }
    }
    private function getRandomPaymentMethod(): string
    {
        $methods = ['Cash', 'Online Banking', 'Card'];
        return $methods[array_rand($methods)];
    }
}
