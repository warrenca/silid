<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rooms';

    /**
     * Get the comments for the blog post.
     */
    public function bookings()
    {
        return $this->hasMany('App\Booking');
    }
}
