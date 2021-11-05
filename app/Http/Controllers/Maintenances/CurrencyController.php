<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Currency;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies = Currency::get();
        return view('maintenances.currencies.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('maintenances.currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:200',
            'code' => 'required|max:200',
            'symbol' => 'required|max:5',
        ]);

        Currency::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'symbol' => $request->input('symbol')
        ]);

        return redirect()->route('currencies.create')->with('add_currency_success', 'Added currency has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        return view('maintenances.currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'name' => 'required|max:200',
            'code' => 'required|max:200',
            'symbol' => 'required|max:5',
        ]);

        $currency->update([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'symbol' => $request->input('symbol'),
        ]);

        return redirect()->route('currencies.index')->with('edit_currency_success', 'Edit in currency has been saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        //
        $currency->delete();

        return redirect()->route('currencies.index')->with('edit_currency_success', 'Currency has been deleted.');
    }

    public function list(){
        $list = Currency::orderBy('code')->get();
        return $list;
    }
}
