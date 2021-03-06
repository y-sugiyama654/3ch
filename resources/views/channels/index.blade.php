@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Channels</div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <th>Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </thead>
                <tbody>
                    @foreach($channels as $channel)
                        <tr>
                            <td>{{ $channel->name }}</td>
                            <td><a href="{{ route('channels.edit', ['channel' => $channel->id]) }}" class="btn btn-xs btn-outline-secondary">Edit</a></td>
                            <td>
                                <form action="{{ route('channels.destroy', ['channel' => $channel->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
