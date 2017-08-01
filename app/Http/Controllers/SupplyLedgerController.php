<?php
namespace App\Http\Controllers;
	
use App\SupplyLedger;
use App\SupplyTransaction;
use App\Supply;
use Carbon;
use Session;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
class SupplyLedgerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($stocknumber)
	{
		if(Request::ajax())
		{
			return json_encode([
				'data' => SupplyLedger::where('stocknumber','=',$stocknumber)
										->groupBy('date')
										->select(
												'date',
												DB::raw('sum(receiptquantity) as receiptquantity'),
												DB::raw('avg(receiptunitprice) as receiptunitprice'),
												DB::raw('sum(issuequantity) as issuequantity'),
												DB::raw('avg(issueunitprice) as issueunitprice')
												)
										->get()
			]);
		}
		$supply = Supply::find($stocknumber);
		return view('supplyledger.index')
				->with('supply',$supply);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
		$supply = Supply::find($id);
		return view('supplyledger.create')
				->with('supply',$supply);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
		$reference = $this->sanitizeString(Input::get('reference'));
		$date = $this->sanitizeString(Input::get('date'));
		$receiptquantity = $this->sanitizeString(Input::get('quantity'));
		$daystoconsume = $this->sanitizeString(Input::get('daystoconsume'));

		$supply = Supply::find($stocknumber);
		$receiptunitprice = $supply->unitprice;

		$validator = Validator::make([	
			'Date' => $date,
			'Stock Number' => $stocknumber,
			'Purchase Order' => $reference,
			'Receipt Quantity' => $receiptquantity,
			'Receipt Unit Price' => $receiptunitprice,
			'Days To Consume' => $daystoconsume
		],SupplyLedger::$receiptRules);

		if($validator->fails())
		{
			return redirect("inventory/supply/$stocknumber/supplyledger/create")
					->withInput()
					->withErrors($validator);
		}

		$transaction = new SupplyLedger;
		$transaction->stocknumber = $stocknumber;
		$transaction->reference = $reference;
		$transaction->date = Carbon\Carbon::parse($date);
		$transaction->receiptquantity = $receiptquantity;
		$transaction->receiptunitprice = $receiptunitprice;
		$transaction->issueunitprice = $receiptunitprice;
		$transaction->daystoconsume = $daystoconsume;
		$transaction->save();

		Session::flash('success-message','Operation Successful');
		return redirect("inventory/supply/$stocknumber/supplyledger");
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id,$month)
	{
		if(Request::ajax())
		{
			$transaction = SupplyTransaction::with('supply')->where('stocknumber','=',$this->sanitizeString($id))->get();
			return json_encode([ 'data' => $transaction ]);
		}

		$month = Carbon\Carbon::parse($month);

		$supply = Supply::find($id);
		$supplyledger = SupplyLedger::whereBetween('date',array($month->startOfMonth()->toDateString(),$month->endOfMonth()->toDateString()))
			->where('stocknumber','=',$this->sanitizeString($id))
			->get();
		return view('supplyledger.show')
				->with('supplyledger',$supplyledger)
				->with('month',$month->toFormattedDateString())
				->with('supply',$supply);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return redirect('inventory/supply/ledger');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		return redirect('inventory/supply');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
		$reference = $this->sanitizeString(Input::get('reference'));
		$date = $this->sanitizeString(Input::get('date'));
		$issuequantity = $this->sanitizeString(Input::get('quantity'));
		$daystoconsume = $this->sanitizeString(Input::get('daystoconsume'));

		$supply = Supply::find($stocknumber);
		$issueunitprice = $supply->unitprice;


		$validator = Validator::make([	
			'Date' => $date,
			'Stock Number' => $stocknumber,
			'Requisition and Issue Slip' => $reference,
			'Issue Quantity' => $issuequantity,
			'Issue Unitprice' => $issueunitprice,
			'Days To Consume' => $daystoconsume
		],SupplyLedger::$issueRules);

		if($validator->fails())
		{
			return redirect("inventory/supply/$stocknumber/supplyledger/release")
					->withInput()
					->withErrors($validator);
		}

		$transaction = new SupplyLedger;
		$transaction->stocknumber = $stocknumber;
		$transaction->reference = $reference;
		$transaction->date = Carbon\Carbon::parse($date);
		$transaction->receiptunitprice = $issueunitprice;
		$transaction->issuequantity = $issuequantity;
		$transaction->issueunitprice = $issueunitprice;
		$transaction->daystoconsume = $daystoconsume;
		$transaction->save();

		Session::flash('success-message','Operation Successful');
		return redirect("inventory/supply/$stocknumber/supplyledger");
	}

	public function releaseForm($id)
	{
		$supply = Supply::find($id);
		$balance = SupplyLedger::where('stocknumber','=',$supply->stocknumber)->get();
		$balance = $balance->sum('receiptquantity') - $balance->sum('issuequantity');
		return view('supplyledger.release')
				->with('supply',$supply)
				->with('balance',$balance);
	}

	public function checkifexisting()
	{
		if(Request::ajax())
		{
			try{
				$quantity = $this->sanitizeString(Input::get('quantity'));
				$reference = $this->sanitizeString(Input::get('reference'));
				$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
				$month = $this->sanitizeString(Input::get('date'));

				$month = Carbon\Carbon::parse($month);

				$supplyledger = SupplyLedger::where('issuequantity','=',$quantity)
														->where('reference','=',$reference)
														->where('stocknumber','=',$stocknumber)
														->whereBetween('date',array($month->startOfMonth()->toDateString(),$month->endOfMonth()->toDateString()))
														->get();

				if($supplyledger->isNotEmpty())
				{ 
					return json_encode('duplicate');
				}

				return json_encode('success');
			} catch (Exception $e) {
				return json_encode('error');
			}
		} 
	}

	public function copy()
	{
		if(Request::ajax())
		{
			try{
				$daystoconsume = $this->sanitizeString(Input::get('daystoconsume'));
				$issuequantity = $this->sanitizeString(Input::get('quantity'));
				$reference = $this->sanitizeString(Input::get('reference'));
				$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
				$date = $this->sanitizeString(Input::get('date'));


				$supply = Supply::find($stocknumber);
				$issueunitprice = $supply->unitprice;


				$validator = Validator::make([	
					'Date' => $date,
					'Stock Number' => $stocknumber,
					'Requisition and Issue Slip' => $reference,
					'Issue Quantity' => $issuequantity,
					'Issue Unitprice' => $issueunitprice,
					'Days To Consume' => $daystoconsume
				],SupplyLedger::$issueRules);

				if($validator->fails())
				{
					return json_encode('error');
				}

				$transaction = new SupplyLedger;
				$transaction->stocknumber = $stocknumber;
				$transaction->reference = $reference;
				$transaction->date = Carbon\Carbon::parse($date);
				$transaction->receiptunitprice = $issueunitprice;
				$transaction->issuequantity = $issuequantity;
				$transaction->issueunitprice = $issueunitprice;
				$transaction->daystoconsume = $daystoconsume;
				$transaction->save();

				return json_encode('success');
			} catch (Exception $e) {
				return json_encode('error');
			}
		} 
	}

}
