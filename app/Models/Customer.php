<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $casts = [
        'birth_date' => 'date',
        'friend_added_date' => 'date',
        'last_reaction_date' => 'date',
    ];

    /**
     * この顧客を担当する担当者を取得 (多対一)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * この顧客に紐づくタスク一覧を取得 (一対多)
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * この顧客に紐づくタグ一覧を取得 (多対多)
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'customer_tag');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
