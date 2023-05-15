<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header">
            <!-- This is for the sidebar toggle which is visible on mobile only -->
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                    class="ti-menu ti-close"></i></a>
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <a class="navbar-brand" href="#">
                <!-- Logo icon -->
                <b class="logo-icon">
                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                    <!-- Dark Logo icon -->
                    <img src="{{ asset('dashboard/assets/images/logo-icon.png')}}" alt="homepage" class="dark-logo" />
                    <!-- Light Logo icon -->
                    <img src="{{ asset('dashboard/assets/images/logo-light-icon.png')}}" alt="homepage"
                        class="light-logo" />
                </b>
                <!--End Logo icon -->
                <!-- Logo text -->
                <span class="logo-text">
                    <!-- dark Logo text -->
                    <img src="{{ asset('dashboard/assets/images/logo-text.png')}}" alt="homepage" class="dark-logo" />
                    <!-- Light Logo text -->
                    <img src="{{ asset('dashboard/assets/images/logo-light-text.png')}}" class="light-logo"
                        alt="homepage" />
                </span>
            </a>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Toggle which is visible on mobile only -->
            <!-- ============================================================== -->
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-left mr-auto">
                <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light"
                        href="javascript:void(0)" data-sidebartype="mini-sidebar"><i
                            class="mdi mdi-menu font-24"></i></a></li>

            </ul>
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-right">
                <!-- ============================================================== -->
                <!-- create new -->
                <!-- ============================================================== -->
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="flag-icon flag-icon-us"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right  animated bounceInDown"
                        aria-labelledby="navbarDropdown2">
                        <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-us"></i> English</a>
                        <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-fr"></i> French</a>
                        <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-es"></i> Spanish</a>
                        <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-de"></i> German</a>
                    </div>
                </li> --}}
                <!-- ============================================================== -->
                <!-- Comment -->
                <!-- ============================================================== -->
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-bell font-24"></i>

                    </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                        <span class="with-arrow"><span class="bg-primary"></span></span>
                        <ul class="list-style-none">
                            <li>
                                <div class="drop-title bg-primary text-white">
                                    <h4 class="m-b-0 m-t-5">4 New</h4>
                                    <span class="font-light">Notifications</span>
                                </div>
                            </li>
                            <li>
                                <div class="message-center notifications">
                                    <!-- Message -->
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="btn btn-danger btn-circle"><i class="fa fa-link"></i></span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Luanch Admin</h5> <span class="mail-desc">Just see
                                                the my new admin!</span> <span class="time">9:30 AM</span>
                                        </div>
                                    </a>
                                    <!-- Message -->
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="btn btn-success btn-circle"><i class="ti-calendar"></i></span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Event today</h5> <span class="mail-desc">Just a
                                                reminder that you have event</span>
                                            <span class="time">9:10 AM</span>
                                        </div>
                                    </a>
                                    <!-- Message -->
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="btn btn-info btn-circle"><i class="ti-settings"></i></span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Settings</h5> <span class="mail-desc">You
                                                can customize this template as you want</span> <span class="time">9:08
                                                AM</span>
                                        </div>
                                    </a>
                                    <!-- Message -->
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="btn btn-primary btn-circle"><i class="ti-user"></i></span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Pavan kumar</h5> <span class="mail-desc">Just see
                                                the my admin!</span> <span class="time">9:02 AM</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <a class="nav-link text-center m-b-5 text-dark" href="javascript:void(0);">
                                    <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
                <!-- ============================================================== -->
                <!-- End Comment -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="{{route('seller.profile')}}"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                            src="{{ asset('dashboard/assets/images/users/1.jpg')}}" alt="user" class="rounded-circle"
                            width="31"></a>
                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        <span class="with-arrow"><span class="bg-primary"></span></span>
                        <div class="d-flex no-block align-items-center p-15 bg-primary text-white m-b-10">
                            <div class=""><img src="{{ asset('dashboard/assets/images/users/1.jpg')}}" alt="user"
                                    class="img-circle" width="60"></div>
                            <div class="m-l-10">
                                <h4 class="m-b-0">{{Auth::user()->name}}</h4>
                                <p class=" m-b-0">{{Auth::user()->email}}</p>
                            </div>
                        </div>
                        <a class="dropdown-item" href="{{route('seller.profile')}}"><i class="ti-user m-r-5 m-l-5"></i>
                            My Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" class="btn btn-primary"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-power-off m-r-5 m-l-5"></i>
                            Logout</a>
                            {{-- logout form --}}
                            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none">
                                {{ csrf_field() }}
                                @csrf
                            </form>
                            {{-- end of logout form --}}
                    </div>
                </li>
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
            </ul>
        </div>
    </nav>
</header>
<!-- ============================================================== -->
<!-- End Topbar header -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- User Profile-->
                <li>
                    <!-- User Profile-->
                    <div class="user-profile d-flex no-block dropdown m-t-20">
                        <div class="user-pic"><img src="{{ asset('dashboard/assets/images/users/1.jpg')}}" alt="users"
                                class="rounded-circle" width="40" /></div>
                        <div class="user-content hide-menu m-l-10">
                            <a href="javascript:void(0)" class="" id="Userdd" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <h5 class="m-b-0 user-name font-medium">{{Auth::user()->name}} </h5>
                                <span class="op-5 user-email" style="text-transform: uppercase">{{Auth::user()->role}}</span>
                            </a>
                        </div>
                    </div>
                    <!-- End User Profile-->
                </li>

                <!-- User Profile-->
                <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span
                        class="hide-menu">Important Links</span></li>

                <li class="sidebar-item"><a href="{{route('seller')}}" class="sidebar-link"><i
                            class="mdi mdi-view-dashboard"></i><span class="hide-menu"> Dashboard </span></a>
                </li>
                
                <li class="sidebar-item"><a href="{{ route('seller.sales')}}" class="sidebar-link"><i
                                    class="mdi mdi-adjust"></i><span class="hide-menu"> Sales </span></a>
                        </li>

                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark"
                        href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-credit-card-multiple"></i><span
                            class="hide-menu">Transactions </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        {{-- <li class="sidebar-item"><a href="#" class="sidebar-link"><i
                                    class="mdi mdi-adjust"></i><span class="hide-menu"> All </span></a></li> --}}
                        
                        <li class="sidebar-item"><a href="{{route('seller.expenses')}}" class="sidebar-link"><i
                                    class="mdi mdi-adjust"></i><span class="hide-menu">Operating Expenses
                                </span></a></li>
                        <li class="sidebar-item"><a href="{{route('seller.income')}}" class="sidebar-link"><i
                                    class="mdi mdi-adjust"></i><span class="hide-menu">Other Income </span></a>
                        </li>
                        <li class="sidebar-item"><a href="{{route('seller.payment')}}" class="sidebar-link"><i
                                    class="mdi mdi-adjust"></i><span class="hide-menu"> Supplier Payment </span></a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark"
                        href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-inbox"></i><span
                            class="hide-menu">Inventory </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        {{-- <li class="sidebar-item"><a href="#" class="sidebar-link"><i
                                    class="mdi mdi-adjust"></i><span class="hide-menu"> All </span></a></li> --}}
                        <li class="sidebar-item"><a href="{{route('seller.product')}}" class="sidebar-link"><i
                                    class="mdi mdi-adjust"></i><span class="hide-menu"> Product </span></a>
                        </li>
                        {{-- <li class="sidebar-item"><a href="{{route('seller.category')}}" class="sidebar-link"><i
                                    class="mdi mdi-adjust"></i><span class="hide-menu"> Category
                                </span></a></li> --}}
                        <li class="sidebar-item"><a href="{{route('seller.receipt')}}" class="sidebar-link"><i
                                    class="mdi mdi-adjust"></i><span class="hide-menu"> (GRN) Receipt
                                </span></a></li>
                        
                        <li class="sidebar-item"><a href="{{route('seller.lens.stock',0)}}" class="sidebar-link"><i
                            class="mdi mdi-adjust"></i><span class="hide-menu"> lens Stock
                        </span></a></li>
                    </ul>
                </li>

                {{-- <li class="sidebar-item"><a href="{{ route('seller.cutomerInvoice')}}" class="sidebar-link"><i
                            class="mdi mdi-archive"></i><span class="hide-menu"> Statement Invoice </span></a>
                </li> --}}
                <li class="sidebar-item"><a href="{{ route('seller.all.invoices')}}" class="sidebar-link"><i
                            class="mdi mdi-archive"></i><span class="hide-menu"> Invoice </span></a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
