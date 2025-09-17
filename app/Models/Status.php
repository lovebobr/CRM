<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $fillable=['name', 'is_default'];

    public function leads(){
        return $this->hasMany(Lead::class,'status_id');
    }

    public static function getDefaultStatus()
    {
        return self::where('is_default', true)->firstOrFail();
    }
}
