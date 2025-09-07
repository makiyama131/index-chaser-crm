<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'from_status_id',
        'to_status_id',
    ];

    // Define relationships to get more info later
    public function customer() { return $this->belongsTo(Customer::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function fromStatus() { return $this->belongsTo(Status::class, 'from_status_id'); }
    public function toStatus() { return $this->belongsTo(Status::class, 'to_status_id'); }
}