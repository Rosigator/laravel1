<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = ['twitter', 'bio', 'profession_id'];

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
}
