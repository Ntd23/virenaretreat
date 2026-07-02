<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropSepayUnmatchedTransactionsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('sepay_unmatched_transactions');
    }

    public function down()
    {
        // Bảng giao dịch SePay không khớp đã được bỏ khỏi luồng thanh toán quảng cáo.
    }
}
