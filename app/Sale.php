<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public function saleDetails()
    {
        return $this->has_many(saleDetail::class);
    }
}