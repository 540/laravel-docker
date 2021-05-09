<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_coins', function (Blueprint $table) {
            $table->primary(['wallet_id', 'coin_id']);
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('coin_id');
            $table->float('amount');
            $table->float('value_usd');
            $table->foreign('wallet_id')->references('wallet_id')->on('wallets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('coin_id')->references('coin_id')->on('coins')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_coins');
    }
}
