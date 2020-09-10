<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function store($micropostId)
    {
        //認証済みのユーザがidのmicropostをお気に入りにする
        \Auth::user()->favorite($micropostId);
        
        //前のURLへリダイレクトさせる
        return back();
    }
    
    public function destroy($micropostId)
    {
        //認証済みのユーザがidのmicropostのお気に入りを外す
        \Auth::user()->unfavorite($micropostId);
        
        //前のURLへリダイレクトさせる
        return back();
    }
}
