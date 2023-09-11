<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Book::factory(33)->create()->each(function ($book) {
            $numOfReviews = random_int(5, 30);
            Review::factory($numOfReviews)->for($book)->create();
        });

        Book::factory(33)->create()->each(function ($book) {
            $numOfReviews = random_int(5, 30);
            Review::factory($numOfReviews)->good()->for($book)->create();
        });

        Book::factory(33)->create()->each(function ($book) {
            $numOfReviews = random_int(5, 30);
            Review::factory($numOfReviews)->avg()->for($book)->create();
        });

        Book::factory(33)->create()->each(function ($book) {
            $numOfReviews = random_int(5, 30);
            Review::factory($numOfReviews)->bad()->for($book)->create();
        });
    }
}
