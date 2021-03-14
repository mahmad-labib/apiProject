<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    public function articles()
    {

        return $this->belongsToMany(Article::class, 'sections_articles');
    }

    public function users()
    {

        return $this->belongsToMany(User::class, 'users_sections');
    }
    //each category might have one parent
    public function parent()
    {
        return $this->belongsTo(Section::class, 'parent_id');
    }

    public function getParents()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while (!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents;
    }

    //each category might have multiple children
    public function children()
    {
        return $this->hasMany(Section::class, 'parent_id')->orderBy('name', 'asc');
    }
    protected $fillable = ['name', 'parent_id'];
}
