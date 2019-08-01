<table class="highlight">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>User ID</th>
            <th>Amount Saved NGN</th>
            <th>Date</th>
            <th>Staff ID</th>
            <th>Payment Ref</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($savings as $active)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$active->user->first_name}} {{$active->user->lastname_name}}</td>
            <td>{{$active->user_id}}</td>
            <td>{{number_format($active->current_amount,2,'.',',')}}</td>
            <td>{{now()->toDateString()}}</td>
            <td>{{$active->created_by}}</td>
            <td>{{$active->user->payment_number}}</td>
        </tr>
        @endforeach
    </tbody>
</table>