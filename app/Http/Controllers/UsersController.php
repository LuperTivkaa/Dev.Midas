<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\User;
use App\Nok;
use App\Bank;
use App\Lsubscription;
use App\Psubscription;
use App\Savingreview;
use Illuminate\Support\Facades\Hash;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $title = 'All Users';
        $users = User::orderBy('first_name','asc')->paginate(10);
        return view('Users.listUsers',compact('users','title'));
    }

    //all user bank details
    public function bankList(){
        $title = "User's Bank ";
        $users = User::orderBy('first_name','asc')->paginate(10);
        return view('Users.listUsersBank',compact('users','title'));

    }
    //NOK Details
    public function nokList(){
        $title = "User's NOK";
        $users = User::orderBy('first_name','asc')->paginate(10);
        return view('Users.listUsersNok',compact('users','title'));

    }

    //User View Details
    public function profileDetails($id){
        $title = "User Page";
        $profile = User::find($id);
        $SavingReview = Savingreview::where('user_id',$id)
                                    ->get();
        return view('Users.userView',compact('profile','title','SavingReview'));
    }

    //Show the form for editing a user
    public function editProfile($id){
      $title="Edit Bio Data";
      $user =User::find($id);
        return view('Users.editProfile',compact('user','title'));
    }

    //Update a user
    public function updateProfile(Request $request, $id){
        $title = "User Details";
        //validate the form
        $this->validate(request(), [
            'payment_number'=>'required',
            'title'=>'required',
            'email'=>'nullable|email',
            'first_name'=>'required|string',
            'last_name'=>'nullable|string',
            'employ_type'=>'required',
            'dept'=>'required|string',
            'phone'=>'required',
            'dob'=>'required',
            'home_add'=>'required|max:100',
            'res_add'=>'required|max:100',
            'sex'=>'required',
            'job_cadre'=>'required',
            'staff_no'=>'required|integer',
            'marital_status'=>'required',
        ]);
        
    $profile =User::find($id);
    $profile->payment_number = $request['payment_number'];
    $profile->title = $request['title'];
    $profile->email = $request['email'];
    $profile->first_name = $request['first_name'];
    $profile->last_name = $request['last_name'];
    $profile->other_name = $request['other_name'];
    $profile->employ_type = $request['employ_type'];
    $profile->dept = $request['dept'];
    $profile->phone = $request['phone'];
    $profile->dob = $request['dob'];
    $profile->home_add = $request['home_add'];
    $profile->res_add = $request['res_add'];
    $profile->sex = $request['sex'];
    $profile->job_cadre = $request['job_cadre'];
    $profile->staff_no = $request['staff_no'];
    $profile->marital_status = $request['marital_status'];
    $profile->save();

    if ($profile->save()) {
        toastr()->success('Data has been edited successfully!');

        //return redirect()->route('posts.index');
        return view('Users.userView',compact('profile','title'));
    }

    toastr()->error('An error has occurred trying to update, please try again later.');
    return back();
    }

//Show the form for editing a user's bank details
public function editBank($id){
    $title="Edit User Bank Details";
    $bank =Bank::find($id);
    return view('Users.editBank',compact('bank','title'));
  }


  public function updateBank(Request $request,$id){
    $title = "User Details";
     //validate the form
     $this->validate(request(), [
        'bank_name' =>'required|string',
        'bank_branch' =>'required',
        'sort_code' =>'required|integer',
        'acct_name' =>'required',
        'acct_number' =>'required|integer|digits:10',
    ]);
    $bankUpdate = Bank::find($id);
    $bankUpdate->bank_name = $request['bank_name'];
    $bankUpdate->bank_branch = $request['bank_branch'];
    $bankUpdate->sort_code = $request['sort_code'];
    $bankUpdate->acct_name = $request['acct_name'];
    $bankUpdate->acct_number = $request['acct_number'];
    $bankUpdate->save();
    //find user
    $userid = $bankUpdate->user_id;
    $profile = User::find($userid);
    if ($bankUpdate->save()) {
        toastr()->success('Data has been edited successfully!');

        return view('Users.userView',compact('profile','title'));
    }

    toastr()->error('An error has occurred trying to update, please try again later.');
    return back();
    }

    //Show NOK EDIT FORM
    public function editNok($id){
        $title="Edit Next of Kin Details";
        $nok =Nok::find($id);
        return view('Users.editNok',compact('nok','title'));
    }

    public function updateNok(Request $request,$id){
        $title = "User Details";
         //validate the form
         $this->validate(request(), [
            'title' =>'required|string',
            'sex'=>'required',
            'relationship' =>'required',
            'first_name' =>'required|string',
            'last_name' =>'required|string',
            'other_name' =>'nullable|string',
            'email' =>'required|email',
            'phone' =>'required',
        ]);

        $nokUpdate = Nok::find($id);
        $nokUpdate->title = $request['title'];
        $nokUpdate->gender = $request['sex'];
        $nokUpdate->relationship = $request['relationship'];
        $nokUpdate->first_name = $request['first_name'];
        $nokUpdate->last_name = $request['last_name'];
        $nokUpdate->other_name = $request['other_name'];
        $nokUpdate->email = $request['email'];
        $nokUpdate->phone = $request['phone'];
        $nokUpdate->save();
         //find user
        $userid = $nokUpdate->user_id;
        $profile = User::find($userid);
        if ($nokUpdate->save()) {
            toastr()->success('Data has been edited successfully!');
    
            return view('Users.userView',compact('profile','title'));
        }
    
        toastr()->error('An error has occurred trying to update, please try again later.');
        return back();
        }

        //  Deactivate user
        public function deactivateUser($id){
            $loans = Lsubscription::where('user_id',$id)
                                ->where('loan_status','Active')
                                ->get();
       
        if(count($loans)==0){
            //find user
            $user = User::find($id);
            $user->status = 'Inactive';
            if($user->save())
            {
                toastr()->success('User  deactivated  successfully!');
                return redirect('/userDetails/'.$id);
            }else{
                //rteurn home
                toastr()->error('Error deactivating user.');
                return back();
            }
        }
        else{
            //redirect to the product page with a message
            toastr()->error('You have pending subscriptions');
            return redirect('/user/page/'.$id);
        }
        }

        //activate user
        public function activateUser($id){
            $user = User::find($id);
            $user->status = 'Inactive';
            if($user->save())
            {
                toastr()->success('User  activated  successfully!');
                return redirect('/userDetails/'.$id);
            }else{
                //rteurn home
                toastr()->error('Error activating user.');
                return back();
            }
        }


        //Find user
        public function searchUser(Request $request){
            $title = 'User Search';
            $this->validate(request(), [
                'search_term' =>'required',
            ]);

            $user = new User;
            $param = $request['search_term'];
            $users = $user->searchUser($param);
            return view('Users.userSearch',compact('users','title'));
        }

        //Password change
        public function passwordChange(){
            $title = "Reset Password Change";
            return view('Users.changePass',compact('title'));
        }

        public function passwordStore(Request $request){
            $this->validate(request(), [
                'payment_number'=>'required|integer',
                'password' =>'required|confirmed',
            ]);

            
            $payment_number = $request['payment_number'];
            $user_id = User::userID($payment_number);
            $user = User::find($user_id);

            $user->password = Hash::make($request['password']);
            if ($user->save()) {
                toastr()->success('Password changed successfully!');
                //return redirect()->route('posts.index');
                return redirect('/userDetails/'.$user_id);
            }
            toastr()->error('An error has occurred please try again later.');
            return back();
        }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function edit($id)
    {
        //
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
    }
}
