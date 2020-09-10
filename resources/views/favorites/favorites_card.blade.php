<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $user-name }}</h3>
    </div>
    <div class="card-body">
        {{-- ユーザのメールアドレスをもとにGravatarを取得して表示 --}}
        <img class="rounder img-fluid" src="{{ Gravataer::get($user->email, ['size' => 500]) }}" alt ="" >
    </div>
</div>
{{-- お気に入り／解除ボタン --}}
@include('faborites.favorite_button')