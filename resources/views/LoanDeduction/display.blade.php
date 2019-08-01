<table class="highlight">
    <thead>
        <tr>
            <th>Name</th>
            <th>Loan</th>
            <th>Monthly RePay</th>
            <th>Due</th>
            <th>History</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loanSub as $active)
        <tr>
            <td>
                <a href="/user/page/{{$active->user_id}}">{{$active->user->first_name}} {{$active->user->last_name}}</a>
            </td>
            <td>{{$active->loan->description}}</td>
            <td>{{number_format($active->monthly_deduction,2,'.',',')}}</td>
            <td>@if($active->loan_end_date=="") @else
                {{$active->loan_end_date->toDateString()}}
                @endif
            </td>
            <td><a href="/#/{{$active->id}}">History</a></td>
        </tr>
        @endforeach
    </tbody>
</table>