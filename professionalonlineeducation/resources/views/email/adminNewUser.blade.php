<table>

    <tr><td style="padding: 10px"><b>First Name: </b>{{ $user->first_name }}</td></tr>
    @if($user->middle_name != '')
        <tr><td style="padding: 10px"><b>Middle Name: </b>{{ $user->middle_name }}</td></tr>
    @endif
     <tr><td style="padding: 10px"><b>Last Name: </b>{{ $user->last_name }}</td></tr>
     <tr><td style="padding: 10px"><b>Office Name: </b>{{ @$user->clinic->clinic_name }}</td></tr>
     <tr><td style="padding: 10px"><b>Office Telephone Number: </b>{{ @$user->clinic->office_phone }}</td></tr>
     <tr><td style="padding: 10px"><b>Office  Address: </b>{{ @$user->clinic->address }}</td></tr>
     <tr><td style="padding: 10px"><b>Office City: </b>{{ @$user->clinic->city }}</td></tr>
     <tr><td style="padding: 10px"><b>Office State: </b>{{ @$user->clinic->state }}</td></tr>
     <tr><td style="padding: 10px"><b>Office Zipcode: </b>{{ @$user->clinic->zipcode }}</td></tr>

     <tr><td style="padding: 10px"><b>Student Address: </b>{{ $user->address }}{{ !empty($user->suite) ? ', '.$user->suite : '' }}</td></tr>
     <tr><td style="padding: 10px"><b>City: </b>{{ $user->city }}</td></tr>
     <tr><td style="padding: 10px"><b>State: </b>{{ $user->state }}</td></tr>
     <tr><td style="padding: 10px"><b>Zipcode: </b>{{ $user->zipcode }}</td></tr>
     <tr><td style="padding: 10px"><b>Email: </b>{{ $user->email }}</td></tr>
     <tr><td style="padding: 10px"><b>Profession: </b>{{ $user->category->title }}</td></tr>

     @php 
        $lincese = $user->linceses()->first();
     @endphp
     @if(!empty($lincese))
        <tr><td style="padding: 10px"><b>License No: </b>{{ $lincese->licence_no }}</td></tr>
        <tr><td style="padding: 10px"><b>Renewal Date: </b>{{ $lincese->renewal_data }}</td></tr>
     @endif

    <tr><td style="padding: 10px"><b>Student Phone Number: </b>{{ $user->phone }}</td></tr>


    
    <tr><td style="padding: 10px"><b>Password: </b>{{ $user->real_password }}</td></tr>
</table>
