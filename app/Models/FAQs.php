<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQs extends Model
{
    protected $fillable = [
        'title',
        'category',
        'Titre',
        'slug',
        'description',
        'video_url',
        'type',
        'author',
        'visible',
        'tag',
        'row',
    ];

    public function isVisible():bool{
        return $this->visible == 'Oui';
    }
}
