<?php

namespace App\Http\Controllers\API;

use App\Attachment;
use App\Lists;
use App\Notifications\TaskCreate;
use App\Society;
use App\Subtask;
use App\Task;
use App\TaskProject;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Test\Feature\AttachmentTest;

class TaskController extends Controller
{
    public function index(Request $request)
    {
//        if ($request->get('filterBy') === 0 || !$request->get('filterBy')) {
//            $tasks = Task::with(['users', 'attachments', 'project', 'comments'])->where('society_id', Auth::user()->society->id)->get();
//        } else {
//            $tasks = Task::with(['users', 'attachments', 'project', 'comments'])
//                ->where('project_id', $request->get('filterBy'))
//                ->where('society_id', Auth::user()->society->id)->get();
//        }
//
//        foreach ($tasks as $task) {
//            $task->subtasks = Subtask::where('task_id', $task->id)->get();
//            $total = Subtask::where('task_id', $task->id)->get()->count();
//            $checked = Subtask::where('task_id', $task->id)->where('state', true)->get()->count();
//            $task->checked = $checked;
//            if ($total != 0) {
//                $task->percent = ($checked * 100) / $total;
//            } else {
//                $task->percent = 0;
//            }
//        }
//        $subtasks = Subtask::all();
//        $projects = TaskProject::all();
//        $users = User::where('society_id', Auth::user()->society->id)->get();
//        return response()->json(compact(['tasks', 'users', 'projects']));

    }

    /**
     * @param Request $request
     * @return $this
     * @throws ValidationException
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'list' => 'required'
        ]);

        $task = new Task();
        $task->name = $request->get('name');
        $task->list()->associate($request->list);
        $task->board()->associate($request->board);
        $task->save();
        return response()->json(['task' => $task]);

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
//        $users = $request->get('users');
//        $u = [];
//        foreach ($users as $user) {
//            $u[] = $user['id'];
//        }
//        $user = User::find($u);
//        $project = TaskProject::find($request->get('project')['id']);
        $task = Task::findOrFail($id);
        $task->name = $request->get('name');
//        $task->description = $request->get('description');
//        $task->priority = $request->get('priority');
//        $task->dueDate = $request->get('dueDate');
//        $task->project()->associate($request->get('project')['id']);
        $task->save();
//        $task->users()->sync($user);
        return response($task);
    }

    public function updateDescription(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->description = $request->get('description');
        $task->save();
        return $task;
    }

    public function updateList(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->list()->associate($request->get('list'));
        $task->save();
        return $task;
    }

    public function show(Request $request, $id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        $task->subtasks = Subtask::where('task_id', $task->id)->get();
        $this->getTaskPercent($task);
        $task->load(['attachments', 'comments', 'list']);
        return $task;
    }

    /**
     * SOFT DELETE
     * @param Request $request
     * @param $id
     */
    public function archive(Request $request, $id){
        $task = Task::findOrFail($id);
        $task->delete();
        return $task;
    }

        /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        Storage::deleteDirectory('/TaskManager/' . $task->society->name . '/' . $task->id);
        foreach ($task->attachments()->get() as $attach) {
            File::delete($attach->url);
        }
        $task->comments()->delete();
        $task->attachments()->delete();
        $task->delete();
        return response()->json(['response' => 'Tache supprimé']);
    }

    public function addUserToTask(Request $request, $id)
    {
        $members = $request->get('members');
        $membersObject = (object) $members;
        $task = Task::findOrFail($id);
        $task->members = json_encode($membersObject);
        $task->save();
        return $members;
    }

    public function addDueDateToTask(Request $request, $id){
        $task = Task::findOrFail($id);
        $task->dueDate = $request->get('date');
        $task->save();
    }

    public function resendToBoard(Request $request, $id){
        $task = Task::withTrashed()->findOrFail($id);
        $task->restore();
        return $task;
    }
    public function getArchived($id){
        return Task::onlyTrashed()->where('board_id', $id)->get();
    }
    public function storeSub(Request $request)
    {
        $sub = new Subtask();
        $sub->name = $request->get('name');
        $sub->task()->associate($request->get('task'));
        $sub->save();
        return response($sub);

    }

    public function updateNameSub(Request $request, $id)
    {
        $subtask = Subtask::findOrFail($id);
        $subtask->name = $request->get('name');
        $subtask->save();
        return response($subtask);
    }

    public function updateCheckSub(Request $request, $id)
    {
        $subtask = Subtask::findOrFail($id);
        $subtask->state = $request->get('state');
        $subtask->save();
        return response($subtask);
    }

    public function destroySub(Request $request, $id)
    {
        $subtask = Subtask::findOrFail($id);
        $subtask->delete();
        return response('sub delete');
    }

    private function getTaskPercent($task)
    {
        $total = Subtask::where('task_id', $task->id)->get()->count();
        $checked = Subtask::where('task_id', $task->id)->where('state', true)->get()->count();
        if ($total != 0) {
            $task->percent = ($checked * 100) / $total;
        } else {
            $task->percent = 0;
        }
    }

    public function getPercent($id)
    {
        $task = Task::findOrFail($id);
        $total = Subtask::where('task_id', $task->id)->get()->count();
        $checked = Subtask::where('task_id', $task->id)->where('state', true)->get()->count();
        $percent = 0;
        if ($total != 0) {
            $percent = ($checked * 100) / $total;
        } else {
            $percent = 0;
        }
        return $percent;
    }
}
