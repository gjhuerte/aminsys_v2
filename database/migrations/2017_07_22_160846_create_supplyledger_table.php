<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplyledgerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('supplyledger', function(Blueprint $table)
		{
			$table->increments('id');
            //$table->string('');for user
			$table->date('date');//monthand year only, should it be string?							
			$table->string('stocknumber');
			$table->foreign('stocknumber')->references('stocknumber')->on('supply')
										->onDelete('cascade')
										->onUpdate('cascade'); //tama
			$table->string('reference',100)->nullable();					
            $table->integer('receiptquantity')->nullable();//receive
            $table->decimal('receiptunitprice');
            $table->integer('issuequantity')->nullable();//release
            $table->decimal('issueunitprice');
            $table->integer('balancequantity')->nullable();//release
            $table->string('daystoconsume',100)->nullable();
			$table->timestamps();
			/*
			$table->string('reference',100);
			$table->string('referencetag',100);
            $table->integer('quantity')->nullable();//receive
            $table->integer('unitprice');
            $table->integer('balancequantity')->nullable(); 
            $table->string('daystoconsume',100)->nullable();
			$table->timestamps();
			*/
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('supplyledger');
	}

}
