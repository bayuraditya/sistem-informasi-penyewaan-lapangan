<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Court;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CourtController extends Controller
{
    public function index(){
        $court = Court::all();
        $user = Auth::user();
        return view('admin.courts.index', compact('user','court'));
    }

    public function create(){
        $user = Auth::user();

        return view('admin.courts.create',compact('user'));
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
        $user = Auth::user();
        return view('admin.courts.edit', compact('court','allCourt','user'));
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
