@extends('layouts.master')
@section('content')
  @if (count($errors) > 0)
  <blockquote>
      <ul>
          @foreach ($errors as $k=>$error)
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
  <div class="col s12 m2 center-align">
    <p class="z-depth-3" style="padding: 15px 0"># Sign-in with Google! using an email from the following allowed domains: {{$allowed_domains}}<br/>
      <a href="/socialite/google/login">
        <img src="/images/btn_google_signin_dark_normal_web.png" height="30">
      </a>
    </p>
  </div>

  @if (env('SILID_DISPLAY_BOOKINGS_IN_LOGIN_PAGE'))
  <h4>Meeting room bookings for today.</h4>
  <table class="bordered striped">
   <thead>
     <tr>
       <th data-field="room">Room</th>
       <th data-field="purpose">Purpose</th>
       <th data-field="reserved_by">Reserved By</th>
       <th data-field="time">Time (start-end)</th>
     </tr>
   </thead>

   <tbody>
     @forelse ($bookings as $booking)
     <tr>
       <td>{{$booking->room->name}}</td>
       <td>{{$booking->purpose}}</td>
       <td>{{$booking->reserved_by}}</td>
       <td>
         <a href="{{generateBookingViewLink($booking->id)}}">
         {{date('h:i A', strtotime($booking->start))}} - {{date('h:i A', strtotime($booking->end))}}
         </a>
       </td>
     </tr>
     @empty
     <tr>
        <td colspan="4">No bookings today</td>
     </tr>
     @endforelse
   </tbody>
  </table>
  @endif
@stop
