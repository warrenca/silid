<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Silid Room Booking Confirmation</title>
  </head>
  <body>
    <p>Hi there!</p>
    <p>You must confirm the booking you just made to lock in the room and the timings.</p>
    <p>
      <a href="{{$confirmation_link}}">Click this link now to confirm.</a> or open the link below<br/>
      <a href="{{$confirmation_link}}">{{$confirmation_link}}</a><br/>
      <i>If you don't want to continue this booking or you didn't do this booking, just disregard this email</i>
    </p>
    <p>
      <b>Room Booking Details</b>
      <ul>
        <li>Purpose: <b>{{$purpose}}</b></li>
        <li>Room Name: <b>{{$booking_room_name}}</b></li>
        <li>Room Description: <b>{{$booking_room_description}}</b></li>
        <li>Start Date and Time: <b>{{$booking_start}}</b></li>
        <li>End Date and Time: <b>{{$booking_end}}</b></li>
      </ul>
    </p>
    <p>Thank you for booking!</p>
    <p>-- Silid Admin</p>
  </body>
</html>
