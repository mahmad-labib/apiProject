<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    
    public function article() {
        return $this->belongsToMany(Article::class, 'articles_images');
    }

    public function pending_article() {
        return $this->belongsToMany(PendingArticles::class, 'pending_articles_images');
    }
}
