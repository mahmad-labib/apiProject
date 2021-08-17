<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    protected $fillable = [
        'title', 'content'
    ];
    public function creator()
    {

        return $this->belongsToMany(User::class, 'users_articles')->select(['id', 'name', 'speciality']);
    }

    public function section()
    {

        return $this->belongsToMany(Section::class, 'sections_articles');
    }

    public function images()
    {

        return $this->belongsToMany(Image::class, 'articles_images');
    }
}
