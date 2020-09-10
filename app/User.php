<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    public function loadRelationshipCounts()
    {
        $this->loadCount('microposts', 'followings', 'followers', 'favorites');
    }
    
    // このユーザがフォロー中のユーザ。（Userモデルとの関係を定義）
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    // このユーザをフォロー中のユーザ。（Userモデルとの関係を定義）
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    public function follow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // すでにフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    public function unfollow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;
        
        if($exist && !$its_me){
            // すでにフォローしていればフォローをはずす
            $this->followings()->detach($userId);
            return true;
        }else{
            // 未フォローであれば何もしない
            return false;
        }
    }
    
    public function is_following($userId)
    {
        // フォロー中ユーザの中に$userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    public function feed_microposts()
    {
        //このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        
        //このユーザのidもその配列に追加
        $userIds[] = $this->id;
        
        //それらのユーザの所有する投稿を絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
    
    //このユーザの所有するお気に入りの投稿
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id');
    }
    
    public function favorite_now($micropostId)
    {
        //お気に入りしている投稿の中に当該の投稿idがあるのか
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }
    
    public function favorite($micropostId)
    {
        //すでにお気に入りにしているかの確認
        $exist = $this->favorite_now($micropostId);
        
        if($exist){
            //何もしない
            return false;
        }else{
            //お気に入りにしていなければ、お気に入りに登録する
            $this->favorites()->attach($micropostId);
            return true;
        }
        
    }
    
    public function unfavorite($micropostId)
    {
        //すでにお気に入りにしているかの確認
        $exist = $this->favorite_now($micropostId);
        
        if($exist){
            //お気に入りしていれば、登録をはずす
            $this->favorites()->detach($micropostId);
            return true;
        }else{
            //お気に入りしていなければ、何もしない
            return false;
        }
    }
}
