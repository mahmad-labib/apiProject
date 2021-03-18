<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingArticles extends Model
{
    protected $table = 'pending_articles';
    protected $fillable = [
        'title', 'content', 'creator_id', 'section_id'
    ];

    public function images() {
       return  $this->belongsToMany(Image::class, 'pending_articles_images');
    }

    // public static function boot() {
    //     parent::boot();
    //     self::deleting(function($articles) { // before delete() method call this
    //          $articles->images()->each(function($image) {
    //              dd($image->);
    //             $image->delete(); // <-- direct deletion
    //          });
    //          // do the rest of the cleanup...
    //     });
    // }
}
