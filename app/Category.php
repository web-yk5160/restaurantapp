<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function menus()
    {
        return $this->has_many(Menu::class);
    }
}