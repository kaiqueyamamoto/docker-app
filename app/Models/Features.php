<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Features extends Model
{
    use HasFactory,HasTranslations;
    public $translatable = ['title','description','subtitle'];

    protected $fillable = ['post_type', 'title', 'description', 'image'];

    protected $table = 'posts';
}
