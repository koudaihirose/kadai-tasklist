<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // タスク一覧を取得
        //$tasks = Task::all();
        
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザの投稿も取得するように変更しますが、現時点ではこのユーザの投稿のみ取得します）
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
        
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        //タスク一覧ビューでそれを表示
        return view('welcome',$data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;

        // タスク作成ビューを表示
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
            'status' => 'required|max:10', 
            'content' => 'required|max:255',
        ]);
        
        // タスクを作成
        //$task = new Task;
       // $task->status = $request->status;    // 追加
       // $task->content = $request->content;
      
        //$task->save();
        
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,
            
            
        ]);

        
        

        // トップページへリダイレクトさせる
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

        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //本人以外のユーザーがアクセスした場合はトップページへ移動
        if (\Auth::id() != $task->user_id) {
            return redirect('/');
        }
        
        
        // 関係するモデルの件数をロード
        //$task->loadRelationshipCounts();

        // ユーザの投稿一覧を作成日時の降順で取得
        //$tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10)
        
        // タスク詳細ビューでそれを表示
        return view('tasks.show', [
            //'user' => $user,
            'task' => $task,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //本人以外のユーザーがアクセスした場合はトップページへ移動
        if (\Auth::id() != $task->user_id) {
            return redirect(RouteServiceProvider::HOME);
        }
    
        // タスク編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
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
            'status' => 'required|max:10',  
            'content' => 'required|max:255',
        ]);
       
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //本人以外のユーザーがアクセスした場合はトップページへ移動
        if (\Auth::id() != $task->user_id) {
            return redirect(RouteServiceProvider::HOME);
        }
        
        // タスクを更新
        $task->status = $request->status;    // 追加
        $task->content = $request->content;
        $task->save();

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
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //本人以外のユーザーがアクセスした場合はトップページへ移動
        if (\Auth::id() != $task->user_id) {
            return redirect(RouteServiceProvider::HOME);
        }
        
        //dd($task);
        // タスクを削除
        //$task->delete();
        
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
