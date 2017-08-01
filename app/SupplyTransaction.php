<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;
class SupplyTransaction extends Model{

	protected $table = 'supplytransaction';

	public $timestamps = true;
	protected $fillable = ['date','stocknumber','reference','receiptquantity','issuequantity','office','daystoconsume'];	
	protected $primaryKey = 'id';
	public static $rules = array(
	'Date' => 'required',
	'Stock Number' => 'required',
	'Reference' => 'required',
	'Office' => '',
	'Receipt Quantity' => 'integer',
	'Issue Quantity' => 'integer',
	'Days To Consume' => ''
	);

	public static $receiptRules = array(
	'Date' => 'required',
	'Stock Number' => 'required',
	'Purchase Order' => 'required',
	'Office' => '',
	'Receipt Quantity' => 'required|integer',
	'Days To Consume' => ''
	);

	public static $issueRules = array(
	'Date' => 'required',
	'Stock Number' => 'required',
	'Requisition and Issue Slip' => 'required',
	'Office' => '',
	'Issue Quantity' => 'required|integer',
	'Days To Consume' => ''
	);

	public static $updateRules = array(
	'Date' => '',
	'Stock Number' => '',
	'Reference' => '',
	'Office' => '',
	'Receipt Quantity' => 'integer',
	'Issue Quantity' => 'integer',
	'Days To Consume' => ''
	);

	public function getDateAttribute($value)
	{
		// return Carbon\Carbon::parse($value)->format('F d Y');
		return Carbon\Carbon::parse($value)->toFormattedDateString();
	}

	public function supply()
	{
		return $this->belongsTo('App\Supply','stocknumber','stocknumber');
	}

}
	