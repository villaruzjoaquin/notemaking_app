<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $guarded = []; // Add guarded variables

    public function getRouteKeyName(){
        return 'uuid'; // Returns the uuid everytime a note is clicked from the index
    }

    public function user(){
        return $this->belongsTo(User::class); // Spit out the user that the note belongs to
    }
}
