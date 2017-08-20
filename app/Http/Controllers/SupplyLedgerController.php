<?php
namespace App\Http\Controllers;
	
use App\SupplyLedger;
use App\SupplyTransaction;
use App\PurchaseOrderSupply;
use App\Supply;
use Auth;
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
				'data' => SupplyLedger::stockNumber($stocknumber)
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

		return view('supplyledger.index')
				->with('supply',Supply::find($stocknumber));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
		return view('supplyledger.create')
				->with('supply',Supply::find($id));
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

		$validator = Validator::make([	
			'Date' => $date,
			'Stock Number' => $stocknumber,
			'Purchase Order' => $reference,
			'Receipt Quantity' => $receiptquantity,
			'Days To Consume' => $daystoconsume
		],SupplyLedger::$receiptRules);

		if($validator->fails())
		{
			return redirect("inventory/supply/$stocknumber/supplyledger/create")
					->withInput()
					->withErrors($validator);
		}

		SupplyLedger::receipt($date,$stocknumber,$reference,$receiptquantity,$daystoconsume);

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
			$transaction = SupplyTransaction::with('supply')->stockNumber($id)->get();
			return json_encode([ 'data' => $transaction ]);
		}

		$supplyledger = SupplyLedger::month($month)->stockNumber($id)->get();
		return view('supplyledger.show')
				->with('supplyledger',$supplyledger)
				->with('month',Carbon\Carbon::parse($month)->toFormattedDateString())
				->with('supply',Supply::find($id));
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
	 * Show the form for releasing
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function releaseForm($id)
	{
		$id = $this->sanitizeString($id);
		return view('supplyledger.release')
				->with('supply',Supply::find($id))
				->with('balance',SupplyLedger::getRemainingBalance($id));
	}

	public function release()
	{

		if(Request::ajax())
		{
			$issuequantity = $this->sanitizeString(Input::get('quantity'));
			$reference = $this->sanitizeString(Input::get('reference'));
			$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
			$date = $this->sanitizeString(Input::get('date'));
			$daystoconsume = "";

			SupplyLedger::issue($date,$stocknumber,$reference,$issuequantity,$daystoconsume);
		} 
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

		$validator = Validator::make([	
			'Date' => $date,
			'Stock Number' => $stocknumber,
			'Requisition and Issue Slip' => $reference,
			'Issue Quantity' => $issuequantity,
			'Days To Consume' => $daystoconsume
		],SupplyLedger::$issueRules);

		if($validator->fails())
		{
			return redirect("inventory/supply/$stocknumber/supplyledger/release")
					->withInput()
					->withErrors($validator);
		}

		SupplyLedger::receipt($date,$stocknumber,$reference,$issuequantity,$daystoconsume);
		Session::flash('success-message','Operation Successful');
		return redirect("inventory/supply/$stocknumber/supplyledger");
	}

	public function batchAcceptForm()
	{
		return View('supplyledger.batch.accept');
	}

	public function batchAccept()
	{
		$purchaseorder = $this->sanitizeString(Input::get('purchaseorder'));
		$date = $this->sanitizeString(Input::get('date'));
		$office = $this->sanitizeString(Input::get('office'));
		$daystoconsume = $this->sanitizeString(Input::get("daystoconsume"));
		$stocknumber = Input::get("stocknumber");
		$receiptquantity = Input::get("quantity");

		foreach($stocknumber as $_stocknumber)
		{
			$validator = Validator::make([	
				'Stock Number' => $_stocknumber,
				'Purchase Order' => $purchaseorder,
				'Date' => $date,
				'Receipt Quantity' => $receiptquantity["$_stocknumber"],
				'Office' => $office,
				'Days To Consume' => $daystoconsume
			],SupplyLedger::$receiptRules);

			if($validator->fails())
			{
				return redirect("inventory/supply/supplyledger/batch/form/accept")
						->with('total',count($stocknumber))
						->with('stocknumber',$stocknumber)
						->with('quantity',$receiptquantity)
						->with('daystoconsume',$daystoconsume)
						->withInput()
						->withErrors($validator);
			}
		}

		foreach($stocknumber as $_stocknumber)
		{
			SupplyLedger::receipt($date,$_stocknumber,$purchaseorder,$receiptquantity["$_stocknumber"],$daystoconsume);
		}

		Session::flash('success-message','Supplies Accepted');
		return redirect('inventory/supply');
	}

	public function batchReleaseForm()
	{
		return View('supplyledger.batch.release');
	}

	public function batchRelease()
	{
		$reference = $this->sanitizeString(Input::get('reference'));
		$date = $this->sanitizeString(Input::get('date'));
		$office = $this->sanitizeString(Input::get('office'));
		$daystoconsume = $this->sanitizeString(Input::get("daystoconsume"));
		$stocknumber = Input::get("stocknumber");
		$issuequantity = Input::get("quantity");

		foreach($stocknumber as $_stocknumber)
		{
			$validator = Validator::make([	
				'Stock Number' => $stocknumber,
				'Requisition and Issue Slip' => $reference,
				'Date' => $date,
				'Issue Quantity' => $issuequantity["$_stocknumber"],
				'Office' => $office,
				'Days To Consume' => $daystoconsume
			],SupplyLedger::$issueRules);

			if($validator->fails())
			{
				return redirect("inventory/supply/supplyledger/batch/form/release")
						->with('total',count($stocknumber))
						->with('stocknumber',$stocknumber)
						->with('quantity',$receiptquantity)
						->with('daystoconsume',$daystoconsume)
						->withInput()
						->withErrors($validator);
			}
		}

		foreach($stocknumber as $_stocknumber)
		{
			SupplyLedger::issue($date,$_stocknumber,$reference,$office,$issuequantity["$_stocknumber"],$daystoconsume);
		}

		Session::flash('success-message','Supplies Released');
		return redirect('inventory/supply');
	}

	public function checkIfSupplyLedgerExists()
	{
		if(Request::ajax())
		{
			$quantity = $this->sanitizeString(Input::get('quantity'));
			$reference = $this->sanitizeString(Input::get('reference'));
			$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
			$month = $this->sanitizeString(Input::get('date'));
			return json_encode(SupplyLedger::isExistingRecord($reference,$stocknumber,$quantity,$month));
		} 
	}

}
