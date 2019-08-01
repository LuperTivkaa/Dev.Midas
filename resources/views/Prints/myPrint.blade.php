@extends('Layouts.print',['Saving'=>$Saving,'to'=>$to,'from'=>$from])
@section('print-area')
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Transaction Description</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$Saving->openingDate($from)}}</td>
            <td>Openning Balance</td>
            <td></td>
            <td></td>
            <td>{{$Saving->openingBalance($from,$to,auth()->id())}}</td>
        </tr>
        @foreach($statementCollection as $statement)
        <tr>
            <td>{{$statement->entry_date}}</td>
            <td>
                {{$statement->notes}}
            </td>
            <td>{{$statement->amount_withdrawn}}</td>
            <td>{{$statement->amount_saved}}
            </td>
            <td>{{$Saving->balanceAsAt($statement->entry_date,$statement->amount_saved,$statement->amount_withdrawn)}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection