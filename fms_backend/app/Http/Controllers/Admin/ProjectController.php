<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Project;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index()
    {
        try {
            $projects = Project::orderBy('id', 'DESC')->get();
            return response()->json(['msg' => 'success', 'response' => 'successfully', 'data' => $projects]);
        } catch (\Exception $e) {
            return response()->json(['msg' => 'error', 'response' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'validation_error', 'errors' => $validator->errors()], 400);
        }

        try {
            $query = Project::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'created_by' => Auth::user()->id,
                'created_at' => now(),
            ]);

            if ($query) {
                return response()->json(['msg' => 'success', 'response' => 'Project successfully added.']);
            } else {
                return response()->json(['msg' => 'error', 'response' => 'Something went wrong!'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['msg' => 'error', 'response' => $e->getMessage()], 500);
        }
    }

    public function edit($id, Request $request)
    {
        try {
            $project = Project::find($id);

            if (!$project) {
                return response()->json(['msg' => 'error', 'response' => 'Project not found.'], 404);
            }

            return response()->json(['msg' => 'success', 'response' => 'successfully', 'data' => $project]);
        } catch (\Exception $e) {
            return response()->json(['msg' => 'error', 'response' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'validation_error', 'errors' => $validator->errors()], 400);
        }

        try {
            $project = Project::find($data['id']);

            if (!$project) {
                return response()->json(['msg' => 'error', 'response' => 'Project not found.'], 404);
            }

            $post_status = $project->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'updated_at' => now(),
                'updated_by' => Auth::user()->id,
            ]);

            if ($post_status) {
                return response()->json(['msg' => 'success', 'response' => 'Project successfully updated!']);
            } else {
                return response()->json(['msg' => 'error', 'response' => 'Something went wrong!'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['msg' => 'error', 'response' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        $data = $request->all();
        $project = Project::find($data['id']);

        if (!$project) {
            return response()->json(['msg' => 'error', 'response' => 'Project not found.'], 404);
        }

        try {
            $project->delete();
            return response()->json(['msg' => 'success', 'response' => 'Project successfully deleted.']);
        } catch (\Exception $e) {
            return response()->json(['msg' => 'error', 'response' => $e->getMessage()], 500);
        }
    }
}