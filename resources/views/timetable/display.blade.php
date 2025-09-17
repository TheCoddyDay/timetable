@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <h1 class="mb-0">Weekly Timetable</h1>
        <div class="d-flex flex-column flex-sm-row gap-2">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input id="displaySearchInput" type="text" class="form-control" placeholder="Search teacher, subject, room, class">
            </div>
            <div class="btn-group">
                <a href="{{ route('timetable.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-home me-1"></i>Today's Schedule
                </a>
                <a href="{{ route('timetable.dashboard') }}" class="btn btn-outline-warning">
                    <i class="fas fa-tachometer-alt me-1"></i>Admin Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Day Selector -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-calendar-day me-2"></i>Select Day to View</h5>
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
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>Quick Info</h5>
                    <div id="dayInfo">
                        <p class="mb-1">Select a day to view schedule</p>
                        <small class="text-muted">Click on any day button above</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0" id="selectedDayTitle">Select a day to view schedule</h5>
            <span class="badge bg-light text-dark" id="recordCount">0 records</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 display-table" id="mainTable">
                    <thead>
                        <tr class="table-light" style="position: sticky; top: 0; z-index: 1;">
                            <th>Teacher</th>
                            <th>Subject</th>
                            <th>Room</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Class</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-calendar-day me-2"></i>Please select a day to view schedule
                            </td>
                        </tr>
                    </tbody>
                </table>
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
            const dayInfo = document.getElementById('dayInfo');
            const searchInput = document.getElementById('displaySearchInput');
            
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
                    selectedDayTitle.textContent = `${this.textContent.trim().replace(/\d+$/, '').trim()} Schedule`;
                    recordCount.textContent = `${currentData.length} records`;
                    
                    // Update day info
                    dayInfo.innerHTML = `
                        <p class="mb-1"><strong>${this.textContent.trim().replace(/\d+$/, '').trim()}</strong></p>
                        <small class="text-muted">${currentData.length} classes scheduled</small>
                    `;
                    
                    // Render table
                    renderTable();
                });
            });

            // Render table function
            function renderTable() {
                if (!currentData || currentData.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox me-2"></i>No classes scheduled for this day
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

            // Auto-select first day on load
            if (daySelectors.length > 0) {
                daySelectors[0].click();
            }
        })();
    </script>
@endsection
