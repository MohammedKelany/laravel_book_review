<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $builder, string $title)
    {
        return $builder->where("title", "LIKE", "%$title%");
    }
    public function scopeWithPopular(Builder $builder, $from = null, $to = null)
    {
        return $builder->withCount([
            "reviews" => fn () => $this->dateRangeSpecifier($builder, $from, $to)
        ]);
    }
    public function scopeMostPopular(Builder $builder, $from = null, $to = null)
    {
        return $builder->withPopular($from, $to)->orderBy("reviews_count", "desc");
    }
    public function scopeWithRated(Builder $builder, $from = null, $to = null)
    {
        return $builder->withAvg([
            "reviews" => fn () => $this->dateRangeSpecifier($builder, $from, $to)
        ], "rating");
    }
    public function scopeHighestRated(Builder $builder, $from = null, $to = null)
    {
        return $builder->withRated($from, $to)->orderBy("reviews_avg_rating", "desc");
    }

    public function scopeMinReviews(Builder $builder, int $minReviews)
    {
        return $builder->having("reviews_count", ">=", $minReviews);
    }
    public function scopeHighestRatedLastMonth(Builder $builder)
    {
        return $builder
            ->highestRated(now()->subMonth(), now())
            ->mostPopular(now()->subMonth(), now())
            ->minReviews(5);
    }

    public function scopeHighestRatedLast6Months(Builder $builder)
    {
        return $builder
            ->highestRated(now()->subMonths(6), now())
            ->mostPopular(now()->subMonths(6), now())
            ->minReviews(20);
    }
    public function scopeMostPopularLastMonth(Builder $builder)
    {
        return $builder
            ->mostPopular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(5);
    }

    public function scopeMostPopularLast6Months(Builder $builder)
    {
        return $builder
            ->mostPopular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(20);
    }
    private  function dateRangeSpecifier(Builder $builder, $from = null, $to = null)
    {
        if ($from && !$to) {
            $builder->where("created_at", ">=", $from);
        } elseif (!$from && $to) {
            $builder->where("created_at", "<=", $to);
        } elseif ($from && $to) {
            $builder->whereBetween("created_at", [$from, $to]);
        }
    }
}