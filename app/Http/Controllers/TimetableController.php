<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    private array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    public function dashboard()
    {
        $data = [];
        foreach ($this->days as $day) {
            $data[$day] = (new Timetable())->setDay($day)->get();
        }
        return view('timetable.dashboard', ['data' => $data, 'title' => 'Dashboard']);
    }

    public function display()
    {
        $data = [];
        foreach ($this->days as $day) {
            $data[$day] = (new Timetable())->setDay($day)->get();
        }
        return view('timetable.display', ['data' => $data, 'title' => 'Timetable']);
    }

    public function store(Request $request, $day)
    {
        $request->validate([
            'teacher_name' => 'required|string|max:100',
            'subject' => 'required|string|max:100',
            'class_room' => 'nullable|string|max:50',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
            'class_name' => 'nullable|string|max:100',
        ]);

        (new Timetable())->setDay($day)->create($request->all());

        return redirect()->route('timetable.dashboard')->with('success', ucfirst($day) . ' added!');
    }

    public function show($day, $id)
    {
        $record = (new Timetable())->setDay($day)->findOrFail($id);
        return view('timetable.show', ['record' => $record, 'day' => $day, 'title' => 'View Record']);
    }

    public function edit($day, $id)
    {
        $record = (new Timetable())->setDay($day)->findOrFail($id);
        return view('timetable.edit', ['record' => $record, 'day' => $day, 'title' => 'Edit Record']);
    }

    public function update(Request $request, $day, $id)
    {
        $request->validate([
            'teacher_name' => 'required|string|max:100',
            'subject' => 'required|string|max:100',
            'class_room' => 'nullable|string|max:50',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
            'class_name' => 'nullable|string|max:100',
        ]);

        $record = (new Timetable())->setDay($day)->findOrFail($id);
        $record->update($request->all());

        return redirect()->route('timetable.dashboard')->with('success', ucfirst($day) . ' record updated!');
    }

    public function destroy($day, $id)
    {
        (new Timetable())->setDay($day)->findOrFail($id)->delete();

        return redirect()->route('timetable.dashboard')->with('success', ucfirst($day) . ' deleted!');
    }


    public function index()
    {
        $now = now('Asia/Kolkata');
        $today = strtolower($now->format('l'));
        $timeNow = $now->format('H:i:s');

        // Get current class (based on now being between start & end)
        $currentClasses = (new Timetable())
            ->setDay($today)
            ->where('start_time', '<=', $timeNow)
            ->where('end_time', '>', $timeNow)
            ->orderBy('start_time')
            ->get();

        $nextClasses = collect();

        if ($currentClasses->isNotEmpty()) {
            // Group current classes by end_time
            $groupedByEnd = $currentClasses->groupBy('end_time');

            foreach ($groupedByEnd as $endTime => $classesGroup) {
                $nextGroup = (new Timetable())
                    ->setDay($today)
                    ->where('start_time', '=', $endTime)
                    ->orderBy('start_time')
                    ->get();

                if ($nextGroup->isNotEmpty()) {
                    $nextClasses->push([
                        'end_time' => $endTime,
                        'currents' => $classesGroup,
                        'next' => $nextGroup
                    ]);
                }
            }
        }

        if (request()->ajax()) {
            return response()->json([
                'now' => $timeNow,
                'currentClasses' => $currentClasses,
                'nextClasses' => $nextClasses,
            ]);
        }

        return view('timetable.index', [
            'title' => "Today's Schedule",
            'today' => ucfirst($today),
            'currentClasses' => $currentClasses,
            'nextClasses' => $nextClasses,
        ]);
    }
}
