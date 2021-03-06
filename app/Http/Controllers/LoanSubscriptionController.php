<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Loan;
use App\User;
use App\Product;
use App\Lsubscription;
use  App\Psubscription;
use  App\Saving;
use Carbon\Carbon;
use GuzzleHttp\Client;

class LoanSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $title ='All Loan Request';
        $loanReq = Loan::withCount(['loansubscriptions' => function ($query){
            $query->where('loan_status','Pending');
        }])->paginate(5);
        return view('LoanSub.index',compact('loanReq','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //New Loan Subscription Form
        $title ='New Loan Subscription';
        return view('LoanSub.create',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
        {
        //
        $this->validate(request(), [
            'payment_id'=>'required|integer',
            //'product_cat'=>'required|integer',
            'product_item'=>'required|integer',
            'custom_tenor' =>'nullable|integer|between:1,60',
            'guarantor_id1' => 'required|integer',
            'guarantor_id2' => 'required|integer',
            'units' => 'nullable|integer',
            'amount_applied' =>'nullable|numeric|between:0.00,999999999.99',
            'net_pay' =>'required|numeric|between:0.00,999999999.99',
            ]);

            
                $user_id = User::userID(request(['payment_id']));
                $loan_sub = new Lsubscription();
                $product = Product::find($request['product_item']);
                
                //check fo active users
                $guarantor1 = User::userID(request(['guarantor_id1']));
                $guarantor2 = User::userID(request(['guarantor_id2']));
                if($guarantor1=="" || $guarantor2=="" || $user_id==""){
                    toastr()->success('One or all the users are inactive');
                    return redirect('/loanSub/create');
                }

                $amtApplied = $request['amount_applied'];

                if($request['custom_tenor']){
                    $tenor = $request['custom_tenor'];
                }else{
                    $tenor = $product->tenor;
                }

                if($amtApplied){
                    $amtApplied = $amtApplied;
                }else{
                    $amtApplied = $product->unit_cost * $request['units'];
                }
                
                //$loan_sub->productdivision_id = $request['product_cat'];
                $loan_sub->product_id = $request['product_item'];
                $loan_sub->user_id = $user_id;
                $loan_sub->guarantor_id = $guarantor1;
                $loan_sub->guarantor_id2 = $guarantor2;
                $loan_sub->monthly_deduction = $amtApplied/$tenor;
                $loan_sub->custom_tenor = $tenor;
                $loan_sub->amount_applied = $amtApplied;
                $loan_sub->units = $request['units'];
                $loan_sub->net_pay = $request['net_pay'];
                $loan_sub->created_by = auth()->id();
                $loan_sub->save();
                if($loan_sub->save()) {
                    toastr()->success('Loan request has been saved successfully!');
                    return redirect('/loanSub/create');
                }
                toastr()->error('An error has occurred trying to create a loan request!');
                return back();
            
       
        }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
        {
        //Show detail of all subscriptions for a particular product
        
        $title ='Loan Subscriptions Detail';
        $loanDetails = Lsubscription::where('loan_id',$id)
        ->where(function($query){
            $query->where('loan_status','Pending');
        })->with(['product' => function ($query) {
          $query->orderBy('description', 'desc');
      }])->paginate(10);

        return view('LoanSub.loanSubDetail',compact('loanDetails','title'));
        }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
        {
        // Show form for editing loan subscription
        $title ='Edit Loan Subscription';
        $user = new User;
        $lSub = Lsubscription::find($id);
        $g1 = $user->userInstance($lSub->guarantor_id)->payment_number;
        $g2 = $user->userInstance($lSub->guarantor_id2)->payment_number;
        $paymentNumber = $user->userInstance($lSub->user_id)->payment_number;
        return view('LoanSub.editLoanSub',compact('lSub','title','g1','g2','paymentNumber'));
        }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
        {
        //
        $this->validate(request(), [
            'payment_id'=>'required|integer',
            //'product_cat'=>'required|integer',
            'product_item'=>'required|integer',
            'custom_tenor' =>'nullable|integer|between:1,60',
            'guarantor_id1' => 'required|integer',
            'guarantor_id2' => 'required|integer',
            'units' => 'nullable|integer',
            'amount_applied' =>'nullable|numeric|between:0.00,999999999.99',
            'net_pay' =>'required|numeric|between:0.00,999999999.99',
            ]);

            
                $user_id = User::userID(request(['payment_id']));
                $loan_sub = Lsubscription::find($id);
                $product = Product::find($request['product_item']);
                
                //check fo active users
                $guarantor1 = User::userID(request(['guarantor_id1']));
                $guarantor2 = User::userID(request(['guarantor_id2']));
                if($guarantor1=="" || $guarantor2=="" || $user_id==""){
                    toastr()->success('One or all the users are inactive');
                    return redirect('/loanSub/create');
                }

                $amtApplied = $request['amount_applied'];

                if($request['custom_tenor']){
                    $tenor = $request['custom_tenor'];
                }else{
                    $tenor = $product->tenor;
                }

                if($amtApplied){
                    $amtApplied = $amtApplied;
                }else{
                    $amtApplied = $product->unit_cost * $request['units'];
                }
                
                //$loan_sub->productdivision_id = $request['product_cat'];
                $loan_sub->product_id = $request['product_item'];
                $loan_sub->user_id = $user_id;
                $loan_sub->guarantor_id = $guarantor1;
                $loan_sub->guarantor_id2 = $guarantor2;
                $loan_sub->monthly_deduction = $amtApplied/$tenor;
                $loan_sub->custom_tenor = $tenor;
                $loan_sub->amount_applied = $amtApplied;
                $loan_sub->units = $request['units'];
                $loan_sub->net_pay = $request['net_pay'];
                $loan_sub->created_by = auth()->id();
                $loan_sub->save();
                if($loan_sub->save()) {
                    toastr()->success('Loan request has been updated successfully!');
                    return redirect('/loanSub/create');
                }
                toastr()->error('An error has occurred trying to update a loan request!');
                return back();
        }

        
//USER SUBSCRIPTION DETAILS PAGE
    public function userLoanSubscriptions($id){
        $title = "User Page";
        
        $saving = new Saving;
        //Find user
        $user = User::find($id);

        //Active Product Subscriptions
        $activeLoans = Lsubscription::activeLoans($id);

        //Pending product subscriptions
        $pendingLoans = Lsubscription::pendingLoans($id);
        
        //User active product subscriptions
        //$userProducts = Psubscription::userProducts($id);

        //User pending products subscriptions
        //$userPendingProducts = Psubscription::pendingProducts($id); 

        return view('LoanSub.userLoanSub',compact('title','activeLoans','pendingLoans','user','saving'));
    }


    //show form for reviewing user loan

    public function review($id){
        $title ='Review Loan Subscription';
        $review = Lsubscription::find($id);
        return view('LoanSub.review',compact('review','title'));
    }

    //Review Store
    public function reviewStore(Request $request, $id)
        {
        //
            
            $this->validate(request(), [
            'notes' =>'required|string',
            'review_date' =>'required|date',
            'amount_approved' =>'required|numeric|between:0.00,999999999.99',
            ]);


                 //Retrieve loan subscription instance
                $loan_sub = Lsubscription::find($id);
                
                $approved_amt = $request['amount_approved'];
                $notes = $request['notes'];

                $rev_date = new Carbon($request['review_date']);
                $loan_sub->amount_approved = $approved_amt;
                $loan_sub->loan_status = 'Reviewed';
                $loan_sub->review_date =$rev_date;
                $loan_sub->review_comment = $notes;
                $loan_sub->review_by = auth()->id();
                $loan_sub->save();
                if($loan_sub->save()) {
                    toastr()->success('Loan request has been reviewed successfully!');
                    //redirect user loans listing page
                    return redirect('/pendingLoans');
                }
                toastr()->error('An error has occurred trying to review a loan request!');
                return back();
        }


        //All pending loans
        public function pendingLoans(){
        $title ='All Pending Loans';
        $pendingLoans = Lsubscription::where('loan_status','Pending')
                                    ->oldest()->with(['product','user'])
                                    ->paginate(20);
        return view('LoanSub.pendingLoans',compact('pendingLoans','title'));
        }

        //All active Loans

        public function activeLoans(){
            $title ='All Active Loans';
            $activeLoans = Lsubscription::where('loan_status','Active')
            ->oldest()
            ->with(['loan','user'])
            ->paginate(20);
            return view('LoanSub.activeLoans',compact('activeLoans','title'));
            }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        //
        $loanSubscription = Lsubscription::find($id)->delete();
        if ($loanSubscription) {
            toastr()->success('Loan subscription has been discard successfully!');
    
            return redirect('/pendingLoans');
        }
    
        toastr()->error('An error has occurred trying to remove loan subsscription, please try again later.');
        return back();
    }


    //Stop Loan
    public function loanStop($id){

        $loanSub = Lsubscription::find($id);
        
        $loanAmount = $loanSub->amount_approved;
        //3 get sum deductions for the product
        $totalDeductions = $loanSub->totalLoanDeductions($id);
        //find the diff
        $diffRslt = $loanAmount-$totalDeductions;
        if($diffRslt <= 0){
            //update the subj obj status to inactive
            //retrun to active Sub page
            $loanSub->status = 'Inactive';
            $loanSub->end_date = now()->toDateString();
            $loanSub->review_by = auth()->id();
                $loanSub->save();
                if($loanSub->save()) {
                    toastr()->success('This loan subscription has been successfully stopped');
                    return redirect('/user/page/'.$loanSub->user_id);
                }
        }
        toastr()->error('You can not stop this facility, please check details');
                return back();

        }

            //Loan Details
            public function loanDetails($id){
            $title = 'User Loan Details';
            //find the loan subscription details
            $userLoan = Lsubscription::find($id);
            return view('LoanSub.activeLoanDetails',compact('userLoan','title'));
        }

        //pending app detail
        public function pendingAppDetail($id){
            $title = 'Pending Application Details';
            //find the loan subscription details
            $userLoan = Lsubscription::find($id);
            return view('LoanSub.pendingAppDetail',compact('userLoan','title'));
        }
        //all audited loans
         public function auditedLoans(){
            $title ='All Audited Loans';
            $auditedLoans = Lsubscription::where('loan_status','Reviewed')
                                            ->oldest()->with(['product','user'])
                                            ->paginate(20);
            return view('LoanSub.auditedLoans',compact('auditedLoans','title'));
            }

            //All approved loans
            public function readyLoans(){
                $title ='All Aprroved Loans';
                $approvedLoans = Lsubscription::where('loan_status','Approved')
                                                ->oldest()->with(['product','user'])
                                                ->paginate(20);
                return view('LoanSub.approvedLoans',compact('approvedLoans','title'));
                }

            //Approve Loans
            public function approveLoan($id){
                   
                    $userLoan = Lsubscription::find($id);

                    // $approved_amt = number_format($userLoan->amount_approved,2,'.',',');
                    // $product = $userLoan->product->name;
                    // $phone = $userLoan->user->phone;

                    $userLoan->loan_status ="Approved";
                    $userLoan->approved_date= now()->toDateString();
                    $userLoan->approve_by = auth()->id();
                    $userLoan->save();
                    //send message of approval 
                    // if($userLoan->save()){
                    //     //send message
                    //     $client = new Client;
                    //     $api = '9IGspBnLAjWENmr9nPogQRN9PuVwAHsSPtGi5szTdBfVmC2leqAe8vsZh6dg';
                    //     $to = $phone;
                    //     $from= 'MIDAS';
                    //     $message = 'Your loan has been approved N'.$approved_amt;
                    //    $url = 'https://www.bulksmsnigeria.com/api/v1/sms/create?api_token='.$api.'&from='.$from.'&to='.$to.'&body='.$message.'&dnd=1';

                    //    $response = $client->request('GET', $url,['verify'=>false]);
                    // //    if($response->getStatusCode()==200){
                    // //        //redirect here
                    // //    }
                    // }else{
                    //     toastr()->error('Unable to approve loan! try again');
                    //     return back();
                    // }
                   
                    
                }

                //Pay loan form
                public function payLoan($id){
                    $title ='Pay Loans';
                    $review = Lsubscription::find($id);
                    return view('LoanSub.payLoans',compact('review','title'));
                }

                //public loan store activate loan
                public function payStore(Request $request){
                    $this->validate(request(), [
                        'start_date' =>'required|date',
                        'sub_id' =>'required|integer',
                        ]);
            
                        
                        $loan_id = $request['sub_id'];
                        $dt = $request['start_date'];
                        $date = new Carbon($dt);
                        $start_date = $date->toDateString();
                        //Retrieve loan subscription instance
                        $loan_sub = Lsubscription::find($loan_id);
                        $tenor = $loan_sub->custom_tenor;
                        $amt_approved = number_format($loan_sub->amount_approved,2,'.',',');
                        $product = $loan_sub->product->name;
                        $phone = $loan_sub->user->phone;
                        $end_Date = $loan_sub->SubEndDate($start_date,$tenor);
                        //update loan
                        $loan_sub->loan_start_date = $start_date;
                        $loan_sub->loan_end_date = $end_Date;
                        $loan_sub->loan_status = 'Active';

                        if($loan_sub->save()){
                            //send message
                            $client = new Client;
                                $api = '9IGspBnLAjWENmr9nPogQRN9PuVwAHsSPtGi5szTdBfVmC2leqAe8vsZh6dg';
                                $to = $phone;
                                $from= 'MIDAS TOUCH';
                                $message = 'Your earlier approved '.$product.' loan of N'. $amt_approved.' has been paid and is now active. Thank you.';
                               $url = 'https://www.bulksmsnigeria.com/api/v1/sms/create?api_token='.$api.'&from='.$from.'&to='.$to.'&body='.$message.'&dnd=1';
        
                               $response = $client->request('GET', $url,['verify'=>false]);
                               toastr()->success('Loan activated successfully!');
                               return redirect('/approved/loans');
                            //    if($response->getStatusCode()==200){
                            //        //redirect here
                            //    }
                        }else{
                            //not saved
                            toastr()->error('Unable to activate lloan! try again');
                            return back();
                        }
                        

                }
}
