<table>
    <tr><td style="padding: 10px"><b>Student Name: </b>{{ $request['user']->FullName }}</td></tr>
    <tr><td style="padding: 10px"><b>Student Complete Address: </b>{{ $request['user']->address }}@if(!empty($request['user']->suite)), {{ $request['user']->suite }}@endif</td></tr>
    <tr><td style="padding: 10px"><b>Phone: </b>{{ $request['user']->phone }}</td></tr>
    <tr><td style="padding: 10px"><b>City: </b>{{ $request['user']->city }}</td></tr>
    <tr><td style="padding: 10px"><b>State: </b>{{ $request['user']->state }}</td></tr>
    <tr><td style="padding: 10px"><b>Zipcode: </b>{{ $request['user']->zipcode }}</td></tr>
    <tr><td style="padding: 10px"><b>Email: </b>{{ $request['user']->email }}</td></tr>
    <tr><td style="padding: 10px"><b>Password: </b>{{ $request['user']->real_password }}</td></tr>
    @if($request['user']->category != null)
    <tr><td style="padding: 10px"><b>Profession: </b>{{ $request['user']->category->title }}</td></tr>
    @endif
    @if($request['user']->clinic)
        <tr><td style="padding: 10px"><b>Office Name: </b>{{ $request['user']->clinic->clinic_name }}</td></tr>
        <tr><td style="padding: 10px"><b>Office Address: </b>{{ $request['user']->clinic->address }}</td></tr>
        <tr><td style="padding: 10px"><b>City: </b>{{ $request['user']->clinic->city }}</td></tr>
        <tr><td style="padding: 10px"><b>State: </b>{{ $request['user']->clinic->state }}</td></tr>
        <tr><td style="padding: 10px"><b>Zipcode: </b>{{ $request['user']->clinic->zipcode }}</td></tr>
        <tr><td style="padding: 10px"><b>Office Phone Number: </b>{{ $request['user']->clinic->office_phone }}</td></tr>

    @endif
    <tr>
        <td style="padding: 10px"><b>Seminar Registered for:</b>
            {{ $request['seminar']->course_title }}
        </td>
    </tr>
    @if(count($request['user']->linceses))
        <div>
            <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">License Information</h4>
            <ul style="list-style: none; padding: 0">
                @foreach($request['user']->linceses as $lincense)
                    <li>License: {{ $lincense->licence_no }} License State: {{ $request['user']->state }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <tr><td style="padding: 10px"><b>Amount Charged: </b>{{ $request['total'] }}</td></tr>
    <tr><td style="padding: 10px"><b>Discount Applied: </b>{{ $request['discount'] }}</td></tr>
    <tr><td style="padding: 10px"><b>Transaction number: </b>{{ $request['transaction_id'] }}</td></tr>



</table>


