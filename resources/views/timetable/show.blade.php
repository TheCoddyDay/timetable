@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>View {{ ucfirst($day) }} Record</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Teacher Name:</strong>
                        <p class="form-control-plaintext">{{ $record->teacher_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Subject:</strong>
                        <p class="form-control-plaintext">{{ $record->subject }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Class Room:</strong>
                        <p class="form-control-plaintext">{{ $record->class_room ?? 'Not specified' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Class Name:</strong>
                        <p class="form-control-plaintext">{{ $record->class_name ?? 'Not specified' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Start Time:</strong>
                        <p class="form-control-plaintext">{{ $record->start_time }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>End Time:</strong>
                        <p class="form-control-plaintext">{{ $record->end_time }}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('timetable.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                    <div>
                        <a href="{{ route('timetable.edit', [$day, $record->id]) }}" class="btn btn-primary me-2">Edit Record</a>
                        <form action="{{ route('timetable.destroy', [$day, $record->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete Record</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
