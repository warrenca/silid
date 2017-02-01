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
          <a href="javascript:void(0)" class="brand-logo center"><img src="/images/silid-60px.jpg" height="60"/></a>
          @if (isset($_SESSION['token']))
          <ul id="dropdown1" class="dropdown-content">
            <li><a href="/booking/list">List all</a></li>
            <li><a href="/booking/own">View own</a></li>
          </ul>
          <ul id="nav-mobile" class="hide-on-med-and-down">
            <li><a href="/booking">Book Now</a></li>
            <!-- Dropdown Trigger -->
            <li><a class="dropdown-button" href="#!" data-activates="dropdown1">Bookings<i class="material-icons right">arrow_drop_down</i></a></li>
            <li class="right">
              <a class="waves-effect waves-light btn red accent-3" href="/logout" onclick="return confirm('Are you sure you want to Sign-out?')">Sign out<i class="material-icons">power_settings_new</i></a>
            </li>
          </ul>
          @endif
        </div>
      </nav>

      <h1 style="color: #13c16c"><img src="/images/silid-60px.jpg" height="60"/>ilid Booking</h1>

      @yield('content')
    </div>
  </body>
  </html>
