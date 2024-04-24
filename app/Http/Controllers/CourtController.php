<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Court;

class CourtController extends Controller
{
    public function index(){
        $court = Court::all();
        return view('admin.courts.index', compact(['court']));
    }

    public function create(){
        return view('admin.courts.create');
    }

    public function store(Request $request){
        $court = new Court();
        $court->court_name = $request->court_name;
        $court->description = $request->description;
        $court->save();
        return redirect()->route('courts.index')
                         ->with('success', 'Court created successfully');
    }

 
    public function show($id){
        //
    }


    public function edit($id){
        $allCourt = Court::all();
        $court = Court::find($id);
        return view('admin.courts.edit', compact('court','allCourt'));
    }

 
    public function update(Request $request, $id){
        $court = Court::findOrFail($id);
        $court->court_name = $request->court_name;
        $court->description = $request->description;
        $court->save();
        return redirect()->route('courts.index')
                         ->with('success', 'Court updated successfully');
    }

    public function destroy($id){
        $court = Court::findOrFail($id);
        $court->delete();
        return redirect()->route('courts.index')
        ->with('success', 'Court deleted successfully');
    }
}
