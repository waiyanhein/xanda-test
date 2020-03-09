@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form>
                        <div class="row">
                            <div class="col-md-3">
                                <input placeholder="name" type="text" name="name">
                            </div>
                            <div class="col-md-3">
                                <select name="class">
                                    <option value="">None</option>
                                    @foreach (\App\Models\Spacecraft::CLASSES as $class)
                                        <option value="{{ $class }}">{{ $class }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status">
                                    <option value="">None</option>
                                    <option value="{{ \App\Models\Spacecraft::STATUS_OPERATIONAL }}">Operational</option>
                                    <option value="{{ \App\Models\Spacecraft::STATUS_DAMAGED }}">Damaged</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit">Filter</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('spacecrafts.create') }}">Create</a>
                            </div>
                        </div>
                    </form>

                    <hr />

                    @if (count($spacecrafts) > 0)
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <td>ID</td>
                                <td>Image</td>
                                <td>Name</td>
                                <td>Class</td>
                                <td>Status</td>
                                <td>Crew</td>
                                <td>Value</td>
                                <td>Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($spacecrafts as $spacecraft)
                                <tr>
                                    <td>{{ $spacecraft->id }}</td>
                                    <td>
                                        <img class="list-image" src="{{ \Illuminate\Support\Facades\Storage::url($spacecraft->image) }}">
                                    </td>
                                    <td>{{ $spacecraft->name }}</td>
                                    <td>{{ $spacecraft->class }}</td>
                                    <td>{{ $spacecraft->status_label }}</td>
                                    <td>{{ $spacecraft->pretty_crew }}</td>
                                    <td>{{ $spacecraft->pretty_value }}</td>
                                    <td class="action-td">
                                        <a href="{{ route('spacecrafts.show', $spacecraft) }}">View</a>
                                        |
                                        <a href="{{ route('spacecrafts.edit', $spacecraft) }}">Edit</a>
                                        |
                                        <a href="#" data-url="{{ route('spacecrafts.destroy', $spacecraft->id) }}" data-id="{{ $spacecraft->id }}" class="btn-delete">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning">
                            <strong>No spacecraft found</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
