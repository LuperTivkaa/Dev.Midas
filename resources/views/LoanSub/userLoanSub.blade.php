@extends('Layouts.admin-app')
@section('main-content')
<div class="container">
    {{--
    @include('inc.messages') --}}
    <div class="row">
        <div class="col s12 subject-header">
            <p class="teal-text">USER PRODUCT(S)</p>
        </div>
    </div>


    <div class="row user-profiles">
        <div class="col s12 m3 l3 profile">
            {{-- <img src="{{asset('images/andy.jpg')}}" alt="" class="circle"> --}}
            <p class="profile__heading text-grey darken-3">Personal Details</p>
            <img src="{{url('storage/photos/'.$user->photo)}}" alt="" class="profile__photo">
            <span class="profile__user-name">{{$user->title}}</span>
            <span class="profile__user-name">{{$user->first_name}} {{$user->last_name}}</span>
            <div class="profile__user-box">
                <span class="black-text sub-profile">Savings</span>
                <span class="profile__user-date grey-text lighten-2"><a href="/saving/listings/{{$user->id}}">N
                        {{number_format($saving->mySavings($user->id),2,'.',',')}}</a></span>
                {{-- <span class="black-text sub-profile">Joined Since</span>
                <span class="profile__join-date grey-text lighten-2">{{$user->created_at->diffForHumans()}}</span>
                <span class="black-text sub-profile">Sex</span>
                <span class="profile__user-status grey-text lighten-2">{{$user->sex}}</span> --}}
            </div>
            <p><a href="" class="btn pink darken-4"> 25% withdrawal</a></p>
            <p><a href="" class="btn red lighten-2"> Full withdrawal</a></p>

            {{-- <span><a href="/editProfile/{{$user->id}}" class="pink-text darken-2">Edit</a></span> --}}
        </div>

        <div class="col s12 m9 l9 profile-detail">

            <div>
                <table class="highlight">
                    <thead>
                        <tr>
                            <th>Staff #</th>
                            <th>Payment #</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$user->staff_no}}</td>
                            <td>{{$user->payment_number}}</td>
                            <td>{{$user->phone}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    @if ($activeLoans->count() >=1 )
    <div class="row user-profiles">
        <div class="col s12 m12 l12  profile-detail">
            <p class="profile__heading text-grey darken-3">
                {{$user->activeLoans($user->id)}} Active Loan(s) | <span><a href="" class="pink-text darken-2">ALL
                        LOANS</a></span></p>
            <table class="highlight">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Prin.</th>
                        <th>Paid</th>
                        <th>Bal</th>
                        <th>Due</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeLoans as $active)
                    <tr>
                        <td>
                            <a href="/activeLoan/detail/{{$active->id}}">{{$active->product->name}}</a>
                        </td>
                        <td>
                            {{number_format($active->amount_approved,2,'.',',')}}
                        </td>
                        <td><a
                                href="/loanDeduction/histroy/{{$active->id}}">{{number_format($active->totalLoanDeductions($active->id),2,'.',',')}}</a>
                        </td>
                        <td>{{number_format($active->amount_approved-$active->totalLoanDeductions($active->id),2,'.',',')}}
                        </td>
                        <td>{{$active->loan_end_date->toFormattedDateString()}}
                        </td>
                        <td>
                            <a href="/userLoan/stop/{{$active->id}}" class="btn red">Stop</a>
                            <a href="/loan/repay/{{$active->id}}" class="btn">Repay</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    @else @endif


    @if ($pendingLoans->count() >=1)
    <div class="row user-profiles">
        <div class="col s12 m12 l12  profile-detail">
            <p class="profile__heading text-grey darken-3">
                {{$user->pendingLoans($user->id)}} Pending Application(s) </p>
            <table class="highlight">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Amount (NGN)</th>
                        <th>Date Applied</th>
                        <th>Required 30%</th>
                        <th>Available 30%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingLoans as $pending)
                    <tr>
                        <td><a href="/pendingApp/detail/{{$pending->id}}">{{$pending->product->name}}</a></td>
                        <td>{{number_format($pending->amount_applied,2,'.',',')}}</td>
                        <td>{{$pending->created_at->toDateString()}}</td>
                        <td>{{number_format($pending->user->requiredPercent($pending->amount_applied), 2,'.',',')}}</td>
                        <td>{{number_format($pending->user->availablePercent($pending->user_id), 2,'.',',')}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    @else
    <p>User has no pending loan applications</p>
    @endif
</div>
@endsection