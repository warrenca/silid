<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Silid Room bookings</title>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
    <link rel="stylesheet" href="/css/styles.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
    <script src="/js/pickadate.js/lib/picker.time.js"></script>
    <script src="/js/scripts.js"></script>
  </head>
  <body>
    <div class="container">
      <nav>
        <div class="nav-wrapper green accent-4">
          <a href="javascript:void(0)" class="brand-logo center" style="height: 60px">
            <img src="/images/silid-60px.jpg" height="60" class="z-depth-2 circle"/>
          </a>
          @if (isset($_SESSION['token']))
          <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
          <ul id="dropdown1" class="dropdown-content">
            <li><a href="/booking/view-all/{{date('Y-m-d')}}/confirmed">View all</a></li>
            <li><a href="/booking/view-own/{{date('Y-m-d')}}/confirmed">View own</a></li>
          </ul>
          <ul id="nav-mobile" class="hide-on-med-and-down">
            <li><a href="/booking">Book Now</a></li>
            <!-- Dropdown Trigger -->
            <li><a class="dropdown-button" href="#!" data-activates="dropdown1">Bookings<i class="material-icons right">arrow_drop_down</i></a></li>
            <li class="right">
              <a class="waves-effect waves-light btn red accent-3" href="/logout" onclick="return confirm('Are you sure you want to Sign-out?')">Sign out<i class="material-icons">power_settings_new</i></a>
            </li>
          </ul>
          @elseif (app()->request->segment(1) != 'login' && !isset($_SESSION['token']))
          <ul id="nav-mobile" class="hide-on-med-and-down">
            <li>
              <a href="/socialite/google/login">
                <img src="/images/btn_google_signin_dark_normal_web.png">
              </a>
            </li>
          </ul>
          @endif
        </div>
      </nav>

      @if (isset($_SESSION['token']))
      <ul id="slide-out" class="side-nav">
        <li><a href="/booking">Book Now<i class="material-icons">mode_edit</i></a></li>
        <li class="no-padding">
          <ul class="collapsible collapsible-accordion">
            <li>
              <a class="collapsible-header">Bookings<i class="material-icons">view_list</i></a>
              <div class="collapsible-body">
                <ul>
                  <li><a href="/booking/view-all/{{date('Y-m-d')}}/confirmed">View all</a></li>
                  <li><a href="/booking/view-own/{{date('Y-m-d')}}/confirmed">View own</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </li>
        <li><div class="divider"></div></li>
        <li><div class="divider"></div></li>
        <li>
          <a class="red accent-3" href="/logout" onclick="return confirm('Are you sure you want to Sign-out?')">Sign out<i class="material-icons">power_settings_new</i></a>
        </li>
      </ul>
      @endif

      <h1 style="color: #13c16c"><img src="/images/silid-60px.jpg" height="60"/>ilid Booking</h1>

      @yield('content')
    </div>
  </body>
  </html>
