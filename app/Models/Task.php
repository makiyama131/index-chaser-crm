<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'customer_id',
        'user_id',
    ];
    protected $casts = [
        'due_date' => 'datetime',
    ];

    protected $touches = ['customer'];


    /**
     * Get the customer that this task belongs to.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user (agent) that this task is assigned to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function completionActivity()
    {
        return $this->hasOne(Activity::class)->where('type', 'タスク完了');
    }
}
