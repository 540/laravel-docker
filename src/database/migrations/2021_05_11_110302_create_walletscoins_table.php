<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletscoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('walletscoins', function (Blueprint $table) {
            $table->string('wallet_id');
            $table->string('coin_id');
            $table->float('amount');

            $table->primary(['wallet_id', 'coin_id']);
            $table->foreign('wallet_id')->references('wallet_id')->on('wallets');
            $table->foreign('coin_id')->references('coin_id')->on('coins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('walletscoins');
    }
}
