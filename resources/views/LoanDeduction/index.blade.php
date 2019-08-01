@extends('Layouts.admin-app')
@section('main-content')
<div class="container">
    {{--
    @include('inc.messages') --}}
    <div class="row">
        <div class="col s12 subject-header">
            <h6 class="teal-text">LOAN DEDUCTION</h6>
        </div>
    </div>
    <div class="row">
        <div class="col s12 subject-header">
            <span><a href="/"><i class="small material-icons tooltipped" data-position="bottom"
                        data-tooltip="New Loan Subscription">playlist_add</i></a></span>
            <span><a href="/loan/filter"><i class="small material-icons tooltipped" data-position="bottom"
                        data-tooltip="Filter Loan">search</i></a></span>

            <span><a href="{{route('prod-deductions.upload')}}"><i class="small material-icons tooltipped"
                        data-position="bottom" data-tooltip="Upload Product Deductions">cloud_upload</i></a></span>
        </div>
    </div>

    <div class="row">
        <div class="col s12 ">
            <span><a class="btn" href="{{route('loans.export')}}">
                    MIDAS XLSX</a>
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            @if (count($loanSub)>=1)
            @include('LoanDeduction.display',$loanSub) @else
            <p>No active records yet</p>
            @endif
        </div>
    </div>
</div>
@endsection