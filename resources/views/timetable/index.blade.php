@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Today's Schedule ({{ $today }})</h1>
    <div>
        <a href="{{ route('timetable.dashboard') }}" class="btn btn-outline-primary me-2">
            <i class="fas fa-tachometer-alt me-1"></i>Admin Dashboard
        </a>
        <a href="{{ route('timetable.display') }}" class="btn btn-outline-info">
            <i class="fas fa-calendar-week me-1"></i>Weekly View
        </a>
    </div>
</div>

<div class="row">
    <!-- Current Class Table -->
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white">
                <strong>Current Class</strong>
            </div>
            <div class="card-body">
                <table class="table table-striped table-sm" id="current-class">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Subject</th>
                            <th>Room</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($currentClasses as $class)
                        <tr>
                            <td>{{ $class->teacher_name }}</td>
                            <td>{{ $class->subject }}</td>
                            <td>{{ $class->class_room }}</td>
                            <td>{{ $class->start_time }} - {{ $class->end_time }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No class is currently running</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- Next Class Table -->
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white">
                <strong>Next Class</strong>
            </div>
            <div class="card-body">
                <table class="table table-striped table-sm" id="next-classes">
                    <thead>
                        <tr>
                            <!-- <th>After Class</th> -->
                            <th>Teacher</th>
                            <th>Subject</th>
                            <th>Room</th>
                            <th>Start</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($nextClasses as $pair)
                        @foreach ($pair['next'] as $next)
                        <tr>
                            <!-- <td> -->
                                <!-- Show all current classes that end at this time -->
                                <!-- @foreach ($pair['currents'] as $c) -->
                                <!-- <div>{{ $c->subject }} ({{ $c->class_room }})</div> -->
                                <!-- @endforeach -->
                            <!-- </td> -->
                            <td>{{ $next->teacher_name }}</td>
                            <td>{{ $next->subject }}</td>
                            <td>{{ $next->class_room }}</td>
                            <td>{{ $next->start_time }}</td>
                        </tr>
                        @endforeach
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No next class found</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>



            </div>
        </div>
    </div>
</div>

<script>
    async function refreshSchedule() {
        const response = await fetch("{{ route('timetable.index') }}", {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        });
        const data = await response.json();

        // Update Current Class Table
        const currentBody = document.querySelector('#current-class tbody');
        if (data.currentClasses.length > 0) {
            currentBody.innerHTML = data.currentClasses.map(c => `
        <tr>
            <td>${c.teacher_name}</td>
            <td>${c.subject}</td>
            <td>${c.class_room}</td>
            <td>${c.start_time} - ${c.end_time}</td>
        </tr>`).join('');
        } else {
            currentBody.innerHTML = `<tr><td colspan="4" class="text-center">No class is currently running</td></tr>`;
        }


        // Update Next Class Table
        const nextBody = document.querySelector('#next-classes tbody');
        if (data.nextClasses.length > 0) {
            nextBody.innerHTML = data.nextClasses.map(pair =>
                pair.next.map(next => `
            <tr>
                <td>${pair.current.subject} (ends ${pair.current.end_time})</td>
                <td>${next.teacher_name}</td>
                <td>${next.subject}</td>
                <td>${next.class_room}</td>
                <td>${next.start_time}</td>
            </tr>`).join('')
            ).join('');
        } else {
            nextBody.innerHTML = `<tr><td colspan="5" class="text-center">No next class found</td></tr>`;
        }


    }

    setInterval(refreshSchedule, 60000); // Refresh every minute
</script>
@endsection