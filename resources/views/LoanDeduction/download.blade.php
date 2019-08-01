<table class="highlight">
    <thead>
        <tr>

            <th>Name</th>
            <th>UserId</th>
            <th>Loan Type</th>
            <th>LoanID</th>
            <th>Amount</th>
            <th>SubscriptionID</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loanSub as $active)
        <tr>
            <td>{{$active->user->first_name}} {{$active->user->last_name}}</td>
            <td>{{$active->user_id}}</td>
            <td>{{$active->loan->description}}</td>
            <td>{{$active->loan->id}}</td>
            <td>{{$active->monthly_deduction}}</td>
            <td>{{$active->id}}</td>
            <td>{{now()->toDateString()}}</td>
        </tr>
        @endforeach
    </tbody>
</table>