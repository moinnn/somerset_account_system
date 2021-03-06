<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Somerset Homeowners Management System | </title>

    <link rel="shortcut icon" href="{{ URL::asset('images/favicon.png')}}">
    <!-- Bootstrap -->
    <link href="{{ URL::asset('vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ URL::asset('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ URL::asset('vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
    <!-- Datatables -->
    <link href="{{ URL::asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">
    <!-- Fontawesome -->
    <link href="{{ URL::asset('vendors/fontawesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- Select2 -->
    <link href="{{ URL::asset('vendors/select2/dist/css/select2.min.css')}}" rel="stylesheet">
    <!-- Bootstrap Daterangepicker -->
    <link rel="stylesheet" href="{{URL::asset('vendors/bootstrap-daterangepicker/daterangepicker.css')}}">
    <!-- Custom Theme Style -->
    <link href="{{ URL::asset('css/custom.css')}}" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="{{route('guestdashboard')}}" class="site_title"><i class="fa fa-home"></i> <span>Somerset Accounting System</span></a>
            </div>

            <div class="clearfix"></div>
            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <ul class="nav side-menu">
                  <li><a href="{{route('guestdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                  <li><a href="{{route('guesttransactionhistory')}}"><i class="fa fa-clock-o"></i> Transaction History </a></li>
                  <li><a href="{{route('guestpendingpayments')}}"><i class="fa fa-money"></i> Pending Payments </a></li>
                  <!--li><a href="#"><i class="fa fa-user"></i> My Profile </a></li-->
                  <!--li><a href="{{route('map.somerset')}}"><i class="fa fa-dashboard"></i> Somerset Map </a></li-->
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <!--a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a-->
              <a href="#" data-toggle="tooltip" data-placement="top" title="FullScreen" onclick="toggleFullScreen();">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true" ></span>
              </a>
              <script type="text/javascript">
                function toggleFullScreen() {
                  if ((document.fullScreenElement && document.fullScreenElement !== null) ||    
                   (!document.mozFullScreen && !document.webkitIsFullScreen)) {
                    if (document.documentElement.requestFullScreen) {  
                      document.documentElement.requestFullScreen();  
                    } else if (document.documentElement.mozRequestFullScreen) {  
                      document.documentElement.mozRequestFullScreen();  
                    } else if (document.documentElement.webkitRequestFullScreen) {  
                      document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);  
                    }  
                  } else {  
                    if (document.cancelFullScreen) {  
                      document.cancelFullScreen();  
                    } else if (document.mozCancelFullScreen) {  
                      document.mozCancelFullScreen();  
                    } else if (document.webkitCancelFullScreen) {  
                      document.webkitCancelFullScreen();  
                    }  
                  }  
                }
                
              </script>
              <!--a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a-->
              <a href="{{ route('logout') }}" data-toggle="tooltip" data-placement="top" title="Logout">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav class="" role="navigation">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    @if(Auth::user()->home_owner_id != NULL)
                      {{Auth::user()->homeOwner->first_name}} {{Auth::user()->homeOwner->last_name}} 
                    @else
                      {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    @endif
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="{{route('users.show',Auth::user()->id)}}"> Profile</a></li>
                    <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>

                <li role="presentation" class="dropdown">
                  <!--a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green">6</span>
                  </a-->
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <a>
                        <span class="image"><img src="../images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          @include('flash::message')
          @yield('content')
          <div class="clearfix"></div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
              Somerset Accounting System
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>  

   <!-- jQuery -->
  <script src="{{ URL::asset('vendors/jquery/dist/jquery.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{ URL::asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
  <!-- FastClick -->
  <script src="{{ URL::asset('vendors/fastclick/lib/fastclick.js')}}"></script>
  <!-- NProgress -->
  <script src="{{ URL::asset('vendors/nprogress/nprogress.js')}}"></script>
  <!-- Data Tables -->
  <script src="{{ URL::asset('vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{ URL::asset('vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
  <!-- Custom Theme Scripts -->
  <script src="{{ URL::asset('js/custom.js')}}"></script>
    
    <script>
      $(document).ready(function(){
          $('#datatable').dataTable();
          $('#flash-overlay-modal').modal();
      });
    </script>
  </body>
</html>