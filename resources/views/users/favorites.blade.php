@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-sm-4">
            {{-- ユーザ情報 --}}
            @include('users.card')
        </aside>
        <div class="col-sm-8">
            {{-- タブ --}}
            @include('users.navtabs')
            
            {{-- お気に入り一覧 --}}
            @if (count($microposts) > 0)
                <ul clas="list-unstyled">
                    @foreach($microposts as $micropost)
                        <li class="media mb-3">
                        {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                        <img class="mr-2 rounded" src="{{ Gravatar::get($micropost->user->email, ['size' => 50]) }}" alt ="">
                        <div class="media-body">
                            <div>
                            {{-- 投稿の所有者のユーザ詳細ページへのリンク --}}
                            {!! link_to_route('users.show', $micropost->user->name, ['user' => $micropost->user->id]) !!}
                                <span class="text-muted">posted at {{ $micropost->created_at }}</span>
                            </div>
                            <div>
                            {{-- 投稿内容 --}}
                                <p class="mb-0">{!! nl2br(e($micropost->content)) !!}</p>
                            </div>
                            <div class="d-flex">
                                <div class="mr-1">
                                    {{-- お気に入りボタンのフォーム --}}
                                    @include('favorites.favorite_button')
                                </div>
                                <div>
                                    @if(Auth::id() == $micropost->user_id)
                                    {{-- 投稿削除ボタンのフォーム --}}
                                    {!! Form::open(['route' => ['microposts.destroy', $micropost->id], 'method' => 'delete']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                    {!! Form::close() !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @endif
    </div>
</div>
@endsection