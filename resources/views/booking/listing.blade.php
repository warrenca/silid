@extends('layouts.master')
@section('content')
<div class="col s12 m2 center-align">
  <p class="z-depth-3" style="padding: 15px 0">
    @if (app()->request->segment(2)=='view-all')
    <i class="material-icons">supervisor_account</i> Everyone's Bookings
    @else
    <i class="material-icons">person_pin</i> Your own bookings
    @endif
  </p>
</div>

<form class="col s12" name="booking" method="POST">
  <div class="row">
    <div class="input-field col s6 m3">
      <input placeholder="Date" id="booking_date" type="text" class="listing-datepicker" name="booking_date" value="{{$date}}">
      <label for="booking_date">Bookings for the date:</label>
    </div>
    <input type="hidden" name="status" value="{{$status}}">
    <div class="input-field col s4">
      <button class="btn waves-effect waves-light
      @if (env('COMPANY_BASE_THEME_COLOR')!='')
        {{env('COMPANY_BASE_THEME_COLOR')}}
      @else
        green accent-5
      @endif
      " type="submit" name="action">Submit
        <i class="material-icons right">send</i>
      </button>
    </div>
  </div>
</form>

<a class="waves-effect waves-teal btn-flat {{$confirmed_active}}" href="/booking/{{app()->request->segment(2)}}/{{app()->request->segment(3)}}/confirmed">Confirmed</a>
<a class="waves-effect waves-teal btn-flat {{$cancelled_active}}" href="/booking/{{app()->request->segment(2)}}/{{app()->request->segment(3)}}/cancelled">Cancelled</a>


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
@stop

@section('style')
  @if (env('COMPANY_PICKADATE_THEME_COLOR_TOP')!='')
  .picker__date-display, .picker__weekday-display{
      background-color: {{env('COMPANY_PICKADATE_THEME_COLOR_TOP')}};
  }
  @endif

  @if (env('COMPANY_PICKADATE_THEME_COLOR_TOP')!='')
  .picker__box{
    background-color: {{env('COMPANY_PICKADATE_THEME_COLOR_BOTTOM')}};
  }
  @endif
@stop
