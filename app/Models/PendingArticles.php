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
}
