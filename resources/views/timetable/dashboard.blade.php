@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin Dashboard</h1>
        <div>
            <a href="{{ route('timetable.index') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-home me-1"></i>Today's Schedule
            </a>
            <a href="{{ route('timetable.display') }}" class="btn btn-outline-info">
                <i class="fas fa-calendar-week me-1"></i>Weekly View
            </a>
        </div>
    </div>

    @foreach ($data as $day => $records)
        <div class="card mb-4">
            <div class="card-header"><strong>{{ ucfirst($day) }}</strong></div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Subject</th>
                            <th>Room</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Class</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                <td>{{ $record->teacher_name }}</td>
                                <td>{{ $record->subject }}</td>
                                <td>{{ $record->class_room }}</td>
                                <td>{{ $record->start_time }}</td>
                                <td>{{ $record->end_time }}</td>
                                <td>{{ $record->class_name }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('timetable.show', [$day, $record->id]) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('timetable.edit', [$day, $record->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('timetable.destroy', [$day, $record->id]) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No records yet</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <form action="{{ route('timetable.store', $day) }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col"><input name="teacher_name" class="form-control" placeholder="Teacher"></div>
                    <div class="col"><input name="subject" class="form-control" placeholder="Subject"></div>
                    <div class="col"><input name="class_room" class="form-control" placeholder="Room"></div>
                    <div class="col"><input name="start_time" type="time" class="form-control"></div>
                    <div class="col"><input name="end_time" type="time" class="form-control"></div>
                    <div class="col"><input name="class_name" class="form-control" placeholder="Class"></div>
                    <div class="col"><button class="btn btn-primary">Add</button></div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
