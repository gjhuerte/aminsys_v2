<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplytransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 SELECT st.stocknumber,s.entityname,s.fundcluster,s.supplytype,s.unit,st.receiptquantity-st.issuequantity AS 'Balance Quantity',s.reorderpoint
	  FROM supply AS s JOIN supplytransaction AS st ON s.stocknumber = st.stocknumber 
	 GROUP BY stocknumber
	 * @return void
	 */
	public function up()
	{
		Schema::create('supplytransaction', function(Blueprint $table)
		{
			$table->increments('id');
            //$table->integer('name')->unsigned();for user
			$table->date('date');							
			$table->string('stocknumber');
			$table->foreign('stocknumber')->references('stocknumber')->on('supply')
										->onDelete('cascade')
										->onUpdate('cascade');
			$table->string('reference',100);					
			$table->string('office',100)->nullable();
            $table->integer('receiptquantity')->nullable();//receive
            $table->integer('issuequantity')->nullable();//release
            $table->integer('balancequantity')->nullable(); 
            $table->string('daystoconsume',100)->nullable();
			$table->timestamps();
			/*
			$table->string('reference',100);
			$table->string('referencetag',100);
            $table->integer('quantity')->nullable();//receive
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
		Schema::drop('supplytransaction');
	}

}
