<?php

namespace App\Http\Controllers\Api;

use App\Models\Office;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OfficeController extends Controller
{
    public function index()
    {
     $office=Office::all();
     return response()->json(['success' => true, 'status' => 300, 'data' =>
     $office], 200);
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
    
        $office = new Office();
        $office->name = $request->input('name');
        $office->created_by = $user;
        $office->description = $request->input('description');
        $office->save();
        return response()->json([
            'success' => true, 'status' => 300,  'message' => 'Office Stored successfully!'
        ]);
      
    }
    
    public function show($id)
    {
        $office = Office::find($id);
        if ($office) {
            return response()->json(['success' => true, 'status' => 300, 'data' =>
            $office], 200);
        } else {
            return response()->json(['success' => false, 'status' => 404, 'message' => 'No Office of that ID']);
        }
    }

    public function update(Request $request, $id)
    {
        $office = Office::find($id);
        $office->update($request->all());
        return response()->json([
            'success' => true, 'status' => 300,  'message' => 'Office Updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $office = Office::find($id);
        if ($office) {
            $office->delete();
            return response()->json(['success' => true, 'status' => null, 'message' => 'Office deleted successfully!']);
        } else {
            return response()->json(['success' => false, 'status' => 404, 'message' => 'No Office to be deleted!!']);
        }
    }
}
