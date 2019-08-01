@extends('Layouts.user')
@section('admin')
<h3>All Loans</h3>
@if(count($loans)>=1)
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Product</th>
            <th>Amount Applied</th>
            <th>Status</th>
            <th>Detail</th>
            <th>History</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loans as $active)
        <tr>
            <td>
                {{$active->user->first_name}} {{$active->user->last_name}}
            </td>
            <td>{{$active->loan->description}}</td>
            <td>{{$active->amount_applied}}</td>
            <td>
                {{$active->loan_status}}
            </td>
            <td><a href="/Dashboard/userloans/view/{{$active->id}}">Detail</a></td>
            <td><a href="/Dashboard/user/loans/{{$active->id}}">History</a></td>
        </tr>
        @endforeach
    </tbody>
</table>@else
<p>No records yet</p>
@endif
@endsection