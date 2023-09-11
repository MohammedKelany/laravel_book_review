@extends('layouts.app')

@section('content')
    <h1 class="mb-10 text-2xl">books</h1>
    <form action="{{ route('books.index', ['books' => $books]) }}" method="GET" class="flex gap-2 mb-10">
        <input type="text" name="title" value="{{ request('title') }}" class="input" placeholder="Search by title">
        <input type="hidden" name="filter" value="{{ request('filter') }}">
        <input type="submit" value="Search" class="btn">
        <a href="{{ route('books.index') }}" class="btn">Clear</a>
    </form>

    <div class="filter-container flex mb-4">
        @foreach ($filters as $key => $filter)
            <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}"
                class="{{ request('filter') === $key || (request('filter') == null && $key === '') ? 'filter-item-active' : 'filter-item' }}">{{ $filter }}</a>
        @endforeach
    </div>

    <ul>
        @forelse ($books as $book)
            <li class="mb-4">
                <div class="book-item">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-grow sm:w-auto">
                            <a href="{{ route('books.show', ['book' => $book]) }}"
                                class="book-title">{{ $book->title }}</a>
                            <span class="book-author">by {{ $book->author }}</span>
                        </div>
                        <div>
                            <div class="book-rating">
                                {{ $book->rating }}
                            </div>
                            <div class="book-review-count">
                                <x-star-rating :rating="$book->reviews_avg_rating" />
                                {{ round($book->reviews_avg_rating, 1) }}
                                <br>out of
                                {{ $book->reviews_count }}
                                {{ Str::plural('review', $book->reviews_count) }}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li class="mb-4">
                <div class="empty-book-item">
                    <p class="empty-text">No books found</p>
                    <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
                </div>
            </li>
        @endforelse
    </ul>
@endsection
