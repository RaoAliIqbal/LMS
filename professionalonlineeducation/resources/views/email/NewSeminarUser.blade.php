<h3 style="color: #0AACFA; font-size: 40px;">Welcome!</h3>

Dear {{ $user->FullName }},
<p>
    All of us at Canham College, Inc., are so glad you chose our program to take you’re {{ $seminar->course_title }}, we know that you have choices, and we really appreciate
    you selecting us.
</p>

<div>
    <table class="table">
        <tr>
            <td><b>Description:</b></td>
            <td>{{ $seminar->description }}</td>
        </tr>
        <tr>
            <td><b>Instructor Name:</b></td>
            <td>Rosalind Canham, DC</td>
        </tr>
        <tr>
            <td><b>Contact Information:</b></td>
            <td>1-888-900-3382</td>
        </tr>
        <tr>
            <td><b>Email:</b></td>
            <td><a href="mailto:mgr@professionalonlineeducation.com">mgr@professionalonlineeducation.com</a></td>
        </tr>
        <tr>
            <td><b>Seminar Location</b></td>
            <td>{{ $seminar->location }}, {{ $seminar->address }}, {{ $seminar->city }}, {{ $seminar->state }}, {{ $seminar->zipcode }} </td>
        </tr>
        <tr>
            <td><b>You are scheduled to attend</b></td>
            <td>{{ $seminar->course_title }} on  {{ $seminar->start_date }}{{ $seminar->start_time }} to {{ $seminar->end_date }} {{ $seminar->end_time }}. Please arrive 15 minutes in advance.</td>
        </tr>

    </table>
    <p>I am excited to train with you this weekend.</p>
</div>
<ul style="list-style: none; padding: 0px;">
    <li>Sincerely,</li>
    <li>Dr. Canham</li>
    <li>Canham College, Inc.</li>
    <li>https://www.professionalonlineeducation.com</li>
</ul>
