<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_sales', function (Blueprint $table) {
            $table->bigIncrements('id_transaksi_sales');
            $table->string('sales_type',50);
            $table->string('invoice_id',100);
            $table->date('invoice_date');
            $table->string('transaksi_tipe',100);
            $table->date('term_until');
            $table->integer('sales_id');
            $table->integer('customer_id');
            $table->text('note')->nullable();
            $table->integer('totalsales');
            $table->double('diskon')->nullable();
            $table->integer('id_user');
            $table->string('status',20)->nullable();
            $table->integer('approve',20)->nullable();
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
        Schema::dropIfExists('transaksi_sales');
    }
}
