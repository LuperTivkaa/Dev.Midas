@extends('Layouts.user')
@section('admin')
<h3>Loan Deductions</h3>
@if(count($deductions))
<table>
    <thead>
        <tr>
            <th>NAME</th>
            <th>PRODUCT</th>
            <th>AMOUNT</th>
            <th>MODE</th>
            <th>DATE</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($deductions as $list)
        <tr>
            <td>
                {{$list->user->first_name}} {{$list->user->last_name}}
            </td>
            <td>
                {{$list->loan->description}}
            </td>
            <td>{{number_format($list->amount_deducted,2,'.',',')}}</td>
            <td>
                {{$list->repayment_mode}}
            </td>
            <td>{{$list->entry_month->toFormattedDateString()}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
{{$deductions->links()}}@else
<p>No records yet</p>
@endif
@endsection