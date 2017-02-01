<?php

return [
  'duration' => [
    '1800' => '30 mins',
    '3600' => '1 hr',
    '5400' => '1.5 hr',
    '7200' => '2 hr',
    '9000' => '2.5 hr',
    '10800' => '3 hr',
    '12600' => '3.5 hr',
    '14400' => 'Half day (4hrs)',
    '86400' => 'Full day'
  ],

  /* These hash length values are constant. You should not change
   * this if you already have users who made bookings. The link on
   * the email will not work if the value gets changed.
   */
  'hashes' => [
    'VIEW_HASH_LENGTH' => 9,
    'CONFIRMATION_HASH_LENGTH' => 5
  ]
];
