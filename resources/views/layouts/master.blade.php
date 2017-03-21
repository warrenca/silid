<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>
      @if (env('COMPANY_HEADING')!='' && env('COMPANY_NAME')!='')
        {{env('COMPANY_HEADING')}} | {{env('COMPANY_NAME')}}
      @else
        Silid Room bookings
      @endif
    </title>
    <!--Import Google Icon Font-->
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
    <link rel="stylesheet" href="/css/styles.css">

    <!-- Compiled and minified JavaScript -->
    <script src="/js/materialize.js"></script>
    <script src="/js/pickadate.js/lib/picker.time.js"></script>
    <script src="/js/scripts.js"></script>
    <style>
    @yield('style')
    </style>
    @yield('script')
  </head>
  <body>
    <header>
    <div>
      <nav>
        <div class="nav-wrapper
        @if(env('COMPANY_BASE_THEME_COLOR')!='')
          {{env('COMPANY_BASE_THEME_COLOR')}}
        @else
          green accent-5
        @endif
        ">
          <a href="/" class="brand-logo center" style="height: 60px">
            @if( file_exists("images/company/".env('COMPANY_LOGO')) && !is_dir("images/company/".env('COMPANY_LOGO')) )
              <img src="/images/company/{{env('COMPANY_LOGO')}}" height="60" class="z-depth-2"/>
            @else
              <img src="/images/silid-60px.jpg" height="60" class="z-depth-2"/>
            @endif
          </a>
          @if (isset($_SESSION['token']))
          <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
          <ul id="nav-mobile" class="hide-on-med-and-down">
            <li @if(app()->request->is('booking')) class='active'@endif><a href="/booking">Book Now</a></li>
            <li @if(app()->request->segment(2)=='view-all') class="active"@endif><a href="/booking/view-all/{{date('Y-m-d')}}/confirmed">All Bookings</a></li>
            <li @if(app()->request->segment(2)=='view-own') class="active"@endif><a href="/booking/view-own/{{date('Y-m-d')}}/confirmed">Own Bookings</a></li>
            <li class="right">
              <a class="waves-effect waves-light btn red accent-3" href="/logout" onclick="return confirm('Are you sure you want to Sign-out?')">Sign out</a>
            </li>
          </ul>
          @elseif (app()->request->segment(1) != 'login' && !isset($_SESSION['token']))
          <ul id="nav-mobile" class="hide-on-med-and-down">
            <li>
              <a href="/socialite/google/login">
                <img src="/images/btn_google_signin_dark_normal_web.png" height="30" style="vertical-align:middle">
              </a>
            </li>
          </ul>
          @endif
        </div>
      </nav>

      @if (isset($_SESSION['token']))
      <ul id="slide-out" class="side-nav">
        <li><a href="/booking">Book Now<i class="material-icons">mode_edit</i></a></li>
        <li><a href="/booking/view-all/{{date('Y-m-d')}}/confirmed">All Bookings<i class="material-icons">view_list</i></a></li>
        <li><a href="/booking/view-own/{{date('Y-m-d')}}/confirmed">Own Bookings<i class="material-icons">view_list</i></a></li>
        <li><div class="divider"></div></li>
        <li><div class="divider"></div></li>
        <li>
          <a class="red accent-3" href="/logout" onclick="return confirm('Are you sure you want to Sign-out?')">Sign out<i class="material-icons">power_settings_new</i></a>
        </li>
      </ul>
      @endif

      <div class="container">
      @if (env('COMPANY_HEADING')!='')
      <h4 class="{{env('COMPANY_BASE_COLOR')}}">{{env('COMPANY_HEADING')}}</h4>
      @else
      <h1 class="green-text"><img src="/images/silid-60px.jpg" height="60"/>ilid Booking</h1>
      @endif
      </div>
    </div>
    </header>
    <main>
      <div class="container">
        @yield('content')
      </div>
    </main>

    <div>
      <footer class="page-footer
        @if(env('COMPANY_BASE_THEME_COLOR')!='')
          {{env('COMPANY_BASE_THEME_COLOR')}}
        @else
          green accent-5
        @endif
        ">
         <div class="footer-copyright">
           <div class="container">
           Powered by Silid Meeting Room Booking 2017
           <a class="grey-text text-lighten-4 right" href="https://github.com/warrenca/silid"><img src="/images/silid-60px.jpg" height="15" class="z-depth-2" style="vertical-align: text-top"/>  Silid@GitHub</a>
           </div>
         </div>
      </footer>
    </div>

  </body>
  </html>
