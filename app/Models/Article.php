<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function creator()
    {

        return $this->belongsToMany(User::class, 'users_articles');
    }

    public function section()
    {

        return $this->belongsToMany(Section::class, 'sections_articles');
    }
}
