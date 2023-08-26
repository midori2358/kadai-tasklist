<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\User;//試験的追記

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
        //タスク一覧を取得
        $tasks = Task::all();
        
        $data = [
             'tasks' => $tasks,
            ];
        }
        
        return view('tasks.index',$data);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;

        // 作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10', //追加
            'content' => 'required|max:255',
        ]);
        
       // タスクを作成
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasklists()->create([
       
        'status' => $request->status,
        'content' => $request->content,
       ]);

        // トップページへリダイレクトさせる
        return redirect('/dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
         // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
         // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は投稿を編集
         if (\Auth::id() === $task->user_id) {


        // メッセージ詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);
         }
           // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は投稿を編集
         if (\Auth::id() === $task->user_id) {

        // メッセージ編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
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
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);
        
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        // メッセージを更新
        $task->status = $request->status;    // 追加
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
           // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は投稿を削除
         if (\Auth::id() === $task->user_id) {
        
        // メッセージを削除
        $task->delete();
       
         }

        
    }
}
