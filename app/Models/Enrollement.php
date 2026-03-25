<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollement extends Model
{
    protected $guarded = [];

      public function getRouteKeyName(): string
    {
        return 'key';
    }

    public function user(){
      return $this->belongsTo(User::class);
    }

    public function city(){
      return $this->belongsTo(City::class);
    }
    public function commune(){
      return $this->belongsTo(Commune::class);
    }

    public function nationality(){
      return $this->belongsTo(Country::class, 'nationality', 'id');
    }
}
