<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DiscountCoupon
 *
 * @property $id
 * @property $code
 * @property $type
 * @property $dicount
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class DiscountCoupon extends Model
{
    
    static $rules = [
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['code','type','dicount','status'];

    /**
     * Find an active coupon by code (case-insensitive, trimmed).
     */
    public static function findByCode(?string $code): ?self
    {
        $code = trim((string) $code);
        if ($code === '') {
            return null;
        }

        return static::query()
            ->whereRaw('UPPER(code) = ?', [strtoupper($code)])
            ->where('status', 1)
            ->first();
    }

}
