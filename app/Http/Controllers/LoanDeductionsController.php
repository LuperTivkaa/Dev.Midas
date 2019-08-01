<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lsubscription;
use App\Ldeduction;
use Carbon\Carbon;
use App\Exports\LoandeductionsExport;
use App\Exports\IppisLoandeductionsExport;
use App\Exports\defaultIppisdeductionsExport;
use App\Exports\defaultIppisPdfExport;
use App\Imports\LoanDeductionImport;
use App\Exports\midasFilterExport;
use Maatwebsite\Excel\Facades\Excel;
//use Redirect;
//use PDF;

class LoanDeductionsController extends Controller
{
        
        //Filter loan deductions form
        public function filterDeductions(){
            $title = 'Filter Loan Deductions';
            return view ('LoanDeduction.queryLoanDeduction', compact('title'));
        }

        //Filter loan deductions result
        public function filterLoanResult(Request $request)
        {
        //
            $title = 'User Loan Deductions';
            $this->validate(request(), [
            'payment_type' =>'required',
            'start_date' =>'required|date',
            'end_date' =>'required|date',
            ]);

            $payment_type = $request['payment_type'];
            $start_date = $request['start_date'];
            $end_date = $request['end_date'];
            $loanSub = Lsubscription::filterResult($payment_type, $start_date, $end_date);

            return view ('LoanDeduction.queryResult', compact('title','loanSub','payment_type','start_date','end_date'));
        }

    //list all loan subscriptions
    public function index(){
        $title = 'User Loan Deductions';
        $loanSub = Lsubscription::loanSubscriptions();
        return view ('LoanDeduction.index', compact('title','loanSub'));
    }
//Export loan deductions in excel format
public function export(){
    $jstNow = Carbon::now()->toDateString();
    $fileName = 'MIDAS_LOANDEDUCTIONS_'.$jstNow.'.xlsx';
    return Excel::download(new LoandeductionsExport(), $fileName);
}

    //list ippis format loan subscriptions
        public function ippis(){
        $title = 'User Loan Deductions';
        $loanSub = Lsubscription::distinctUserLoanSub();
        return view ('LoanDeduction.ippis', compact('title','loanSub'));
    }

    //DEFAULT IPPIS DOWNLOAD
      public function defaultIppisExport(){
        $jstNow = Carbon::now()->toDateString();
        $fileName = 'MIDAS_IPPIS_LOAN_DEDUCTIONS_'.$jstNow.'.xlsx';
        return Excel::download(new defaultIppisdeductionsExport(), $fileName);
    }


     //IPPIS FILTER DOWNLOAD TEMPLATE
     public function excelFilterExport($payment_type,$start_date,$end_date){
        $jstNow = Carbon::now()->toDateString();
        $fileName = 'MIDAS_IPPISFILTER_LOAN_DEDUCTIONS_'.$jstNow.'.xlsx';
        return Excel::download(new IppisLoandeductionsExport($payment_type,$start_date,$end_date), $fileName);
    }

    //MIDAS FILTER DOWNLOAD TEMPLATE
    public function midasExcelFilterExport($payment_type,$start_date,$end_date){
        $jstNow = Carbon::now()->toDateString();
        $fileName = 'MIDAS_LOAN_DEDUCTIONS_'.$jstNow.'.xlsx';
        return Excel::download(new midasFilterExport ($payment_type,$start_date,$end_date), $fileName);
    }

    //TODO: DEFAULT IPPIS PDF DOWNLOAD

    //upload loan deductions form
    public function importForm(){
        $title = 'Upload Loan Deductions';
      return view('LoanDeduction.midasImport',compact('title'));
    }

    //Import loan deductions
    public function importLoanDeductions(){
        
        try{
      Excel::import(new LoanDeductionImport(),request()->file('deductions_import'));
        }catch(\Exception $ex){
           toastr()->error('An error has occurred trying to import loan deductions');
                return back();
        }catch(\Error $ex){
            toastr()->error('Something bad has happened');
            return back();
        }
      
        toastr()->success('Document uploaded successfully!');
        //redirect to listing page order by latest
        return redirect('/loanDeduction/listings');
    
    }

    //Recent loan deduction upload
    public function loanDeductions(){
        $title = 'Recent Loan Deductions';
        //List recent uploads 
        $recent= Ldeduction::with('user','loan')->latest()->orderBy('user_id','desc')->paginate(100);
        return view('LoanDeduction.recentLoanDeduction',compact('recent','title'));
    }

    public function edit($id){
        $title =  'Edit Loan Deduction';
        $deduction = Ldeduction::find($id);
        return view ('LoanDeduction.editLoanDeduction',compact('deduction','title'));
    }

    //update product deduction
    public function update(Request $request, $id)
    {
    
    //Save product subscription
    $this->validate(request(), [
        'amount' =>'required|numeric|between:0.00,999999999.99',
        'bank_name' =>'nullable|string',
        'depositor_name'=>'nullable|string',
        'teller_number' =>'nullable|integer',
        'entry_date' =>'required|date',
        'mode' =>'required|string',
    ]);

            $loan_Deduct = Ldeduction::find($id);
            
            $loan_Deduct->amount_deducted = $request['amount'];
            $loan_Deduct->bank_name = $request['bank_name'];
            $loan_Deduct->depositor_name = $request['depositor_name'];
            $loan_Deduct->teller_no = $request['teller_number'];
            $loan_Deduct->entry_month = $request['entry_date'];
            $loan_Deduct->repayment_mode = $request['mode'];
            $loan_Deduct->uploaded_by = auth()->id();
            $loan_Deduct->save();
            if($loan_Deduct->save()) {
                toastr()->success('Loan deduction updated successfully!');
                return redirect('/loanDeduction/listings');
            }
        
            toastr()->error('An error has occurred trying to update user deduction.');
            return back();
  
    }

    //Remove deduction
    public function destroy($id)
    {
        //Delete loan Deduction
        $dlt = Ldeduction::find($id)->delete();
        if($dlt) {
            toastr()->success('Item deleted successfully!');
            return redirect('/loanDeduction/listings');
        }
    
        toastr()->error('An error has occurred trying to delete record.');
        return back();
    }

    /**
     * Loan repay
     * pass in loan subscription id
     */
      public function repay($id){
        $title = 'Loan Repayment';
        //find the loan from the loan subscription table
        $subscription = Lsubscription::find($id);
        return view('LoanDeduction.loanRepay',compact('title','subscription'));
    }

    //Store loan deduction repayment
    public function repayStore(Request $request)
    {
    
    //Save loan repayment
    $this->validate(request(), [
        'amount' =>'required|numeric|between:0.00,999999999.99',
        'user_id' =>'required|integer',
        'sub_id' =>'required|integer',
        'product' =>'required|integer',
        'bank_name' =>'required|string',
        'depositor_name'=>'required|string',
        'teller_number' =>'required|string',
        'entry_date' =>'required|date',
        'notes' =>'required|string',
        'bank_add' =>'required|string',
        ]);

        
            $loanRepay = new Ldeduction;
            //TODO
            /**
             * Write a function to allow loan repaymentonly if
             * 1. The balance on the loan is greater than zero
             * 2. If the balance on the Loan is equal to zero
             * 3. If the balance is zero then refuse loan payment
             */
            
            $loanRepay->amount_deducted = $request['amount'];
            $loanRepay->bank_name = $request['bank_name'];
            $loanRepay->user_id = $request['user_id'];
            $loanRepay->loan_id = $request['product'];
            $loanRepay->lsubscription_id = $request['sub_id'];
            $loanRepay->entry_month = $request['entry_date'];
            $loanRepay->teller_no = $request['teller_number'];
            $loanRepay->repayment_mode = $request['mode'];
            $loanRepay->depositor_name = $request['depositor_name'];
            $loanRepay->notes = $request['notes'];
            $loanRepay->bank_add = $request['bank_add'];
            $loanRepay->uploaded_by = auth()->id();
            $loanRepay->save();
            if($loanRepay->save()) {
                toastr()->success('Loan Repayment Successful');
                return redirect('/loanDeduction/history/'.$request['sub_id']);
            }
        
            toastr()->error('An error has occurred trying to record payment.');
            return back();
        
    }

    /**
     * Loan Deduction History
     * pass in loan subscription ID
     */
    public function loanDeductionHistory($id){
        $title = 'Loan Deduction History';
        $loanHistory = Ldeduction::loanHistory($id);
        return view ('LoanDeduction.loanHistory',compact('title','loanHistory'));

    }
     
}
