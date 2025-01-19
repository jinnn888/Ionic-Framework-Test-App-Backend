<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Student;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Validator;

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

    public function getAbsenteesToday()
    {
        $absentStudents = Student::whereDoesntHave('attendances', function ($query) {
            $query->whereDate('day', Carbon::today());
        })->get();

        return response()->json([
            'absentees' => $absentStudents
        ]);
    }

    public function filterPresentAttendances(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $attendances = Attendance::with('student')->whereDate('day', $date)->get();
        return response()->json([
            'attendances' => AttendanceResource::collection($attendances)
        ]);
    }

    public function filterAbsentAttendances(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $absentStudents = Student::whereDoesntHave('attendances', function($query) use ($date) {
            $query->whereDate('day', $date);
        })->get();
        return response()->json([
            'attendances' => StudentResource::collection($absentStudents)
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_number' => 'required|exists:students,id_number'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error',
                'error' => 'Student not found.'
            ], 422);
        }


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
