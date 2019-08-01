<table class="highlight">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>User Id</th>
            <th>Amount Saved</th>
            <th>Date</th>
            <th>Staff Id</th>
            <th>Payment Ref</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($savings as $active)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$active->user->first_name}} {{$active->user->lastname_name}}</td>
            <td>{{$active->user_id}}</td>
            <td>{{$active->current_amount}}</td>
            <td>{{now()->toDateString()}}</td>
            <td>{{$active->created_by}}</td>
            <td>{{$active->user->payment_number}}</td>
        </tr>
        @endforeach
    </tbody>
</table>