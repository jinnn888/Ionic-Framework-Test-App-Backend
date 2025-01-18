<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\StudentResource;
use App\Models\Student;

class StudentController extends Controller
{

    public function index() {
        $students = Student::all();
        return response()->json([
            'students' => StudentResource($students)
        ], 200);
    }

    public function store(Request $request) {
        $validated = Validator::make($request->all(), [
            'name' =>  'required|string|max:255',
            'id_number' => 'required|string|max:255|unique:students,id_number',
            'strand' => 'required|string|max:255',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validated->errors()
            ], 422);
        }

        $student = Student::create([
            'name' => $request->name,
            'id_number' => $request->id_number,
            'strand' => $request->strand,
        ]);

        return response()->json([
            'message' => 'Student added successfuly',
            'student' => new StudentResource($student)
        ], 201);

    }
}
