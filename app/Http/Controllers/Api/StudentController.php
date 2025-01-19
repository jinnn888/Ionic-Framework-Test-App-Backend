<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\StudentResource;
use App\Models\Student;

class StudentController extends Controller
{

    public function index() {
        $students = Student::all();
        return response()->json([
            'students' => StudentResource::collection($students)
        ], 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' =>  'required|string|max:255',
            'id_number' => 'required|string|max:255|unique:students,id_number',
            'strand' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
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

    public function edit(string $id) {
        $student = Student::findOrFail($id);

        return new StudentResource($student);
    }

    public function update(string $id, Request $request) {
        $student = Student::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' =>  'required|string|max:255',
            'id_number' => [
                'required',
                'string', 
                'max:255', 
                Rule::unique('students', 'id_number')->ignore($id)
            ],
            'strand' => 'required|string|max:255',
        ]);

        if ($validator->fails())  {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }       

        $student->update([
            'name' => $request->name,
            'id_number' => $request->id_number,
            'strand' => $request->strand,
        ]);

        return response()->json([
            'message' => 'Student updated successfuly',
            'student' => new StudentResource($student)
        ], 200);

    }

}
