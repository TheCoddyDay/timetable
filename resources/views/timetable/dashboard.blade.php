@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <h1 class="mb-0">Admin Dashboard</h1>
        <div class="d-flex flex-column flex-sm-row gap-2">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input id="dashboardSearchInput" type="text" class="form-control" placeholder="Search teacher, subject, room, class">
            </div>
            <div class="btn-group">
                <a href="{{ route('timetable.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-home me-1"></i>Today's Schedule
            </a>
            <a href="{{ route('timetable.display') }}" class="btn btn-outline-info">
                <i class="fas fa-calendar-week me-1"></i>Weekly View
            </a>
            </div>
        </div>
    </div>

    <!-- Day Selector -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-calendar-day me-2"></i>Select Day</h5>
                    <div class="btn-group w-100" role="group" aria-label="Day selection">
    @foreach ($data as $day => $records)
                            <button type="button" class="btn btn-outline-primary day-selector {{ $loop->first ? 'active' : '' }}" 
                                    data-day="{{ $day }}" data-count="{{ count($records) }}">
                                {{ ucfirst($day) }}
                                <span class="badge bg-primary ms-1">{{ count($records) }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-plus-circle me-2"></i>Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addFormCollapse">
                            <i class="fas fa-plus me-1"></i>Add New Entry
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0" id="selectedDayTitle">Select a day to view records</h5>
            <span class="badge bg-secondary" id="recordCount">0 records</span>
        </div>
            <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0 dashboard-table" id="mainTable">
                    <thead>
                        <tr class="table-light" style="position: sticky; top: 0; z-index: 1;">
                            <th>Teacher</th>
                            <th>Subject</th>
                            <th>Room</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Class</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-calendar-day me-2"></i>Please select a day to view records
                                </td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Form Collapse -->
    <div class="collapse mt-4" id="addFormCollapse">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Entry</h5>
            </div>
            <div class="card-body">
                <form id="addForm" method="POST" class="row g-3">
                    @csrf
                    <div class="col-12 col-md-6">
                        <label class="form-label">Day</label>
                        <select name="day" class="form-select" required>
                            <option value="">Select Day</option>
                            @foreach ($data as $day => $records)
                                <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Teacher</label>
                        <input name="teacher_name" class="form-control" placeholder="Teacher Name" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Subject</label>
                        <input name="subject" class="form-control" placeholder="Subject" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Room</label>
                        <input name="class_room" class="form-control" placeholder="Room" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Start Time</label>
                        <input name="start_time" type="time" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">End Time</label>
                        <input name="end_time" type="time" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Class</label>
                        <input name="class_name" class="form-control" placeholder="Class" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Save Entry
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#addFormCollapse">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hidden data for JavaScript -->
    <script type="application/json" id="timetableData">
        {!! json_encode($data) !!}
    </script>
    <script>
        (function(){
            // Get timetable data
            const timetableData = JSON.parse(document.getElementById('timetableData').textContent);
            const daySelectors = document.querySelectorAll('.day-selector');
            const tableBody = document.getElementById('tableBody');
            const selectedDayTitle = document.getElementById('selectedDayTitle');
            const recordCount = document.getElementById('recordCount');
            const searchInput = document.getElementById('dashboardSearchInput');
            const addForm = document.getElementById('addForm');
            
            let currentDay = null;
            let currentData = [];

            // Day selector functionality
            daySelectors.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Update active state
                    daySelectors.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Get selected day
                    currentDay = this.dataset.day;
                    currentData = timetableData[currentDay] || [];
                    
                    // Update UI
                    selectedDayTitle.textContent = `${this.textContent.trim().replace(/\d+$/, '').trim()} Records`;
                    recordCount.textContent = `${currentData.length} records`;
                    
                    // Render table
                    renderTable();
                });
            });

            // Render table function
            function renderTable() {
                if (!currentData || currentData.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox me-2"></i>No records for this day
                            </td>
                        </tr>
                    `;
                    return;
                }

                tableBody.innerHTML = currentData.map(record => `
                    <tr>
                        <td class="searchable">${record.teacher_name}</td>
                        <td class="searchable">${record.subject}</td>
                        <td class="searchable"><span class="badge bg-secondary">${record.class_room}</span></td>
                        <td>${formatTime(record.start_time)}</td>
                        <td>${formatTime(record.end_time)}</td>
                        <td class="searchable"><span class="badge bg-info text-dark">${record.class_name}</span></td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <a href="/timetable/${currentDay}/${record.id}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                <a href="/timetable/${currentDay}/${record.id}/edit" class="btn btn-sm btn-outline-warning"><i class="fas fa-pen"></i></a>
                                <form action="/timetable/${currentDay}/${record.id}" method="POST" class="d-inline">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this record?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                `).join('');
            }

            // Format time function
            function formatTime(timeString) {
                const time = new Date('2000-01-01T' + timeString);
                return time.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
            }

            // Search functionality
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase().trim();
                    const rows = tableBody.querySelectorAll('tr');
                    
                    rows.forEach(row => {
                        if (row.querySelector('.searchable')) {
                            const searchableText = Array.from(row.querySelectorAll('.searchable'))
                                .map(td => td.textContent.toLowerCase())
                                .join(' ');
                            row.style.display = query === '' || searchableText.includes(query) ? '' : 'none';
                        }
                    });
                });
            }

            // Form submission
            if (addForm) {
                addForm.addEventListener('submit', function(e) {
                    const daySelect = this.querySelector('select[name="day"]');
                    if (daySelect.value) {
                        this.action = `/timetable/${daySelect.value}`;
                    } else {
                        e.preventDefault();
                        alert('Please select a day');
                    }
                });
            }

            // Auto-select first day on load
            if (daySelectors.length > 0) {
                daySelectors[0].click();
            }
        })();
    </script>
@endsection
