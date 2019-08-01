@extends('Layouts.admin-app') 
@section('main-content')
<div class="container">
    {{--
    @include('inc.messages') --}}
    <div class="row">
        <div class="col s12 subject-header">
            <span class="teal-text">New  Target Saving</span>
            <span><a href="/"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="New Loan Subscription">playlist_add</i></a></span>
            <span><a href="/saving/search"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="Search Savings">search</i></a></span>
            <span><a href="/products"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="Upload Savings">cloud_upload</i></a></span>
            <span><a href="/"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="All User Savings">view_list</i></a></span>
            <span><a href="/products"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="All Savings">visibility</i></a></span>
            <span><a href="{{route('ts.create')}}"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="New TS Upload">cloud_upload</i></a></span>
        </div>
    </div>
    <div class="row">
        <form class="col s12" method="POST" action="/targetsaving/store">
            {{ csrf_field() }}
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <input placeholder="IPPIS or GFMIS" id="payment_id" name="payment_id" type="text" class="validate">
                    <label for="payment_id">Payment ID</label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <input id="amount_saved" name="amount_saved" type="text" class="validate">
                    <label for="amount_saved">Amount Saved</label>
                </div>
            </div>
            <div class="row">

            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <input id="entry_date" name="entry_date" type="text" class="validate datepicker">
                    <label for="entry_date">Entry Date</label>
                </div>
            </div>
            <button type="submit" class="btn">Create</button>
        </form>
    </div>
</div>
@endsection