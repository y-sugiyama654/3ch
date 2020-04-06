@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Update a Discussion</div>
        <div class="card-body">
            <form action="{{ route('discussions.update', $discussion->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-froup">
                    <label for="content">Content</label>
                    <input id="content" type="hidden" name="content" value="{{ $discussion->content }}">
                    <trix-editor input="content"></trix-editor>
                </div>
                <button type="submit" class="btn btn-success mt-3">Save Discussion Changes</button>
            </form>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.1/trix.css">
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.1/trix.js"></script>
@endsection
