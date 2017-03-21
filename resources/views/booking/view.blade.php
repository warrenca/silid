@extends('layouts.master')
@section('content')
<div class="col s12 m7">
  @if ($success_message != "")
  <blockquote class="green lighten-2 success">
    <i class="material-icons">info_outline</i> {{$success_message}}
  </blockquote>
  @endif

  <h5 class="header">
    The room was booked by {{$booking->reserved_by}} for the room:<br/>
    <i><u>{{$booking->room->name}}</u></i><br/><br/>
  Purpose: <i>{{$booking->purpose}}</i></h5>
  <div class="card horizontal hide-on-med-and-down">
    <div class="card-image">
      <img src="/images/room-{{$booking->room->id}}.jpg">
    </div>
    <div class="card-stacked">
      <div class="card-content">
        <ul>
            <li>Start: <b>{{date('F d, Y @h:i A', strtotime($booking->start))}}</b></li>
            <li>End: <b>{{date('F d, Y @h:i A', strtotime($booking->end))}}</b></li>
            <li>Room description: <b>{{$booking->room->description}}</b></li>
            @if (isset($_SESSION['email']) && $booking->reserved_by==$_SESSION['email'] && $booking->confirmed)
            <li>
              <form action="{{$cancellation_link}}" method="post">
                <br/>If you want to cancel this booking <button type="submit" class="btn-flat red accent-1 white-text" onclick="return confirm('Are you sure you want to cancel?')">cancel here</button>
              </form>
            </li>
            @elseif ($booking->status=='unconfirmed')
            <li><br/><small><i>This booking is not yet confirmed. To lock the room, immediately look for the following subject on your email and follow the instruction:<br/>"Reference Code: {{$confirmation_id}}"</i></small></li>
            @endif
        </ul>
      </div>
      <div class="card-action
        @if ($booking->status=='confirmed')
          green lighten-2
        @elseif ($booking->status=='cancelled')
          red lighten-5
        @else
          red lighten-3
        @endif
        ">
        @if ($booking->status=='confirmed')
          <b><i class="material-icons">lock</i> The is room locked and confirmed!</b>
        @elseif ($booking->status=='cancelled')
          <b><i class="material-icons">lock_open</i> The room booking was cancelled</b>
        @else
          <b><i class="material-icons">lock_open</i> The room and timing is open!</b>
        @endif
      </div>
    </div>
  </div>

  <div class="row hide-on-large-only">
   <div class="col s12 m7">
     <div class="card">
       <div class="card-image">
         <img src="/images/room-{{$booking->room->id}}.jpg">
       </div>
       <div class="card-content">
         <ul>
             <li>Start: <b>{{date('F d, Y @h:i A', strtotime($booking->start))}}</b></li>
             <li>End: <b>{{date('F d, Y @h:i A', strtotime($booking->end))}}</b></li>
             <li>Room description: <b>{{$booking->room->description}}</b></li>
             @if (isset($_SESSION['email']) && $booking->reserved_by==$_SESSION['email'] && $booking->confirmed)
             <li>
               <form action="{{$cancellation_link}}" method="post">
               <br/>If you want to cancel this booking <button type="submit" class="btn-flat red accent-1 white-text" onclick="return confirm('Are you sure you want to cancel?')">cancel here</button>
               </form>
             </li>
             @elseif ($booking->status=='unconfirmed')
             <li><br/><small><i>This booking is not yet confirmed. To lock the room, immediately look for the following subject on your email and follow the instruction:<br/>"Reference Code: {{$confirmation_id}}"</i></small></li>
             @endif
         </ul>
       </div>
       <div class="card-action
         @if ($booking->status=='confirmed')
           green lighten-2
         @elseif ($booking->status=='cancelled')
           red lighten-5
         @else
           red lighten-3
         @endif
         ">
         @if ($booking->status=='confirmed')
           <b><i class="material-icons">lock</i> The is room locked and confirmed!</b>
         @elseif ($booking->status=='cancelled')
           <b><i class="material-icons">lock_open</i> The room booking was cancelled</b>
         @else
           <b><i class="material-icons">lock_open</i> The room and timing is open!</b>
         @endif
       </div>
     </div>
   </div>
 </div>

 @if ($booking->recursion_count > 1)
 <div class="row">
   <ul class="collapsible popout" data-collapsible="accordion">
     <li>
       <div class="collapsible-header active"><i class="material-icons">replay</i>Recurring Options</div>
       <div class="collapsible-body">
         <div class="row">
           Happening {{$booking->recursion_frequency}} on the following dates
           <ul class="collection">
           @foreach ($occurrences as $occurrence)
            <li class="collection-item">{{$occurrence->format('F d, Y @h:i:s A')}}</li>
           @endforeach
           <ul>
         </div>
       </div>
     </li>
   </ul>
 </div>
 @endif
</div>
@stop
