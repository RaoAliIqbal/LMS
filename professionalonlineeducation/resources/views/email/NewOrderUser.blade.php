<h3>Welcome!</h3>
<p>{{ $user->FullName }}</p>
<div>
    <p>
        All of us at Canham College, Inc., are so glad you chose our program to take your @foreach($courses as $course) <b>{{ $course->title }}</b> @if(!$loop->last) , @endif @endforeach we
        know that you have choices, and we appreciate you selecting us.
    </p>
    <p>
        Courses can be accessed immediately. Go to https://www.ProfessionalOnlineEducation.com and in the
        upper right-hand corner of the home page click the login button. Enter your Email Address and
        Password.
    </p>
    <p>
        The system will bring you to your student dashboard. All purchased courses will be on the left hand side
        of the dashboard. Click on the course and it will open up to your material.
    </p>
    <p>
        There are many other things you can do on your dashboard as well; upload a profile picture, store your
        licensing deadline information, open, and start your courses, browse other course offerings, purchase
        another course, and print/reprint your course completion paperwork.
    </p>
    <p>
        If you have any questions regarding the course material during your course, you can reach your
        instructor during office hours 9am-5pm PST at Rosalind Canham, <a href="mailto:mgr@professionalonlineeducation.com">mgr@professionalonlineeducation.com</a>. For any
        technical issues please click on the chat icon on the bottom right-hand corner of the page.
    </p>
    <p>
        We strive to provide quality, challenging and informational courses and hope you enjoy learning with us!
    </p>
</div>
<ul style="list-style: none; padding: 0px;">
    <li>Rosalind Canham, DC</li>
    <li>Instructor</li>
    <li>Canham College, Inc.</li>
    <li>2934 E Avalon Drive</li>
    <li>Phoenix, AZ 85016</li>
    <li>Toll Free 1-888-900-3382</li>
</ul>







