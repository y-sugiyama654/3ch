@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Edit channel  {{ $channel->name }}</div>
        <div class="card-body">
            <form action="{{ route('channels.update', ['channel' => $channel->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <input type="text" value="{{ $channel->name }}" name="channel" class="form-control">
                </div>
                <div class="form-group">
                    <div class="text-center">
                        <button class="btn btn-success" type="submit">
                            Update channel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
