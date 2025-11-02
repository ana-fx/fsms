<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FoodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'food_category_id',
        'food_item_id',
        'title',
        'description',
        'quantity',
        'unit',
        'notes',
        'recipient_name',
        'recipient_phone',
        'delivery_address',
        'city',
        'postal_code',
        'delivery_notes',
        'payment_proof',
        'payment_proof_uploaded_at',
        'status',
        'requested_date',
        'needed_date',
        'shipped_by',
        'shipped_at',
        'delivery_photo',
        'delivery_photo_uploaded_at',
        'delivered_at',
        'admin_notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'requested_date' => 'date',
        'needed_date' => 'date',
        'shipped_at' => 'datetime',
        'delivery_photo_uploaded_at' => 'datetime',
        'delivered_at' => 'datetime',
        'payment_proof_uploaded_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the request.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the food category for this request.
     */
    public function foodCategory(): BelongsTo
    {
        return $this->belongsTo(FoodCategory::class);
    }

    /**
     * Get the food item (product) for this request.
     */
    public function foodItem(): BelongsTo
    {
        return $this->belongsTo(FoodItem::class);
    }

    /**
     * Get the supplier who shipped this request.
     */
    public function shippedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shipped_by');
    }

    /**
     * Scope a query to only include requests by a specific customer.
     */
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope a query to only include requests with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include paid requests (payment completed).
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope a query to only include payment pending requests.
     */
    public function scopePaymentPending($query)
    {
        return $query->where('status', 'payment_pending');
    }

    /**
     * Boot method to generate order number before creating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($foodRequest) {
            if (empty($foodRequest->order_number)) {
                $foodRequest->order_number = static::generateOrderNumber();
            }
        });
    }

    /**
     * Generate unique order number format: FSMS-YYYYMMDD-XXXXX
     */
    protected static function generateOrderNumber(): string
    {
        $prefix = 'FSMS-' . now()->format('Ymd') . '-';
        
        // Get last order number with same prefix today
        $lastOrder = static::where('order_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            // Extract the sequence number and increment
            $sequence = intval(substr($lastOrder->order_number, -5));
            $sequence++;
        } else {
            // First order of the day
            $sequence = 1;
        }

        return $prefix . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get the route key for the model.
     * Allow access by order_number instead of id.
     */
    public function getRouteKeyName()
    {
        return 'order_number';
    }

    /**
     * Get the order number for display.
     */
    public function getOrderNumberAttribute($value)
    {
        return $value;
    }
}
