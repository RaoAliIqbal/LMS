<body class="fixed-content fixed-aside">
<div class="app" id="app">
    <!-- ############ LAYOUT START-->
    <!-- ############ Aside START-->
    <div id="aside" class="app-aside fade box-shadow-x nav-expand dark" aria-hidden="true" ui-class="dark">
        <div class="sidenav modal-dialog dk" ui-class="dark">
            <!-- sidenav top -->
            <div class="navbar lt" ui-class="dark">
                <!-- brand -->
                <a href="#" class="navbar-brand" id="side_bar">

                    <img width="180" src="{{ asset('admin-assets/images/logo.png') }}" alt="Canham College"/>
                    <span class="hidden-folded d-inline"></span>
                </a>
                <!-- / brand -->
            </div>
            <!-- Flex nav content -->
            <div class="flex hide-scroll">
                <div class="scroll">
                    <div class="nav-border b-primary" data-nav>
                        <ul class="nav bg">
                            <li>
                                <a href="{{ route('admin_dashboard') }}">
                                    <span class="nav-icon no-fade"><i class="fa fa-tachometer" aria-hidden="true"></i></span>
                                    <span class="nav-text">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin_analytics') }}">
                                    <span class="nav-icon no-fade"><i class="fa fa-bar-chart" aria-hidden="true"></i></span>
                                    <span class="nav-text">Analytics</span>
                                </a>
                            </li>
                            <li>
                                <a>
                                    <span class="nav-icon no-fade"><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>
                                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                                    <span class="nav-text">Users</span>
                                </a>
                                <ul class="nav-sub nav-mega nav-mega-3">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin_client_view') }}">
                                            <span class="nav-text">Customers</span>
                                        </a>
                                    </li>
                                    <li class="sub_menu">
                                        <a href="{{ route('admin_create_client') }}">
                                            <span class="nav-text">Add Student + Course</span>
                                        </a>
                                    </li>
                                    <li class="sub_menu">
                                        <a href="{{ route('admin_orphan_payments') }}">
                                            <span class="nav-text">Paid — Missing Courses</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('seminars.index') }}">
                                    <span class="nav-icon no-fade"><i class="fa fa-genderless" aria-hidden="true"></i></span>
                                    <span class="nav-text">Seminars</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin_quizzes') }}">
                                    <span class="nav-icon no-fade"><i class="fa fa-genderless" aria-hidden="true"></i></span>
                                    <span class="nav-text">Quizzes</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin_exercises') }}">
                                    <span class="nav-icon no-fade"><i class="fa fa-genderless" aria-hidden="true"></i></span>
                                    <span class="nav-text">Exercises</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin_evaluation') }}">
                                    <span class="nav-icon no-fade"><i class="fa fa-genderless" aria-hidden="true"></i></span>
                                    <span class="nav-text">Evaluation</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin_lessons') }}">
                                    <span class="nav-icon no-fade"><i class="fa fa-genderless" aria-hidden="true"></i></span>
                                    <span class="nav-text">Courses</span>
                                </a>
                            </li>
                            <li>
                                <a>
                                    <span class="nav-icon no-fade"><i class="fa fa-genderless" aria-hidden="true"></i></span>
                                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                                    <span class="nav-text">Approvals</span>
                                </a>
                                <ul class="nav-sub nav-mega nav-mega-3">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin_approvals') }}">
                                            <span class="nav-text">Approvals</span>
                                        </a>
                                    </li>
                                    <li class="sub_menu">
                                        <a href="{{ route('regulations.index') }}">
                                            <span class="nav-text">Regulations</span>
                                        </a>
                                    </li>
                                    <li class="sub_menu">
                                        <a href="{{ route('admin_courseApprovals') }}">
                                            <span class="nav-text">Course Approvals</span>
                                        </a>
                                    </li>
                                    <li class="sub_menu">
                                        <a href="{{ route('admin_SeminarApprovals') }}">
                                            <span class="nav-text">Seminar Approvals</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="{{ route('admin_zoom') }}">
                                    <span class="nav-icon no-fade"><i class="fa fa-video-camera" aria-hidden="true"></i></span>
                                    <span class="nav-text">Zoom Meetings</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('bot-responses.index') }}">
                                    <span class="nav-icon no-fade"><i class="fa fa-genderless" aria-hidden="true"></i></span>
                                    <span class="nav-text">Chat Bot Responses</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.chatbot-qas.index') }}">
                                    <span class="nav-icon no-fade"><i class="fa fa-question-circle" aria-hidden="true"></i></span>
                                    <span class="nav-text">Support Chatbot Q&amp;A</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.coupons.index') }}">
                                    <span class="nav-icon no-fade"><i class="fa fa-genderless" aria-hidden="true"></i></span>
                                    <span class="nav-text">Coupons</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.user_bot_conv.index') }}">
                                    <span class="nav-icon no-fade"><i class="fa fa-genderless" aria-hidden="true"></i></span>
                                    <span class="nav-text">User Bot Convesation</span>
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
            <!-- sidenav bottom -->

        </div>
    </div>
    <!-- ############ Aside END-->
    <!-- ############ Content START-->
    <div id="content" class="app-content box-shadow-0" role="main">
        <!-- Header -->
        <div class="content-header white  box-shadow-0" id="content-header">
            <div class="navbar navbar-expand-lg">
                <!-- btn to toggle sidenav on small screen -->
                <a class="d-lg-none mx-2" data-toggle="modal" data-target="#aside">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512">
                        <path d="M80 304h352v16H80zM80 248h352v16H80zM80 192h352v16H80z"/>
                    </svg>
                </a>
                <!-- Page title -->
                <div class="navbar-text nav-title flex" id="pageTitle">@yield('title')</div>
                <ul class="nav flex-row order-lg-2">
                    <!-- Notification -->

                    <!-- User dropdown menu -->
                    <li class="dropdown d-flex align-items-center">
                        <a href="#" data-toggle="dropdown" class="d-flex align-items-center">
                                    <span class="avatar w-32">
	    	          <img src="{{ route('admin.assets') }}/images/user-logo.png" alt="...">
	    	        </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right w pt-0 mt-2 animate fadeIn">
                            <div class="row no-gutters b-b mb-2">


                            </div>
                            <a class="dropdown-item" href="{{ route('admin_profile') }}">
                                <span>Profile</span>
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}">
                                <span>Sign Out</span>
                            </a>
                        </div>
                    </li>
                    <!-- Navarbar toggle btn -->
                    <li class="d-lg-none d-flex align-items-center">
                        <a href="#" class="mx-2" data-toggle="collapse" data-target="#navbarToggler">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 512 512">
                                <path d="M64 144h384v32H64zM64 240h384v32H64zM64 336h384v32H64z"/>
                            </svg>
                        </a>
                    </li>
                </ul>
                <!-- Navbar collapse -->

            </div>
        </div>
