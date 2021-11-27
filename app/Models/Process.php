<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Process extends Model
{
    use HasFactory,HasTranslations;
    public $translatable = ['title','description','link_name','subtitle'];

    protected $fillable = ['post_type', 'title', 'description', 'link', 'link_name'];

    protected $table = 'posts';
}
