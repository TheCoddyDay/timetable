@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit {{ ucfirst($day) }} Record</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('timetable.update', [$day, $record->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="teacher_name" class="form-label">Teacher Name *</label>
                            <input type="text" class="form-control @error('teacher_name') is-invalid @enderror" 
                                   id="teacher_name" name="teacher_name" value="{{ old('teacher_name', $record->teacher_name) }}" required>
                            @error('teacher_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="subject" class="form-label">Subject *</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" value="{{ old('subject', $record->subject) }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="class_room" class="form-label">Class Room</label>
                            <input type="text" class="form-control @error('class_room') is-invalid @enderror" 
                                   id="class_room" name="class_room" value="{{ old('class_room', $record->class_room) }}">
                            @error('class_room')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="class_name" class="form-label">Class Name</label>
                            <input type="text" class="form-control @error('class_name') is-invalid @enderror" 
                                   id="class_name" name="class_name" value="{{ old('class_name', $record->class_name) }}">
                            @error('class_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_time" class="form-label">Start Time *</label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" name="start_time" value="{{ old('start_time', $record->start_time) }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_time" class="form-label">End Time *</label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" name="end_time" value="{{ old('end_time', $record->end_time) }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('timetable.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                        <button type="submit" class="btn btn-primary">Update Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
