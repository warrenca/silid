@extends('layouts.master')
@section('content')
<form class="col s12" name="booking" method="POST">
  <div class="row">
    <div class="input-field col s4">
      <input placeholder="Date" id="booking_date" type="text" class="listing-datepicker" name="booking_date" value="{{$date}}">
      <label for="booking_date">Bookings for the date:</label>
    </div>
    <input type="hidden" name="status" value="{{$status}}">
    <div class="input-field col s4">
      <button class="btn waves-effect waves-light light-blue accent-3" type="submit" name="action">Submit
        <i class="material-icons right">send</i>
      </button>
    </div>
  </div>
</form>

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
         {{date('H:i:s', strtotime($booking->start))}} - {{date('H:i:s', strtotime($booking->end))}}
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
