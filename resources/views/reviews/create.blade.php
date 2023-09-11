@extends('layouts.app')

@section('content')
    <h1 class="mb-10 text-2xl">Add Review for {{ $book->title }}</h1>
    <form action="{{ route('books.reviews.store', ['book' => $book]) }}" method="POST">
        @csrf
        <label for="review">Review</label>
        <textarea class="input mb-4" required name="review" id="review"></textarea>
        <label for="rating">Rating</label>
        <select name="rating" id="rating" class="input mb-4" required>
            <option value="">Select a rating</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <input type="submit" value="Create!">
    </form>
@endsection
