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
      <h1><img src="/images/silid-60px.jpg"/>ilid Booking</h1>
      <div class="col s12 m2 center-align">
        <p class="z-depth-3" style="padding: 15px 0">
          <i class="material-icons">info</i> Select a booking date and time.
        </p>
      </div>

      @if (count($booking_errors) > 0)
      <blockquote>
          <ul>
              @foreach ($booking_errors as $k=>$error)
                  <li>
                    @if ($k==0)
                      <i class="material-icons">warning</i>
                    @endif
                    {{ $error }}
                  </li>
              @endforeach
          </ul>
      </blockquote>
      @endif

      <form class="col s12" name="booking" method="POST">
        <div class="row">
          <div class="input-field col s6">
            <select name="room_id">
              <option value="" disabled selected>Select Room</option>
              <option value="1">Conference Room</option>
              <option value="2">Room A</option>
              <option value="3">Room B</option>
            </select>
            <label>Select Room</label>
          </div>
          <div class="input-field col s6">
            <input placeholder="Email" id="reserved_by" type="text" value="{{$email}}" disabled>
            <input type="hidden" name="reserved_by" value="{{$email}}">
            <label for="reserved_by">Reserved by</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <input placeholder="Date" id="booking_date" type="text" class="datepicker" name="booking_date">
            <label for="booking_date">Select booking date</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s6">
            <input placeholder="Time" id="booking_time" type="text" class="timepicker" name="booking_time">
            <label for="booking_time">Select booking time</label>
          </div>
          <div class="input-field col s6">
            <select name="booking_duration">
              <option value="" disabled selected>Booking duration</option>
              <option value="1800">30 mins</option>
              <option value="3600">1 hr</option>
              <option value="5400">1.5 hr</option>
              <option value="7200">2 hr</option>
              <option value="9000">2.5 hr</option>
              <option value="10800">3 hr</option>
              <option value="12600">3.5 hr</option>
              <option value="14400">Half day (4hrs)</option>
              <option value="86400">Full day</option>
            </select>
            <label>Duration</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <button class="btn waves-effect waves-light" type="submit" name="action">Submit
              <i class="material-icons right">send</i>
            </button>
          </div>
        </div>
      </form>
    </div>
  </body>
  </html>
