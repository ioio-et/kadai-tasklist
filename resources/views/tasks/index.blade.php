@extends('layouts.app')

@section('content')
@if (Auth::check())
        @if (count($tasks) > 0)
        <table class="table table-striped">
    <thead>
        <tr>
            <th>id</th>
            <th>ステータス</th>
            <th>タスク</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tasks as $task)
        <tr>
            <td>{!! link_to_route('tasks.show',$task->id,['task' => $task->id]) !!}</td>
            <td>{{ $task->status }}</td>
            <td>{{ $task->content }}</td>
        </tr>
        @endforeach
         </tbody>
        </table>
        @endif
{{--メッセージ作成ページへのリンク--}}
        <div class="center">
                {!! link_to_route('tasks.create','新規タスクの投稿',[],['class' => 'btn btn-lg btn-primary']) !!}
        </div>
    @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>Welcome to the Tasklist</h1>
                {{-- ユーザ登録ページへのリンク --}}
                {!! link_to_route('signup.get', 'Sign up now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
        </div>
    @endif





@endsection