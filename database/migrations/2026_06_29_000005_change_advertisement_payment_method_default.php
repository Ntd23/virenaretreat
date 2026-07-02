<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeAdvertisementPaymentMethodDefault extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('advertisement_payments') || !Schema::hasColumn('advertisement_payments', 'payment_method')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE advertisement_payments MODIFY payment_method VARCHAR(50) NOT NULL DEFAULT ''");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE advertisement_payments ALTER COLUMN payment_method SET DEFAULT ''");
        }

        DB::table('advertisement_payments')
            ->where('payment_method', 'sepay')
            ->where('payment_status', 'pending')
            ->where(function ($query) {
                $query->whereNull('paid_amount')->orWhere('paid_amount', 0);
            })
            ->whereNull('sepay_transaction_id')
            ->update([
                'payment_method' => '',
                'qr_url' => null,
                'sepay_gateway' => null,
            ]);
    }

    public function down()
    {
        if (!Schema::hasTable('advertisement_payments') || !Schema::hasColumn('advertisement_payments', 'payment_method')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE advertisement_payments MODIFY payment_method VARCHAR(50) NOT NULL DEFAULT 'sepay'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE advertisement_payments ALTER COLUMN payment_method SET DEFAULT 'sepay'");
        }
    }
}
