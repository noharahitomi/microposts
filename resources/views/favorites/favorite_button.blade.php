@if(Auth::user()->favorite_now($micropost->id))
        {{-- お気に入り解除ボタン --}}
        {!! Form::open(['route'=>['favorites.unfavorite', $micropost->id], 'method'=>'delete']) !!}
            {!! Form::submit('Unfavorite', ['class' => "btn btn-success btn-sm"]) !!}
        {!! Form::close() !!}
    @else
        {{-- お気に入りボタンのフォーム --}}
        {!! Form::open(['route'=>['favorites.favorite', $micropost->id]]) !!}
            {!! Form::submit('Favorite', ['class' => "btn btn-light btn-sm"]) !!}
        {!! Form::close() !!}
@endif