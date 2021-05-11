<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WalletTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->id('id_transaction');
            $table->string('id_coin')->default(0);                   // 90
            $table->string('usd_buyed_amount')->default(0);          // 50 $
            $table->string('buyed_coins_amount')->default(0);        // 50/6000 BTC
            $table->string('buyed_coins_usd_price')->default(0);     // 6000 $
            $table->string('operation')->default(0);                 // buy/sell
            $table->timestamps();

            $table->foreignId('id_wallet')->constrained('wallet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
