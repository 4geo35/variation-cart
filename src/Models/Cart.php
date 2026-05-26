<?php

namespace GIS\VariationCart\Models;

use App\Models\User;
use GIS\ProductVariation\Interfaces\ProductVariationInterface;
use GIS\ProductVariation\Models\ProductVariation;
use GIS\VariationCart\Interfaces\CartInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model implements CartInterface
{
    protected $keyType = "string";
    public $incrementing = false;
    protected $fillable = [
        "total", "notify_at", "user_id"
    ];

    public function variations(): BelongsToMany
    {
        $variationModelClass = config("product-variation.customVariationModel") ?? ProductVariation::class;
        return $this->belongsToMany($variationModelClass)
            ->withPivot("quantity")
            ->withTimestamps();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function getHumanTotalAttribute(): string
    {
        if ($this->total - intval($this->total) > 0) {
            return number_format($this->total, 2, ",", " ");
        } else {
            return number_format($this->total, 0, ",", " ");
        }
    }

    public function getCountAttribute(): int
    {
        $count = 0;
        foreach ($this->variations as $variation) {
            /**
             * @var ProductVariationInterface $variation
             */
            $pivot = $variation->pivot;
            $count += $pivot->quantity;
        }
        return $count;
    }

    public function getCheckUpdatedAttribute(): string
    {
        $updated = $this->updated_at;
        $tz = date_helper()->changeTz($updated);
        return date_helper()->format($tz, "Y-m-d H:i:s");
    }

    public function getSaleLessAttribute(): float
    {
        $total = 0;
        foreach ($this->variations as $variation) {
            $pivot = $variation->pivot;
            $quantity = $pivot->quantity;
            if ($variation->sale) {
                $total += $variation->old_price * $quantity;
            } else {
                $total += $variation->price * $quantity;
            }
        }
        return $total;
    }

    public function getHumanSaleLessAttribute(): string
    {
        $total = $this->sale_less;
        if ($total - intval($total) > 0) {
            return number_format($total, 2, ",", " ");
        } else {
            return number_format($total, 0, ",", " ");
        }
    }

    public function getDiscountAttribute(): float
    {
        $total = 0;
        foreach ($this->variations as $variation) {
            $pivot = $variation->pivot;
            $quantity = $pivot->quantity;
            $total += $variation->discount * $quantity;
        }
        return $total;
    }

    public function getHumanDiscountAttribute(): string
    {
        $total = $this->discount;
        if ($total - intval($total) > 0) {
            return number_format($total, 2, ",", " ");
        } else {
            return number_format($total, 0, ",", " ");
        }
    }
}
