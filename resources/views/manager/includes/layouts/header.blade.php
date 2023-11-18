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
                    <img src="{{ asset('dashboard/assets/images/logo-icon.png') }}" alt="homepage" class="dark-logo" />
                    <!-- Light Logo icon -->
                    <img src="{{ asset('dashboard/assets/images/logo-light-icon.png') }}" alt="homepage"
                        class="light-logo" />
                </b>
                <!--End Logo icon -->
                <!-- Logo text -->
                <span class="logo-text">
                    <!-- dark Logo text -->
                    <img src="{{ asset('dashboard/assets/images/logo-text.png') }}" alt="homepage" class="dark-logo" />
                    <!-- Light Logo text -->
                    <img src="{{ asset('dashboard/assets/images/logo-light-text.png') }}" class="light-logo"
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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="flag-icon flag-icon-{{ App::getLocale() == 'en' ? 'us' : App::getLocale() }}"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right  animated bounceInDown"
                        aria-labelledby="navbarDropdown2">
                        @foreach (Config::get('language') as $lang => $language)
                            <a class="dropdown-item" href="{{ route('lang.switch', $lang) }}">
                                <i class="flag-icon flag-icon-{{ $lang == 'en' ? 'us' : $lang }}"></i>
                                {{ $language }}
                            </a>
                        @endforeach

                    </div>
                </li>
                <!-- ============================================================== -->
                <!-- Comment -->
                <!-- ============================================================== -->
                @if ($count_notification > 0)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i
                                class="mdi mdi-bell font-24" style="color: red"></i>

                        </a>
                        <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                            <span class="with-arrow"><span class="bg-primary"></span></span>
                            <ul class="list-style-none">
                                <li>
                                    <div class="drop-title bg-primary text-white">
                                        <h4 class="m-b-0 m-t-5">{{ $count_notification }} New</h4>
                                        <span class="font-light">Notifications</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="message-center notifications">
                                        <!-- Message -->
                                        @foreach ($notifications as $notification)
                                            <a href="#" class="message-item">
                                                <span class="btn btn-danger btn-circle"><i
                                                        class="fa fa-link"></i></span>
                                                <div class="mail-contnet">
                                                    <h5 class="message-title">
                                                        {{ \App\Models\CompanyInformation::where('id', $notification->company_id)->pluck('company_name')->first() }}'s
                                                        @if ($notification->notification == 'New Credit Request')
                                                            Credit Request
                                                        @else
                                                            Order
                                                        @endif
                                                    </h5>
                                                    <span class="mail-desc">{{ $notification->notification }}</span>
                                                    <span
                                                        class="time">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </li>
                                <li>
                                    <a class="nav-link text-center m-b-5 text-dark"
                                        href="{{ route('manager.notification.clear') }}"> <strong>Clear all
                                            notifications</strong> <i class="fa fa-trash"></i> </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <!-- ============================================================== -->
                <!-- End Comment -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic"
                        href="{{ route('manager.profile') }}" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"><img src="{{ asset('dashboard/assets/images/users/1.jpg') }}"
                            alt="user" class="rounded-circle" width="31"></a>
                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        <span class="with-arrow"><span class="bg-primary"></span></span>
                        <div class="d-flex no-block align-items-center p-15 bg-primary text-white m-b-10">
                            <div class=""><img src="{{ asset('dashboard/assets/images/users/1.jpg') }}"
                                    alt="user" class="img-circle" width="60"></div>
                            <div class="m-l-10">
                                <h4 class="m-b-0">{{ Auth::user()->name }}</h4>
                                <p class=" m-b-0">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <a class="dropdown-item" href="{{ route('manager.profile') }}"><i
                                class="ti-user m-r-5 m-l-5"></i>
                            {{ __('navigation.my_profile') }}</a>

                        @if (userInfo()->permissions == 'manager')
                            <a class="dropdown-item" href="{{ route('manager.settings') }}"><i
                                    class="ti-settings m-r-5 m-l-5"></i>
                                {{ __('navigation.settings') }}</a>

                            <a class="dropdown-item" href="{{ route('manager.clinic.settings') }}"><i
                                    class="ti-settings m-r-5 m-l-5"></i>
                                {{ __('navigation.clinic_settings') }}</a>
                        @endif

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" class="btn btn-primary"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                class="fa fa-power-off m-r-5 m-l-5"></i>
                            {{ __('navigation.logout') }}</a>
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
                        <div class="user-pic"><img src="{{ asset('dashboard/assets/images/users/1.jpg') }}"
                                alt="users" class="rounded-circle" width="40" /></div>
                        <div class="user-content hide-menu m-l-10">
                            <a href="javascript:void(0)" class="" id="Userdd" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <h5 class="m-b-0 user-name font-medium">{{ Auth::user()->name }} </h5>
                                <span class="op-5 user-email"
                                    style="text-transform: uppercase">{{ Auth::user()->role }}</span>
                            </a>
                        </div>
                    </div>
                    <!-- End User Profile-->
                </li>


                <li class="sidebar-item"><a href="{{ route('manager') }}" class="sidebar-link"><i
                            class="mdi mdi-view-dashboard"></i><span class="hide-menu">
                            {{ __('navigation.dashboard') }} </span></a>
                </li>

                @if (userInfo()->permissions == 'manager' || userInfo()->permissions == 'seller')
                    <!-- User Profile-->
                    <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i>
                        <span class="hide-menu">{{ __('navigation.dispensing_dept') }}</span>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{ route('manager.sales') }}" class="sidebar-link">
                            <i class="mdi mdi-adjust"></i>
                            <span class="hide-menu"> {{ __('navigation.sales') }} </span>
                        </a>
                    </li>

                    {{-- <li class="sidebar-item"><a href="{{ route('manager.order') }}" class="sidebar-link"><i
                                class="mdi mdi-cart-plus"></i><span class="hide-menu">
                                {{ __('navigation.lab_orders') }} </span></a>
                    </li> --}}

                    <li class="sidebar-item">
                        <a href="{{ route('manager.proforma') }}" class="sidebar-link">
                            <i class="mdi mdi-content-paste"></i>
                            <span class="hide-menu"> {{ __('navigation.insurance') }} </span>
                        </a>
                    </li>
                @endif

                @if (userInfo()->permissions == 'manager' || userInfo()->permissions == 'store' || userInfo()->permissions == 'lab')
                    <!-- User Profile-->
                    <li class="nav-small-cap">
                        <i class="mdi mdi-dots-horizontal"></i>
                        <span class="hide-menu">{{ __('navigation.stock_dept') }} </span>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{ route('manager.product') }}" class="sidebar-link">
                            <i class="mdi mdi-adjust"></i>
                            <span class="hide-menu">
                                {{ __('navigation.product_nav') }}
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="#!" aria-expanded="false">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span class="hide-menu">Lab Request </span>
                            <span class="badge badge-danger badge-pill ml-2">
                                {{$orderCount}}
                            </span>
                        </a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item">
                                    <a href="{{route('manager.lab.requests.type','requested')}}" class="sidebar-link">
                                        <i class="mdi mdi-adjust"></i>
                                        <span class="hide-menu"> Requested </span>
                                        <span class="badge badge-danger badge-pill ml-2">
                                            {{$requested}}
                                        </span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{route('manager.lab.requests.type','booking')}}" class="sidebar-link">
                                        <i class="mdi mdi-adjust"></i>
                                        <span class="hide-menu"> Booking </span>
                                        <span class="badge badge-danger badge-pill ml-2">
                                            {{$booking}}
                                        </span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{route('manager.lab.requests.type','requested')}}" class="sidebar-link">
                                        <i class="mdi mdi-adjust"></i>
                                        <span class="hide-menu"> Requested </span>
                                        <span class="badge badge-danger badge-pill ml-2">
                                            {{$requested}}
                                        </span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{route('manager.lab.requests.type','priced')}}" class="sidebar-link">
                                        <i class="mdi mdi-adjust"></i>
                                        <span class="hide-menu"> Priced </span>
                                        <span class="badge badge-danger badge-pill ml-2">
                                            {{$priced}}
                                        </span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{route('manager.lab.requests.type','po-sent')}}" class="sidebar-link">
                                        <i class="mdi mdi-adjust"></i>
                                        <span class="hide-menu"> PO Sent </span>
                                        <span class="badge badge-danger badge-pill ml-2">
                                            {{$sentToLab}}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    {{-- <li class="sidebar-item">
                        <a href="{{ route('manager.lab.requests') }}" class="sidebar-link">
                            <i class="mdi mdi-adjust"></i>
                            <span class="hide-menu">Lab Request </span>
                        </a>
                    </li> --}}

                    <li class="sidebar-item"><a href="{{ route('manager.receipt') }}" class="sidebar-link"><i
                                class="mdi mdi-adjust"></i><span class="hide-menu">
                                {{ __('navigation.grn_receipt') }}
                            </span></a>
                    </li>

                    <li class="sidebar-item"><a href="{{ route('manager.lens.stock', 0) }}" class="sidebar-link"><i
                                class="mdi mdi-adjust"></i><span class="hide-menu"> {{ __('navigation.lens_stock') }}
                            </span></a>
                    </li>

                    <li class="sidebar-item"><a href="{{ route('manager.po') }}" class="sidebar-link"><i
                                class="mdi mdi-account-convert"></i><span class="hide-menu">
                                {{ __('navigation.purchase_order') }} </span></a>
                    </li>

                    {{-- <li class="sidebar-item"><a href="{{ route('manager.quations') }}" class="sidebar-link"><i
                                class="mdi mdi-account-convert"></i><span class="hide-menu">
                                {{ __('navigation.quotation') }}</span></a>
                    </li> --}}
                @endif


                <!-- User Profile-->
                @if (userInfo()->permissions == 'manager' || userInfo()->permissions == 'accountant')
                    <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span
                            class="hide-menu">{{ __('navigation.finance_department') }}</span></li>


                    <li class="sidebar-item"><a href="{{ route('manager.all.invoices') }}" class="sidebar-link"><i
                                class="mdi mdi-archive"></i><span class="hide-menu"> {{ __('navigation.invoice') }}
                            </span></a>
                    </li>

                    {{-- <li class="sidebar-item"><a href="{{ route('manager.credit')}}" class="sidebar-link"><i
                                class="mdi mdi-archive"></i><span class="hide-menu"> Invoice Credits </span></a>
                    </li> --}}

                    <li class="sidebar-item"><a href="{{ route('manager.suppliers') }}" class="sidebar-link"><i
                                class="mdi mdi-truck-delivery"></i><span class="hide-menu">
                                {{ __('navigation.suppliers') }} </span></a>
                    </li>

                    {{-- <li class="sidebar-item"><a href="{{ route('manager.clients') }}" class="sidebar-link"><i
                                class="mdi mdi-account-circle"></i><span class="hide-menu">
                                {{ __('navigation.clients') }} </span></a>
                    </li> --}}

                    <li class="sidebar-item"><a href="{{ route('manager.expenses') }}" class="sidebar-link"><i
                                class="mdi mdi-adjust"></i><span
                                class="hide-menu">{{ __('navigation.operating_expenses') }}
                            </span></a></li>
                    <li class="sidebar-item"><a href="{{ route('manager.income') }}" class="sidebar-link"><i
                                class="mdi mdi-adjust"></i><span
                                class="hide-menu">{{ __('navigation.other_income') }} </span></a>
                    </li>
                    <li class="sidebar-item"><a href="{{ route('manager.payment') }}" class="sidebar-link"><i
                                class="mdi mdi-adjust"></i><span class="hide-menu">
                                {{ __('navigation.supplier_payment') }} </span></a>
                    </li>
                @endif



                @if (userInfo()->permissions == 'manager' || userInfo()->permissions == 'accountant')
                    <!-- User Profile-->
                    @if (userInfo()->permissions == 'manager')
                        <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span
                                class="hide-menu">{{ __('navigation.administration_department') }}</span></li>


                        <li class="sidebar-item"><a href="{{ route('manager.users') }}" class="sidebar-link"><i
                                    class="mdi mdi-account-multiple"></i><span class="hide-menu">
                                    {{ __('navigation.users') }} </span></a>
                        </li>
                    @endif

                    @if (userInfo()->permissions == 'manager' || userInfo()->permissions == 'accountant')
                        <li class="sidebar-item"><a href="{{ route('manager.report') }}" class="sidebar-link"><i
                                    class="mdi mdi-note-multiple"></i><span class="hide-menu">
                                    {{ __('navigation.report') }} </span></a>
                        </li>
                    @endif
                @endif


                @if (userInfo()->permissions == 'manager' || userInfo()->permissions == 'lab')
                    <!-- User Profile-->
                    <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span
                            class="hide-menu">{{ __('navigation.laboratory_department') }}</span></li>


                    <li class="sidebar-item"><a href="{{ route('manager.received.order') }}" class="sidebar-link"><i
                                class="mdi mdi-flask"></i><span class="hide-menu"> {{ __('navigation.laboratory') }}
                            </span></a>
                    </li>
                    <li class="sidebar-item"><a href="{{ route('manager.retail') }}" class="sidebar-link"><i
                                class="mdi mdi-adjust"></i><span class="hide-menu">
                                {{ __('navigation.lab_request') }} </span></a>
                    </li>
                    {{-- <li class="sidebar-item"><a href="{{ route('manager.cutomerInvoice')}}" class="sidebar-link"><i
                        class="mdi mdi-archive"></i><span class="hide-menu"> Statement Invoice </span></a>
                    </li> --}}
                @endif

                @if (userInfo()->permissions == 'manager' || userInfo()->permissions == 'doctor' || userInfo()->permissions == 'seller')
                    @if (\App\Models\CompanyInformation::where('id', Auth::user()->company_id)->pluck('is_clinic')->first() == '1')
                        <!-- User Profile-->
                        <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i>
                            <span class="hide-menu">{{ __('navigation.clinic_department') }}</span>
                        </li>

                        <li class="sidebar-item">
                            <a href="{{ route('manager.patients') }}" class="sidebar-link">
                                <i class="mdi mdi-hospital"></i>
                                <span class="hide-menu"> {{ __('navigation.patient') }} </span>
                            </a>
                        </li>
                    @endif
                @endif

                @if (userInfo()->permissions != 'manager' && userInfo()->permissions != 'store')
                    <li class="sidebar-item">
                        <a href="{{ route('manager.product') }}" class="sidebar-link">
                            <i class="mdi mdi-adjust"></i>
                            <span class="hide-menu">
                                {{ __('navigation.product_nav') }}
                            </span>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
