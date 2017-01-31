@extends('layouts.master')
@section('content')
<div class="col s12 m7">
  <h4 class="header">Hi {{$booking->reserved_by}}!</h4>
  <div class="card horizontal">
    <div class="card-image">
      <img src="/images/room-{{$booking->room->id}}.jpg">
    </div>
    <div class="card-stacked">
      <div class="card-content">
        <ul>
            <li>Room name: <b>{{$booking->room->name}}</b></li>
            <li>Room description: <b>{{$booking->room->description}}</b></li>
            <li>Start: <b>{{date('F d, Y @h:i A', strtotime($booking->start))}}</b></li>
            <li>End: <b>{{date('F d, Y @h:i A', strtotime($booking->end))}}</b></li>
            @if ($booking->confirmed)
            <li><br/>If you want to cancel this booking, click < here >.</li>
            @else
            <li><br/><i>This booking is not yet confirmed. To lock the room, immediately look for the following subject on your email:<br/>"Reference Code: {{$confirmation_id}}"</i></li>
            @endif
        </ul>
      </div>
      <div class="card-action
        @if ($booking->confirmed)
          green lighten-2
        @else
          red lighten-3
        @endif
        ">
        @if ($booking->confirmed)
          <b><i class="material-icons">lock</i> The is room locked and confirmed!</b>
        @else
          <b><i class="material-icons">lock_open</i> The room and timing is open!</b>
        @endif
      </div>
    </div>
  </div>
</div>
@stop
