<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;
class SupplyLedger extends Model{

	protected $table = 'supplyledger';

	public $timestamps = true;
	protected $fillable = ['date','stocknumber','reference','receiptquantity','receiptunitprice','issuequantity','issueunitprice','daystoconsume'];

	protected $primaryKey = 'id';

	public static $receiptRules = array(
	'Date' => 'required',
	'Stock Number' => 'required',
	'Purchase Order' => 'required',
	'Receipt Quantity' => 'required',
	'Receipt Unit Price' => 'required',
	'Days To Consume' => ''
	);

	public static $issueRules = array(
	'Date' => 'required',
	'Stock Number' => 'required',
	'Requisition and Issue Slip' => 'required',
	'Issue Quantity' => 'required',
	'Issue Unitprice' => 'required',
	'Days To Consume' => ''
	);

	public static $rules = array(
	'Date' => 'required',
	'Stock Number' => 'required',
	'Reference' => 'sometimes',
	'Receipt Quantity' => 'integer',
	'Receipt Unitprice' => '',
	'Issue Quantity' => 'integer',
	'Issue Unitprice' => '',
	'Days To Consume' => ''
	);

	public static $updateRules = array(
	'Date' => 'required',
	'Stock Number' => '',
	'Reference' => '',
	'Receipt Quantity' => 'integer',
	'Receipt Unitprice' => '',
	'Issue Quantity' => 'integer',
	'Issue Unitprice' => '',
	'Days To Consume' => ''
	/*
	'Date' => 'required',
	'Stock Number' => 'required',
	'Reference' => 'required',
	'Referencetag' => 'required',
	'Quantity' => 'integer',
	'Balance Quantity' => 'integer',
	'Days To Consume' => ''
	*/
	);

	public function getDateAttribute($value)
	{
		return Carbon\Carbon::parse($value)->format('F Y');
	}

}
