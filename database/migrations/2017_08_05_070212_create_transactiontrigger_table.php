
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactiontriggerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('

            CREATE TRIGGER `accept_supply_trg` AFTER 
            INSERT ON `supplytransaction`
            FOR EACH ROW 
                UPDATE purchaseorder_supply
                SET receivedquantity = receivedquantity + new.receiptquantity,reference = concat(reference,",",new.reference), date = concat(date,",",new.date),updated_at = now()
                WHERE supplyitem = new.stocknumber AND purchaseorderno = new.purchaseorderno AND new.issuequantity = 0 OR NULL;
            ');
        DB::unprepared('

            CREATE DEFINER=`root`@`localhost` PROCEDURE `bal_update`
            (IN `_user_id` VARCHAR(100), IN `_date` DATE, IN `_stocknumber` VARCHAR(100), IN `_purchaseorderno` VARCHAR(100), IN `_reference` VARCHAR(100), IN `_office` VARCHAR(100), IN `_receipt` INT(11), IN `_issue` INT(11), IN `_daystoconsume` VARCHAR(100))
            NO SQL
                BEGIN

                    SET @endbalance = (
                            SELECT balancequantity 
                            FROM supplytransaction 
                            WHERE date <= _date AND stocknumber = _stocknumber
                            ORDER BY date DESC , id DESC
                            LIMIT 1
                        );
                        
                    IF @endbalance IS null THEN
                        SET @endbalance = 0; 
                    END IF;
                        
                    SET @balance = @endbalance +_receipt - _issue;

                    INSERT INTO supplytransaction VALUES(null,_user_id,_date,_stocknumber,_purchaseorderno,_reference,_office,_receipt,_issue,@balance,_daystoconsume,NOW(),NOW());


                    UPDATE supplytransaction
                        SET balancequantity = balancequantity + _receipt - _issue ,updated_at = now()
                        WHERE stocknumber = _stocknumber AND date > _date;

                END'
                );
        DB::unprepared('
  CREATE DEFINER=`root`@`localhost` PROCEDURE `ledger_update`(IN `_user_id` VARCHAR(100), IN `_date` DATE, IN `_stocknumber` VARCHAR(100), IN `_reference` VARCHAR(100), IN `_receipt` INT(11), IN `_issue` INT(11), IN `_daystoconsume` VARCHAR(100))
                        NO SQL
                    BEGIN

                    SET @endbalance = (
                            SELECT balancequantity 
                            FROM supplyledger 
                            WHERE date <= _date AND stocknumber = _stocknumber
                            ORDER BY date DESC , id DESC
                            LIMIT 1
                        );
                        
                    IF @endbalance IS null THEN
                        SET @endbalance = 0; 
                    END IF;
                    

                    SET @reference = (
                                    SELECT purchaseorderno
                                    FROM purchaseorder_supply
                                    WHERE supplyitem = _stocknumber
                                    LIMIT 1
                                     );

                    SET @price2 = (
                            SELECT AVG(unitprice) 
                            FROM purchaseorder_supply 
                            WHERE supplyitem = _stocknumber
                        );
                        
                    IF @price2 IS NULL THEN
                        SET @price2 = 0;
                    END IF;

                    IF _receipt IS NOT NULL OR _receipt != 0 THEN
                    SET @price = (
                            SELECT unitprice 
                            FROM purchaseorder_supply 
                            WHERE supplyitem = _stocknumber
                            LIMIT 1                 
                        );
                    ELSE
                    SET @price = @price2;
                    END IF;

                    SET @balance = @endbalance +_receipt - _issue;

                    INSERT INTO supplyledger VALUES(
                        null,
                        _user_id,
                        _date,
                        _stocknumber,
                        _reference,
                        _receipt,
                        @price,
                        _issue,
                        @price2,
                        @balance,
                        _daystoconsume,
                        NOW(),
                        NOW()
                                                    );

                    UPDATE supplytransaction
                        SET balancequantity = balancequantity + _receipt - _issue ,updated_at = now()
                        WHERE stocknumber = _stocknumber AND date > _date;

                END
                       ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS `accept_supply_trg`;');
        DB::unprepared('DROP PROCEDURE IF EXISTS `bal_update`;');
        DB::unprepared('DROP PROCEDURE IF EXISTS `ledger_update`;');
        
    }
}