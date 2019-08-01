@extends('Layouts.admin-app')
@section('main-content')
<div class="container">
    {{--
    @include('inc.messages') --}}
    <div class="row">
        <div class="col s12 subject-header">
            <h6 class="teal-text">IMPORT LOAN DEDUCTIONS</h6>
        </div>
    </div>
    <div class="row">
        <div class="col s12 subject-header">
            <span><a href="/"><i class="small material-icons tooltipped" data-position="bottom"
                        data-tooltip="New Loan Subscription">playlist_add</i></a></span>
            <span><a href="/saving/search"><i class="small material-icons tooltipped" data-position="bottom"
                        data-tooltip="Search Savings">search</i></a></span>
            <span><a href="{{route('prod-deductions.upload')}}"><i class="small material-icons tooltipped"
                        data-position="bottom" data-tooltip="Upload Product Deductions">cloud_upload</i></a></span>
        </div>
    </div>
    <div class="row">
        <form class="col s12" method="POST" action="{{route('deductions.import')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">

                <div class="file-field input-field col s12 m6 l6">
                    <div class="btn">
                        <span>Browse</span>
                        <input type="file" name="deductions_import">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Choose xlsx file">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn">UPLOAD LOAN DEDUCTIONS</button>
        </form>
    </div>
</div>
@endsection