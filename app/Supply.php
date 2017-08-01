<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;
class Supply extends Model{

	protected $table = 'supply';
	protected $fillable = ['stocknumber','entityname','fundcluster','supplytype','unit','unitprice','reorderpoint'];
	protected $primaryKey = 'stocknumber';
	public $incrementing = false;
	public $timestamps = true;
	public static $rules = array(
	'Stock Number' => 'required|unique:supply,stocknumber',
	'Entity Name' => 'required',
	'Fund Cluster' => '',
	'Supply Type' => 'required|unique:supply,supplytype',
	'Unit' => 'required',
	'Unit Price' => '',	
	'Reorder Point' => 'required|integer'
	);

	public static $updateRules = array(
	'Stock Number' => '',
	'Entity Name' => '',
	'Fund Cluster' => '',
	'Supply Type' => '',
	'Unit' => '',
	'Unit Price' => '',
	'Reorder Point' => 'integer'
	);

	public function supplytransaction()
	{
		return $this->hasMany('App\SupplyTransaction');
	}

	public function getUnitPriceAttribute($value)
	{
		return number_format($value,2,'.',',');
	}

}
