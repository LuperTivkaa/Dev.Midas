<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Loan;
use App\User;
use App\Lsubscription;
use App\Savingreview;
use App\Saving;
use Carbon\Carbon;
use App\Exports\MonthlycontributionExport;
use App\Exports\MonthlycontributionExportView;
use App\Imports\SavingsImport;
use Maatwebsite\Excel\Facades\Excel;

class MonthlySavingController extends Controller
{
    //
    public function index(){
        $title = 'Active Deductions';
        //list active contributors with their dedcutions

        $savings = Savingreview::where('status','Active')->oldest()->with(['user'])
        ->paginate(20);
        return view('MonthlySaving.index',compact('savings','title'));
    }

    public function export(){
        return Excel::download(new MonthlycontributionExport(), 'savingdeductions.xlsx');
    }

    public function export_view(){
        $jstNow = Carbon::now()->toDateString();
        $fileName = 'MIDAS_USERSAVINGS_'.$jstNow.'.xlsx';
        return Excel::download(new MonthlycontributionExportView(), $fileName);
    }

    public function savingUpload(){
        $title = 'Upload User Savings';
      return view('MonthlySaving.savingsUpload',compact('title'));
    }

    public function savingImport(){

        try{
      Excel::import(new SavingsImport(),request()->file('saving_import'));
        }catch(\Exception $ex){
            toastr()->error('An error has occurred trying to import savings');
                return back();
        }catch(\Error $ex){
            toastr()->error('Something bad has happened');
            return back();
        }
      
        toastr()->success('Savings uploaded successfully!');
        //redirect to listing page order by latest
        return redirect('/recent/savings');
    
    }

}
