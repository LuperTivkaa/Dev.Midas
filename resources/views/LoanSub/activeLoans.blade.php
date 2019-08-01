@extends('Layouts.admin-app') 
@section('main-content')
<div class="container">
    {{--
    @include('inc.messages') --}}
    <div class="row">
        <div class="col s12 subject-header">
            <p class="teal-text">ACTIVE LOANS</p>

        </div>
    </div>
    <div class="row">
        <div class="col s12 subject-header">
            <span><a href="/loanSub/create"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="New Loan Subscription">playlist_add</i></a></span>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            @if (count($activeLoans)>=1)
            <table class="highlight">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Product</th>
                        <th>Amount NGN</th>
                        <th>Begin</th>
                        <th>Expires</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activeLoans as $active)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td><a href="/user/page/{{$active->user_id}}">{{$active->user->first_name}} {{$active->user->lastname_name}}</a></td>
                        <td>{{$active->loan->description}}</td>
                        <td>{{number_format($active->amount_approved,2,'.',',')}}</td>
                        <td>{{$active->loan_start_date->toFormattedDateString()}}</td>
                        <td>{{$active->loan_start_date->diffForHumans($active->loan_end_date->toFormattedDateString())}}</td>
                        <td><a href="/userLoan/stop/{{$active->id}}">stop</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{$activeLoans->links()}} @else
            <p>No active loans yet</p>
            @endif
        </div>
    </div>
</div>
@endsection