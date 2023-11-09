<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFavorite\Traits\Favoriteable;   
// ↑ お気に入りされるモデル（今回は商品のProduct）にuse Favoriteableとすることで、お気に入り機能を使えるようになる


class Product extends Model
{
    use HasFactory, Favoriteable; // ←お気に入りされるモデル（今回は商品のProduct）にuse Favoriteableとすることで、お気に入り機能を使えるようになる

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

     public function reviews()
     {
         return $this->hasMany('App\Models\Review');
     }
}
