<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // ▼▼▼ ADD THIS PROPERTY ▼▼▼
    protected $fillable = [
        'customer_id',
        'user_id',
        'type',
        'display_name',
        'file_path',
        'mime_type',
        'size',
        'memo',
    ];
    // ▲▲▲ END OF PROPERTY ▲▲▲

    /**
     * Get the customer that this document belongs to.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user who uploaded this document.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
