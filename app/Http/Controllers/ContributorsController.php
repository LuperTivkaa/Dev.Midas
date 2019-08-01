<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Saving;

class ContributorsController extends Controller
{
    //
    public function index(){
        //
        $title ='All Active Contributors';
        $activeUsers= User::where('status','Active')->withCount(['usersavings' => function ($query) {
            $query->latest('entry_date');
           }])->paginate(100);
        return view('Contributors.index',compact('activeUsers','title'));
    }

    public function inactiveUsers(){
        $title = 'Inactive Users';
        $inactiveUsers= User::where('status','Inactive')->withCount(['usersavings' => function ($query) {
     $query->orderBy('entry_date', 'desc');
    }])->paginate(100);
    return view('Contributors.inactiveContributors',compact('inactiveUsers','title'));

    }

    public function recentUploads(){
        
        $title = 'Recent Saving Uploads';
        // List recent uploads 
        $recentUploads= Saving::with('user')->latest()->paginate(100);
        return view('Contributors.recentSavings',compact('recentUploads','title'));

    }

    public function userListings($user_id){
        
        $title = 'User Saving Listing';
        $userSavings = Saving::where('user_id',$user_id)
        ->with('user')
        ->latest('entry_date')
        ->paginate(50);

        
        return view('Contributors.userListings',compact('userSavings','title'));

    }

    public function edit($id)
    {
        $title ='Edit User Saving';
        $Saving = Saving::find($id);
        return view('Contributors.editSaving',compact('Saving','title'));
    }

    public function update(Request $request,$id)
    {

        $this->validate(request(), [
            'amount_saved' =>'required|numeric|between:0.00,999999999.99',
            ]);

                $saving = Saving::find($id);
                $saving->amount_saved = $request['amount_saved'];
                $saving->created_by = auth()->id();
                $saving->save();
                if($saving->save()) {
                    toastr()->success('Saving record has been edited successfully!');
                    return redirect('/recent/savings');
                }
            
                toastr()->error('An error has occurred trying to update a saving record!');
                return back();
    }


//Create saving form
public function create(){
    $title= "New Saving";
    return view('Contributors.create',compact('title'));
}

//Store new saving  individually
public function store(Request $request){
    $this->validate(request(), [
        'payment_id'=>'required|integer',
        'entry_date' =>'required|date',
        'amount_saved' =>'required|numeric|between:0.00,999999999.99',
        ]);

        if(User::where('payment_number',request(['payment_id']))->exists())
        {
            $user = User::where('payment_number',request(['payment_id']))->first();
            $user_id = $user->id;
            $newsaving = new Saving;
            $newsaving->user_id = $user_id;
            $newsaving->amount_saved = $request['amount_saved'];
            $newsaving->entry_date = $request['entry_date'];
            $newsaving->created_by = auth()->id();
            $newsaving->save();
            if($newsaving->save()) {
                toastr()->success('Saving record created successfully!');
                return redirect('/recent/savings');
            }
        
            toastr()->error('An error has occurred trying to create a new saving');
            return back();
        }
    toastr()->error('No user exist with this payment identification number.');
    return back();
}




    //delete saving

    public function destroy($id)
    {
        //
        //
        $saving = Saving::find($id)->delete();
        if ($saving) {
            toastr()->success('Saving has been discard successfully!');
            return redirect('/recent/savings');
        }
    
        toastr()->error('An error has occurred trying to remove saving record, please try again later.');
        return back();
    }

}
