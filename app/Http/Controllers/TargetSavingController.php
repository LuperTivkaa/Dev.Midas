<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Targetsr;
use App\TargetSaving;
use App\User;
use Carbon\Carbon;
use App\Exports\TargetSavingExport;
use App\Imports\TargetSavingImport;
use Maatwebsite\Excel\Facades\Excel;

class TargetSavingController extends Controller
{
    //

    public function index(){
        $title ='Active Target Saving Deductions';
        $ts = Targetsr::where('status','Active')->oldest()->with(['user'])
        ->paginate(20);
        return view('TargetSaving.index',compact('ts','title'));
    }


    //Export
    public function export(){
        $jstNow = Carbon::now()->toDateString();
        $fileName = 'MIDAS_TARGETSAVINGS_'.$jstNow.'.xlsx';
        return Excel::download(new TargetSavingExport(), $fileName);
    }

    //create upload form
    public function tsUpload(){
        $title = 'Upload User Target Savings';
      return view('TargetSaving.tsUpload',compact('title'));
    }


    public function tsImport(){

        try{
      Excel::import(new TargetSavingImport(),request()->file('ts_import'));
        }catch(\Exception $ex){
            toastr()->error('An error has occurred trying to import target savings');
                return back();
        }catch(\Error $ex){
            toastr()->error('Something bad has happened');
            return back();
        }
      
        toastr()->success('Target savings uploaded successfully!');
        //redirect to listing page order by latest
        return redirect('/recent/targetsavings');
    
    }

//Recent target savings uploads
public function recentTargetSavings(){ 
    $title = 'Recent Target Saving';
    // List recent target saving uploads 
    $recentTs= TargetSaving::with('user')->latest()->paginate(100);
    return view('TargetSaving.recentTargetSaving',compact('recentTs','title'));

}

public function edit($id){ 
    $title = 'Edit Target Saving';
    // List recent u
    $tSaving = TargetSaving::find($id);
    return view('TargetSaving.editTargetSaving',compact('tSaving','title'));

}

//Update Target Saving Record
public function update(Request $request, $id){
    $targetsaving = TargetSaving::find($id);
    $this->validate(request(), [
        'amount' =>'required|numeric|between:0.00,999999999.99',
        ]);

            $targetsaving->amount = $request['amount'];
            //$targetsaving->created_by = auth()->id();
            $targetsaving->save();
            if($targetsaving->save()) {
                toastr()->success('Target saving record has been edited successfully!');
                return redirect('/recent/targetsavings');
            }
        
            toastr()->error('An error has occurred trying to update a target saving record!');
            return back();
}

//Delete Target Saving Record
public function destroy($id){
    $ts_saving = TargetSaving::find($id)->delete();
    if ($ts_saving) {
        toastr()->success('Target saving has been discarded successfully!');
        return redirect('/recent/targetsavings');
    }

    toastr()->error('An error has occurred trying to remove target saving record, please try again later.');
    return back();
}


//Show create form for new target saving
public function create(){
$title = "New Target Saving";
return view('TargetSaving.create',compact('title'));
}


//Store New Target Saving
public function store(Request $request){
    $this->validate(request(), [
        'payment_id'=>'required|integer',
        'entry_date' =>'required|date',
        'amount_saved' =>'required|numeric|between:0.00,999999999.99',
        ]);

        if(User::where('payment_number',request(['payment_id']))->exists())
        {
            //Check also if user has subscribed to target saving
            $user = User::where('payment_number',request(['payment_id']))->first();
            $user_id = $user->id;
            $newsaving = new TargetSaving;
            $newsaving->user_id = $user_id;
            $newsaving->amount = $request['amount_saved'];
            $newsaving->entry_date = $request['entry_date'];
            $newsaving->created_by = auth()->id();
            $newsaving->save();
            if($newsaving->save()) {
                toastr()->success('Target saving record created successfully!');
                return redirect('/recent/targetsavings');
            }
        
            toastr()->error('An error has occurred trying to create a new target saving');
            return back();
        }
    toastr()->error('No user exist with this payment identification number.');
    return back();
}

}
