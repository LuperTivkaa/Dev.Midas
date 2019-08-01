@extends('Layouts.admin-app') 
@section('main-content')
<div class="container">
    {{--
    @include('inc.messages') --}}
    <div class="row">
        <div class="col s12 subject-header">
            <p class="teal-text">RECENT TARGET SAVING</p>
        </div>
    </div>
    <div class="row">
        <div class="col s12 subject-header">
            <span><a href="/"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="New Loan Subscription">playlist_add</i></a></span>
            <span><a href="/saving/search"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="Search Savings">search</i></a></span>
            <span><a href="/products"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="Upload Savings">cloud_upload</i></a></span>
            <span><a href="/"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="All User Savings">view_list</i></a></span>
            <span><a href="/products"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="All Savings">visibility</i></a></span>
            <span><a href="{{route('ts.create')}}"><i class="small material-icons tooltipped" data-position="bottom" data-tooltip="New TS Upload">cloud_upload</i></a></span>
        </div>
    </div>
    <hr/>

    <div class="row">
        <div class="col s12">
            @if (count($recentTs)>=1)
            <table class="highlight">
                <thead>
                    <tr>
                        <th>Contributor Name</th>
                        <th>Amount</th>
                        <th>Entry Date</th>
                        <th>Created At</th>
                        <th>Edit</th>
                        <th>Remove</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentTs as $item)
                    <tr>
                        <td><a href="/user/page/{{$item->user->id}}">{{$item->user->first_name}} {{$item->user->last_name}}</a></td>
                        <td>{{number_format($item->amount,2,'.',',')}}</td>
                        <td>{{$item->entry_date->toDateString()}}</td>
                        <td>{{$item->created_at->diffForHumans()}}</td>
                        <td>
                            <a href="/targetsaving/edit/{{$item->id}}"><i class="small material-icons">edit</i> </a>
                        </td>
                        <td>
                            <a href="/targetsaving/remove/{{$item->id}}" id="delete"> <i class="small material-icons red-text">delete</i></a>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{$recentTs->links()}} @else
            <p>No Records Available</p>
            @endif
        </div>
    </div>
</div>
@endsection