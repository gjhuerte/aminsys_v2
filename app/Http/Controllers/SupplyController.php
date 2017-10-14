<?php
namespace App\Http\Controllers;
	
use App\Supply;
use Carbon;
use Session;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
class SupplyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Request::ajax())
		{
			return json_encode([
				'data' => Supply::all()
			]);
		}
		return view('maintenance.supply.index')
                ->with('title','Supply Maintenance');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('maintenance.supply.create')
                ->with('title','Supply Maintenance');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
		$entityname = $this->sanitizeString(Input::get('entityname'));
		$description = $this->sanitizeString(Input::get('description'));
		$unit = $this->sanitizeString(Input::get('unit'));

		if(Input::has('price'))
		{
			$price = $this->sanitizeString(Input::get('price'));
		} else 
			$price = null;

		$reorderpoint = $this->sanitizeString(Input::get("reorderpoint"));
		$supplytype = $this->sanitizeString(Input::get('supplytype'));

		$validator = Validator::make([
			'Stock Number' => $stocknumber,
			'Entity Name' => $entityname,
			'Supply Type' => $supplytype,
			'Unit' => $unit,
			'Reorder Point' => $reorderpoint
		],Supply::$rules);

		if($validator->fails())
		{
			return redirect('maintenance/supply/create')
					->withInput()
					->withErrors($validator);
		}

		$supply = new Supply;
		$supply->stocknumber = $stocknumber;
		$supply->entityname = $entityname;
		$supply->supplytype = $supplytype;
		$supply->unit = $unit;
		$supply->reorderpoint = $reorderpoint;
		$supply->save();

		Session::flash('success-message','Supply added to inventory');
		return redirect('maintenance/supply');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Request::ajax())
		{
			$supply = Supply::find($id);
			return json_encode([ 'data' => $supply ]);
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$supply = Supply::find($id);
		return view('maintenance.supply.edit')
				->with('supply',$supply)
                ->with('title','Supply Maintenance');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
		$entityname = $this->sanitizeString(Input::get('entityname'));
		$description = $this->sanitizeString(Input::get('description'));
		$unit = $this->sanitizeString(Input::get('unit'));
		$reorderpoint = $this->sanitizeString(Input::get("reorderpoint"));
		$supplytype = $this->sanitizeString(Input::get('supplytype'));

		$validator = Validator::make([
			'Stock Number' => $stocknumber,
			'Entity Name' => $entityname,
			'Supply Type' => $supplytype,
			'Unit' => $unit,
			'Reorder Point' => $reorderpoint
		],Supply::$updateRules);

		if($validator->fails())
		{
			return redirect("maintenance/supply/$id/edit")
					->withInput()
					->withErrors($validator);
		}

		$supply = Supply::find($id);
		$supply->stocknumber = $stocknumber;
		$supply->entityname = $entityname;
		$supply->supplytype = $supplytype;
		$supply->unit = $unit;
		$supply->reorderpoint = $reorderpoint;
		$supply->save();

		Session::flash('success-message','Supply information update');
		return redirect('maintenance/supply');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Request::ajax())
		{
			$supply = Supply::find($id);
			$supply->delete();
			return json_encode('success');
		}

		try{
			$supply = Supply::find($id);
			$supply->delete();
			Session::flash('success-message','Office Removed');	
		} catch (Exception $e) {
			Session::flash('error-message','Problem Encountered While Processing Your Data');
		} 

		return redirect('maintenance/supply');
	}


}
