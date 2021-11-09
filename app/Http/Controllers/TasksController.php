<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿を取得
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            //$tasks = Task::all();

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        // indexビューでそれらを表示
        return view('tasks.index', $data);

        /*return view('tasks.index', [
            'tasks' => $tasks,
        ]);*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::check()) {
        $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
        }else{
            return redirect('/');
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'status'=>'required|max:10',
            'content'=>'required'
            ]);
        /*
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        */
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,
            ]);
    
        // 前のURLへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
                
        if(\Auth::id() === $task->user_id) {
        return view('tasks.show', [
            'task' => $task,
        ]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
        return view('tasks.edit', [
            'task' => $task,
        ]);
        }else{
            return redirect('/');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status'=>'required|max:10',
            'content'=>'required'
            
            ]);
            
        $task = Task::findOrFail($id);
        
        
        // メッセージを更新
        if (\Auth::id() === $task->user_id){
            $task->status = $request->status;
            $task->content = $request->content;
            $task->save();
        }else{
            return redirect('/');
        }
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
