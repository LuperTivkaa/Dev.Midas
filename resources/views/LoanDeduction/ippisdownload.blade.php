<table class="highlight">
    <thead>
        <tr>

            <th>IPPIS NUMBER</th>
            <th>NAME</th>
            <th>AMOUNT</th>
            <th>START DATE</th>
            <th>END DATE</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loanSub as $active)
        <tr>
            <td>{{$active->user->payment_number}}</td>
            <td>{{$active->user->first_name}} {{$active->user->last_name}}</td>
            <td>{{number_format($active->totalIppisDeductions($active->user_id),2,'.',',')}}</td>
            <td>{{now()->addMonths(1)->toDateString()}}</td>
            <td>{{$active->compareDates($active->productSubEndDate($active->user_id),$active->loanEndDate($active->user_id))->toDateString()}}</td>
        </tr>
        @endforeach
    </tbody>
</table>