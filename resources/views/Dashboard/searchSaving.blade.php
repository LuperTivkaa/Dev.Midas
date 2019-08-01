@extends('Layouts.user')
@section('admin')
<h3>Filtered Savings</h3>
@if (count($records)>=1)
<span><a href="/Dashboard/print/{{$fromDate}}/{{$toDate}}">Print</a></span> | <span><a
        href="/Dashboard/downloadpdf/{{$fromDate}}/{{$toDate}}">Download PDF</a></span>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$saving->openingDate($fromDate)}}</td>
            <td>Opening Balance</td>
            <td></td>
            <td></td>
            <td>{{number_format($saving->openingBalance($fromDate,auth()->id()),2,'.',',')}}</td>
        </tr>
        @foreach ($records as $list)
        <tr>
            <td>{{$list->entry_date}}</td>
            <td>{{$list->notes}}</td>
            <td>{{number_format($list->amount_withdrawn,2,'.',',')}}</td>
            <td>{{number_format($list->amount_saved,2,'.',',')}}</td>

            <td>{{number_format($saving->balanceAsAt($fromDate,$list->amount_saved,$list->amount_withdrawn,$list->id))}}
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
@else
<p>No records found yet</p>
@endif
@endsection