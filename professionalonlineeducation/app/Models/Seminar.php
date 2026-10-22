<?php

namespace App\Models;

use App\Http\Helpers\General\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Seminar
 *
 * @property $id
 * @property $seminar_id
 * @property $max_students
 * @property $course_title
 * @property $class_full_message
 * @property $start_date
 * @property $end_date
 * @property $start_time
 * @property $end_time
 * @property $location
 * @property $address
 * @property $city
 * @property $state
 * @property $zip_code
 * @property $ceus
 * @property $cost
 * @property $description
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Seminar extends Model
{
    
    static $rules = [
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['seminar_id','hours','max_students','course_title','class_full_message','start_date','end_date','start_time','end_time','location','address','city','state','zip_code','ceus','cost','description','status','zoom_link','zoom_meeting_id','zoom_password','is_online'];

    public function enrolledUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\User::class, 'seminar_users', 'seminar_id', 'user_id')
                    ->withPivot('id', 'completed_at', 'enrolled_at', 'price')
                    ->withTimestamps();
    }

    /**
     * Whether `cost` contains a usable numeric amount for public pricing UI.
     */
    public function hasDisplayableUsdPrice(): bool
    {
        $raw = trim((string) ($this->cost ?? ''));
        if ($raw === '') {
            return false;
        }
        $numeric = preg_replace('/[^0-9.\-]/', '', $raw);

        return $numeric !== '' && is_numeric($numeric);
    }

    /**
     * Plain USD decimal string (no thousands separators) for cart math, data-usd, and float casts.
     * Legacy: missing or non-numeric cost defaults to 20.00 to match older seminar checkout copy.
     */
    public function getPriceForDisplayAttribute(): string
    {
        if (! $this->hasDisplayableUsdPrice()) {
            return '20.00';
        }
        $numeric = preg_replace('/[^0-9.\-]/', '', trim((string) $this->cost));

        return Helper::usdDataAmount($numeric);
    }

    /**
     * One-line label for client/admin payment history when course_title may be empty.
     */
    public function paymentHistoryDescription(): string
    {
        $dateLabel = '';
        if (! empty($this->start_date)) {
            try {
                $dateLabel = Carbon::parse($this->start_date)->format('M j, Y');
            } catch (\Throwable $e) {
                $dateLabel = trim((string) $this->start_date);
            }
        }
        if ($dateLabel !== '') {
            return 'LIVE seminar — '.$dateLabel;
        }
        $title = trim((string) ($this->course_title ?? ''));
        if ($title !== '') {
            return 'LIVE seminar — '.$title;
        }

        return 'LIVE seminar';
    }

}
