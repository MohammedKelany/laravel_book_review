<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [
            "" => "latest",
            "popular_last_month" => "Popular Last Month",
            "popular_last_6_months" => "Popular Last 6 Months",
            "highest_rated_last_month" => "Highest Rated Last Month",
            "highest_rated_last_6_month" => "Highest Rated Last 6 Months",
        ];
        $title = $request->input("title");
        $filter = $request->input("filter", '');
        $books = Book::when($title, function ($query, $title) {
            return $query->title($title);
        })->withCount("reviews")->withAvg("reviews", "rating");

        $books = match ($filter) {
            "popular_last_month" => $books->mostPopularLastMonth(),
            "popular_last_6_months" => $books->mostPopularLast6Months(),
            "highest_rated_last_month" => $books->highestRatedLastMonth(),
            "highest_rated_last_6_month" => $books->highestRatedLast6Months(),
            default => $books->latest(),
        };
        $cacheKey = "book:$title,filter:$filter";
        $books = cache()->remember($cacheKey, 3600, function () use ($books) {
            // dd("this is not cached");
            return $books->get();
        });
        return view("books.index", ["books" => $books, "filters" => $filters]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bookKey = "book:$id";
        $book = cache()->remember($bookKey, 3600, fn () => Book::with([
            "reviews" => fn ($query) => $query->latest(),
        ])->withPopular()->withRated()->findOrFail($id));
        return view(
            "books.show",
            ["book" => $book,]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}