<!DOCTYPE html>
<html lang="en">

@include('component/head')

<body class="nav-md">
    <!-- jQuery -->
    <script src="{{asset('/assets/vendors/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('/js/terbilangs.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- live serch -->
    
    <div class="container body" style="">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="index.html" class="site_title"><i class="fa fa-user"></i> <span>Welcome</span></a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->

                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    
                    @if(session()->get('level') == 1)
                    @include('component.sidebar_pimpinan')
                    @elseif(session()->get('level') == 2 || session()->get('level') == 4)
                    @include('component.sidebar_super_admin')
                    @elseif(session()->get('level') == 3)
                    @include('component.sidebar')
                    @endif
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Settings">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Lock">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>

            <!-- top navigation -->
            @include('component.topnav')
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                            <h3>@yield('page-title')</h3>
                        </div>

                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12  ">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->

            <!-- footer content -->
            @include('component.footer')
            <!-- /footer content -->
        </div>
    </div>

    @include('component.script')
</body>

</html>
