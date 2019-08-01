<table class="highlight">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>User ID</th>
            <th>Target Saving NGN</th>
            <th>Date</th>
            <th>Staff ID</th>
            <th>Payment Ref</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ts as $item)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$item->user->first_name}} {{$item->user->lastname_name}}</td>
            <td>{{$item->user_id}}</td>
            <td>{{number_format($item->monthly_saving,2,'.',',')}}</td>
            <td>{{now()->toDateString()}}</td>
            <td>{{$item->review_by}}</td>
            <td>{{$item->user->payment_number}}</td>
        </tr>
        @endforeach
    </tbody>
</table>