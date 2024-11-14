<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeUnit\FunctionUnit;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}
