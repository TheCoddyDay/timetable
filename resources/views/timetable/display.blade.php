@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Weekly Timetable</h1>
        <div>
            <a href="{{ route('timetable.index') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-home me-1"></i>Today's Schedule
            </a>
            <a href="{{ route('timetable.dashboard') }}" class="btn btn-outline-warning">
                <i class="fas fa-tachometer-alt me-1"></i>Admin Dashboard
            </a>
        </div>
    </div>

    @foreach ($data as $day => $records)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong>{{ ucfirst($day) }}</strong>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Subject</th>
                            <th>Room</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Class</th>
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
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No records yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endsection
