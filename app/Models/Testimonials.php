<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Testimonials extends Model
{
    use HasFactory,HasTranslations;
    public $translatable = ['title','description','subtitle'];

    protected $fillable = ['post_type', 'title', 'subtitle', 'description', 'image'];

    protected $table = 'posts';
}
