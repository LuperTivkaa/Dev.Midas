@extends('Layouts.admin-app') 
@section('main-content')
<div class="container">
    {{--
    @include('inc.messages') --}}
    <div class="row">
        <div class="col s12 subject-header">
            <p class="teal-text">Recent Monthly Deductions</p>
            <span><a href="/"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="New Loan Subscription">playlist_add</i></a></span>
            <span><a href="/saving/search"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="Search Savings">search</i></a></span>
            <span><a href="/products"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="Upload Savings">cloud_upload</i></a></span>
            <span><a href="/"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="All User Savings">view_list</i></a></span>
            <span><a href="/products"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="All Savings">visibility</i></a></span>
            <span><a href="{{route('usersaving.create')}}"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="New Savings Upload">cloud_upload</i></a></span>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            @if (count($userSavings)>=1)
            <table class="highlight">
                <thead>
                    <tr>
                        <th>Contributor Name</th>
                        <th>Amount</th>
                        <th>Entry Date</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userSavings as $listing)
                    <tr>
                        <td><a href="/user/page/{{$listing->user->id}}">{{$listing->user->first_name}} {{$listing->user->last_name}}</a></td>
                        <td>{{number_format($listing->amount_saved,2,'.',',')}}</td>
                        <td>{{$listing->entry_date->toDateString()}}</td>
                        <td>{{$listing->created_at->diffForHumans()}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{$userSavings->links()}} @else
            <p>No Records Available</p>
            @endif
        </div>
    </div>
</div>
@endsection