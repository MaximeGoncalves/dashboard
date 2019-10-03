<?php

namespace App\Http\Controllers\API;

use App\TaskProject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class TaskProjectController extends Controller
{
    public function index()
    {
        return TaskProject::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $project = new TaskProject();
        $project->name = $request->name;
        $project->save();
        return $project;
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required'
        ]);
        $project = TaskProject::find($id);
        $project->name = $request->get('name');
        $project->save();
        return $project;
    }

    public function show($id){
        return TaskProject::find($id);
    }
    public function destroy($id){
        $project = TaskProject::find($id);
        $project->tasks()->delete();
        $project->delete();
    }
}
