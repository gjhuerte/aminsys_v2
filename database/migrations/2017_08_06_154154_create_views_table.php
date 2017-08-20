<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW rsmi_view AS
            SELECT st.date,st.reference ,st.office,st.stocknumber ,s.supplytype,s.unit ,st.issuequantity,ps.unitprice, st.issuequantity*ps.unitprice AS "amount"
            FROM supplytransaction AS st 
                JOIN supply AS s ON st.stocknumber = s.stocknumber 
                JOIN purchaseorder_supply AS ps ON ps.supplyitem = s.stocknumber;
            ');
        /*DB::statement('
        create VIEW supplyledger_view AS
            SELECT DATE_FORMAT(date, "%M") AS "Date",stocknumber AS "Stock No.",reference AS "Reference",receiptquantity AS "Receipt Qty.",receiptunitprice AS "Receipt Unit Cost",issuequantity AS "Issue Qty",issueunitprice AS "Issue Unit Cost",balancequantity AS "Balance Qty.",daystoconsume AS "Days to Consume" 
            FROM supplyledger
            GROUP BY DATE_FORMAT(date, "%M");
            ');*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW rsmi_view;');/*
        DB::statement('DROP VIEW supplyledger_view;');*/
    }
}
