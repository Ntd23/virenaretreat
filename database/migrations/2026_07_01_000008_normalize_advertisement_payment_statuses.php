<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class NormalizeAdvertisementPaymentStatuses extends Migration
{
    public function up()
    {
        DB::table('advertisement_payments')
            ->whereIn('payment_status', ['underpaid', 'overpaid'])
            ->update([
                'payment_status' => 'waiting_confirm',
                'updated_at' => now(),
            ]);
    }

    public function down()
    {
        // This data cleanup is intentionally not reversible.
    }
}
