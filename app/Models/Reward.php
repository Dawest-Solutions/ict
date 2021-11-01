<?php

namespace App\Models;

use Carbon\Carbon;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Reward
 *
 * @property int $id
 * @property string $name
 * @property string $image_path
 * @property string $location
 * @property int $value
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Draw[] $draws
 * @property-read int|null $draws_count
 * @method static \Illuminate\Database\Eloquent\Builder|Reward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reward query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereValue($value)
 * @mixin \Eloquent
 */
class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'service', 
        'image_path', 
        'location', 
        'description',
    ];

    /**
     * @return HasMany
     */
    public function draws(): HasMany
    {
        return $this->hasMany(Draw::class);
    }

    /**
     * @return HasMany
     */
    public function rewardInDays(): HasMany
    {
      return $this->hasMany(RewardInDay::class);
    }

    /**
     * Value of the prize for the catalog
     *
     * @param Carbon $date
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function priceForCatalog(Carbon $date): int
    {
        return $this->priceForDate($date, 'catalog');
    }

    /**
     * Value of the prize for the purposes of the draw
     *
     * @param Carbon $date
     * @return int
     */
    public function priceForDraw(Carbon $date): int
    {
        return $this->priceForDate($date, 'draw');
    }

    /**
     * Get the value of the prize
     *
     * @param Carbon $date
     * @param $type
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|int
     */
    protected function priceForDate(Carbon $date, $type): ?int
    {
        $draw = $this
            ->draws()
            ->when($type == 'draw', function ($query) use ($date) {
                $query->where('date_begin', '<=', $date)
                    ->where('date_end', '>=', $date);
        })
            ->when($type == 'catalog', function ($query) use ($date) {
                $date->addDay();
                $query->where('date_begin', '<=', $date)
                    ->where('date_end', '>=', $date);
        })
            ->orderBy('date_draw')
            ->first();

        if ($draw) {
            return $draw->value;
        }

        throw new InvalidArgumentException('Wartość nagrody ' . $this->name . ' nie została poprawnie określona');
    }

}
