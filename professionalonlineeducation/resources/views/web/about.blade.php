@extends('layouts.web')
@section('title','Chiropractic Skills | About Professional Online Education')
@section('keywords','About Professional Online Education')
@section('description','Discover the advantages of pursuing a professional online education in chiropractic. As our main motive is about Professional Online Education providing.')
@section('content')

	<section class="mainbanner about" data-aos="fade-up" style="background-image: url('/webAssets/images/banner-homepage.jpg')">
        
        <div class="caption">
            <div class="container">
                <div class="col-md-7">
                    <h2>About Us</h2>
                      @guest
                    <a href="{{ route('register') }}" class="btn pink">Enroll now for FREE!</a>
                @else
                    {{-- Logged-in user --}}
                    <a href="{{ url('/user/dashboard') }}" class="btn pink">Go to Dashboard</a>
                @endguest
                </div>
            </div>
        </div>
    </section>
    <div class="container aboutwrap">

        <div class="row row_btm_space white_block">
            <div class="col-sm-12">
				<h3>About Us</h3>
                <p><strong>CCI,</strong> started as a chiropractic assistant training company in Arizona in 2002.   The company expanded into online chiropractic education courses in 2008 to meet the requests of out of state offices.  In 2012, CCI became recognized as a PACE program of the Federation of Chiropractic Licensing Boards and became the first provider of the online National Clinical Certified Chiropractic Assistant program.  In 2013 we started providing DC CE Courses co-sponsored by New York Chiropractic College and became a CA Board CE provider. In 2022, we received GSA approval for online government training programs. </p>
                <h3>Our Mission</h3>
                <p>We want to provide <strong>quality online education that is convenient and  your whole team.</strong>  We create effective training, continuing education and professional development courses for you and your entire staff period. Our courses meet the highest standards of the profession, which will reduce training costs and improve employee retention.</p>
            </div>
        </div>

        <h3>Our Instructors</h3>
        <hr>
        <div class="row">
            <div class="col-md-12"></div>
            <div class="col-md-12">
				<img src="{{ url('webAssets\image001.jpg') }}" alt="" style="display: inline-block;" class="sm-client second">				
                <h2>Rosalind Canham, DC</h2>
                <p>Dr. Canham is the founder of CCI and lead instructor.  She has a M.S. in Human Anatomy and Physiology Instruction from New York Chiropractic College and a Doctor of Chiropractic Degree from Cleveland Chiropractic College.  Her chiropractic career started before becoming a Chiropractor and starting her own private practice in 1998.  She was a CA, office manager, and office consultant.  Her 30 years in Chiropractic offers a wealth of knowledge and experience.  In 2013 she received her Certified Clinical Chiropractic Procedure Coding (CCCPC) certificate from Chirocode.  She has written over 44 courses hosted online, covering subject matter from professional development, exercise rehab, manual medicine and therapeutic procedures, ethics, boundaries, and CA training.  As an experienced public speaker, she has taught both DC CE and CA training and CE at multiple  AZ Chiropractic Association and the SD Chiropractors Association state conventions.  Most of her courses are didactic and packed with information and checklists that you can use day one to improve your office procedures.</p>

                <p>Nearly 40 years ago, Dr. Canham&rsquo;s journey into chiropractic began by chance. Hired as a temporary receptionist to cover a maternity leave, everything changed within two weeks when that employee&mdash;and two other staff members&mdash;left the practice. Overnight, she became the office&rsquo;s only Chiropractic Assistant.</p>

                <p>At the time, there was no formal training or state-mandated education for Chiropractic Assistants. Like many CAs of that era, she learned through experience, discovering both a passion for chiropractic and the challenges created by inconsistent training. Determined to change that, she went on to earn her Doctor of Chiropractic degree.</p>

                <p>While in chiropractic school, Dr. Canham consulted with chiropractic practices on patient flow, team development, and office efficiency. After graduation, she built four successful chiropractic practices from the ground up, before selling them, giving her firsthand insight into what it takes to develop exceptional chiropractic teams.</p>

                <p>In 2002, recognizing the need for standardized CA education, Dr. Canham created a board-approved Chiropractic Assistant Certification program in Arizona. She traveled throughout the state teaching weekend classes before launching an online learning platform in 2006 to serve students across the country.</p>

                <p>In 2009, the program earned a significant milestone by receiving <strong>Federation of Chiropractic Licensing Boards (FCLB) PACE approval</strong> for its National Chiropractic Assistant Program, reinforcing its commitment to high educational standards and national recognition.</p>

                <p>In 2016, Dr. Canham earned her Master&rsquo;s of Science Degree in Human Anatomy and Physiology Instruction from New York Chiropractic College, deepening her knowledge of adult learning principles and enhancing her ability to create impactful online educational programs.</p>

                <p>Today, Professional Online Education has trained more than <strong>12,000 Chiropractic Assistants</strong>, offers state-specific and national certification programs in <strong>31 states</strong>, and provides continuing education and leadership training for chiropractic professionals. What began as one Chiropractic Assistant learning on the job has become a mission to ensure every CA has access to the education, confidence, and leadership skills needed to help build thriving chiropractic practices.</p>

                  <h5><b>Other interesting facts:</b></h5>
                <p>Mother to 3 mixed dogs (chug, chiweenie, chihuahua).<br>
                    Cancer survivor <br>
                    CEO of Living Evolution Project (non-profit) providing navigation, and professional development assistance.
                    <br>
                </p>
            </div>
        </div>

        <div class="row">
            
            <div class="col-md-12 new_sec_nan" style="margin-top:70px">
			
			<img src="{{ url('webAssets\image003.jpg') }}" alt="" style="display: inline-block;" class="sm-client">
                <h2>Nana Baird, LMT</h2>
               <p>Mrs. Baird has been in private practice for 24 years and is certified in Thai Massage and Sports Massage.   She worked in Los Angeles, Beverly Hills, and Phoenix, AZ.  Has been a massage consultant for CCI for 10 years.  Recently, she came onboard as an instructor providing online video-based massage CE courses.  Nana beliefs that quality learning needs to utilize multiple learning methods.  "It is essential that education provide not only written material but provide video that the student can visually learn from and review as many times as needed to gain new techniques." Nana  </p>
                <h5><b>Other interesting facts:</b></h5>
               <p>
                   Owned AZ Back and Foot Massage providing much needed massage to hospital staff in Arizona. <br>
                   Co-owned and operated MANAKA a non-profit that provides educational opportunities for disadvantaged youth.
                   <br>
                   Was a client navigator for Living Evolution Project, a non-profit that provides professional services and courses for assisting the newly homeless find work.  As well as navigating them into transitional housing.
                   <br>
               </p>
            </div>
        </div>

    </div>
	<br><br>
@endsection