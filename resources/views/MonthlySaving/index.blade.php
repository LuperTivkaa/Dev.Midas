@extends('Layouts.admin-app') 
@section('main-content')
<div class="container">
    {{--
    @include('inc.messages') --}}
    <div class="row">
        <div class="col s12 subject-header">
            <p class="teal-text">Active Monthly Deductions</p>
            <span><a href="/"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="New Loan Subscription">playlist_add</i></a></span>
            <span><a href="/saving/search"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="Search Savings">search</i></a></span>
            <span><a href="/products"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="Upload Savings">cloud_upload</i></a></span>
            <span><a href="/"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="All User Savings">view_list</i></a></span>
            <span><a href="/products"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="All Savings">visibility</i></a></span>
            <span><a href="{{route('usersaving.create')}}"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="New Savings Upload">cloud_upload</i></a></span>
        </div>
    </div>
    <div class="row">
        <div class="col s12 subject-header">
            <span><a class="btn" href="{{route('usersaving.export')}}"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="Download Template">cloud_download</i> Download Template</a></span>
        </div>
    </div>



    <div class="row">
        <div class="col s12">
            @if (count($savings)>=1)
    @include('MonthlySaving.savingsTable',$savings) {{$savings->links()}} @else
            <p>No active saving records yet</p>
            @endif
        </div>
    </div>
</div>
@endsection