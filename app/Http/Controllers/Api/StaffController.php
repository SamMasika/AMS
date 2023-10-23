<?php

namespace App\Http\Controllers\Api;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function index()
    {
     $staff=Staff::with('section')->with('office')->get();
     return response()->json(['success' => true, 'status' => 300, 'data' =>
     $staff], 200);
    }
    public function store(Request $request)
    {
        $user = Auth::user()->fname . ' ' . Auth::user()->lname;
    
        $data = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email',
        ]);
    
        if ($data->fails()) {
            return $this->sendError('Validation Error', $data->errors(), 403);
        }
    
        $staff = new Staff();
        $staff->fname = $request->input('fname');
        $staff->mname = $request->input('mname');
        $staff->lname = $request->input('lname');
        $staff->email = $request->input('email');
        $staff->phone = $request->input('phone');
        $staff->section_id = $request->input('section_id');
        $staff->office_id = $request->input('office_id');
        $staff->password = Hash::make($staff->lname);
        $staff->created_by = $user;
        $staff->save();
        return response()->json([
            'success' => true, 'status' => 300,  'message' => 'Staff Stored successfully!'
        ]);
      
    }
    
    public function show($id)
    {
        $staff = Staff::find($id);
        if ($staff) {
            return response()->json(['success' => true, 'status' => 300, 'data' =>
            $staff], 200);
        } else {
            return response()->json(['success' => false, 'status' => 404, 'message' => 'No Staff of that ID']);
        }
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::find($id);
        $staff->fname = $request->input('fname');
        $staff->mname = $request->input('mname');
        $staff->lname = $request->input('lname');
        $staff->email = $request->input('email');
        $staff->phone = $request->input('phone');
        $staff->section_id = $request->input('section_id');
        $staff->staff_id = $request->input('staff_id');
        $staff->created_by = $user;
        $staff->save();
        return response()->json([
            'success' => true, 'status' => 300,  'message' => 'Staff Updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $staff = Staff::find($id);
        if ($staff) {
            $staff->delete();
            return response()->json(['success' => true, 'status' => null, 'message' => 'Staff deleted successfully!']);
        } else {
            return response()->json(['success' => false, 'status' => 404, 'message' => 'No Staff to be deleted!!']);
        }
    }
}
