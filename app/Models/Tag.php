<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // firstOrCreateメソッドで使うために必要
    protected $fillable = ['name'];

    /**
     * このタグを持つ顧客を取得する
     */
    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_tag');
    }
}
