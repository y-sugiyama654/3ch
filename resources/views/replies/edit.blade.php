@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.1/trix.css">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">Update a Reply</div>
        <div class="card-body">
            <form action="{{ route('reply.update', $reply->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-froup">
                    <label for="content">Content</label>
                    <input id="content" type="hidden" name="content" value="{{ $reply->content }}">
                    <trix-editor input="content"></trix-editor>
                </div>
                <button type="submit" class="btn btn-success mt-3">Save Reply Changes</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.1/trix.js"></script>
@endsection
