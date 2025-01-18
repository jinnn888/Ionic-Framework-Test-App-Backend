<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Student;
use App\Http\Resources\AttendanceResource;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::with('student')->get();
        return AttendanceResource::collection($attendances);
    }

    public function getPresentsToday()
    {
        $attendances = Attendance::with('student')->where('day', Carbon::today())->get();

        return response()->json([
            'attendances' => AttendanceResource::collection($attendances)
        ]);

    }

    public function filterAttendances(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $attendances = Attendance::with('student')->where('day', $date)->get();
        return response()->json([
            'attendances' => AttendanceResource::collection($attendances)
        ]);
    }

    public function store(Request $request)
    {

        $today = Carbon::today();
        $currentTime = Carbon::now();

        $student = Student::where('id_number', $request->id_number)->first();

        Attendance::create([
            'student_id' => $student->id,
            'day' => $today,
            'time_in' => $currentTime
        ]);

        return response()->json([
            'data' => $request->student,
            'message' => 'Attendance recorded.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
