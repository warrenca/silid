<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Silid Room Booking Confirmation</title>
  </head>
  <body>
    <p>Hi there!</p>
    <p>You must confirm the booking you just made to lock in the room and timings.</p>
    <p>
      <a href="{{$confirmation_link}}">Click this link now to confirm.</a> or open the link below<br/>
      <a href="{{$confirmation_link}}">{{$confirmation_link}}</a>
    </p>
    <p>
      <b>Room Booking Details</b>
      <ul>
        <li>Room Name: {{$booking->room_id}}</li>
        <li>Start Date and Time: {{$booking->start}}</li>
        <li>End Date and Time: {{$booking->end}}</li>
      </ul>
    </p>
    <p>Thank you for booking!</p>
    <p>-- Silid Admin</p>
  </body>
</html>
