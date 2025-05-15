<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;
    //какие поля доступны чтобы их можно было изменять данные
    protected $fillable=['name','phone','email','status_id'];

    public function status(){
        return $this->belongsTo(Status::class,'status_id');
    }
}
