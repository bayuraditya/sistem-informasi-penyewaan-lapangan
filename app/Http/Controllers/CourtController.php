<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Court;

class CourtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $court = Court::all();
        return view('admin.courts.index', compact(['court']));
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.courts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $court = new Court();
        $court->court_name = $request->court_name;
        $court->description = $request->description;

        $court->save();

        return redirect()->route('courts.index')
                         ->with('success', 'Court created successfully');
   
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $allCourt = Court::all();
        $court = Court::find($id);
        return view('admin.courts.edit', compact('court','allCourt'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $court = Court::findOrFail($id);
        $court->court_name = $request->court_name;
        $court->description = $request->description;

        $court->save();

        return redirect()->route('courts.index')
                         ->with('success', 'Court updated successfully');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $court = Court::findOrFail($id);
        $court->delete();


        return redirect()->route('courts.index')
        ->with('success', 'Court deleted successfully');

    }
}
