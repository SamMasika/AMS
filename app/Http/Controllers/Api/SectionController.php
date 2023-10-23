<?php

namespace App\Http\Controllers\Api;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    public function index()
    {
     $section=Section::all();
     return response()->json(['success' => true, 'status' => 300, 'data' =>
     $section], 200);
    }
    public function store(Request $request)
{
    $user = Auth::user()->fname . ' ' . Auth::user()->lname;

    $data = Validator::make($request->all(), [
        'name' => 'required',
        'description' => 'nullable',
    ]);

    if ($data->fails()) {
        return $this->sendError('Validation Error', $data->errors(), 403);
    }

    try {
        $section = new Section();
        $section->name = $request->input('name');
        $section->created_by = $user;
        $section->description = $request->input('description');
        $section->save();

        return response()->json([
            'success' => true,
            'status' => 300,
            'message' => 'Section Updated successfully!'
        ]);
    } catch (\Exception $e) {
        // Handle the exception and return an error response
        return $this->sendError('Error', 'Failed to save section: ' . $e->getMessage(), 500);
    }
}

    
    public function show($id)
    {
        $section = Section::find($id);
        if ($section) {
            return response()->json(['success' => true, 'status' => 300, 'data' =>
            $section], 200);
        } else {
            return response()->json(['success' => false, 'status' => 404, 'message' => 'No Section of that ID']);
        }
    }

    public function update(Request $request, $id)
    {
        $section = Section::find($id);
        $section->update($request->all());
        return response()->json([
            'success' => true, 'status' => 300,  'message' => 'Section Updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $section = Section::find($id);
        if ($section) {
            $section->delete();
            return response()->json(['success' => true, 'status' => null, 'message' => 'Section deleted successfully!']);
        } else {
            return response()->json(['success' => false, 'status' => 404, 'message' => 'No Section to be deleted!!']);
        }
    }
}
