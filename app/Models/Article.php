<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    protected $fillable = [
        'title', 'content', 'creator_id', 'section_id', 'state'
    ];
    public function creator()
    {

        return $this->belongsToMany(User::class, 'users_articles');
    }

    public function section()
    {

        return $this->belongsToMany(Section::class, 'sections_articles');
    }
}
