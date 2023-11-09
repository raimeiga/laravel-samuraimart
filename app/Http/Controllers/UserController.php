<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;    
/* ↑ Authファサードを利用することで、「現在ログイン中のユーザー」を取得
     Authファサードは、クラスをインスタンス化しなくても、ファイル内でAuth::user()を記述することで、
     現在ログイン中のユーザーのデータ（Userモデルのインスタンス）を取得できる。
*/
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function mypage()
    {
        $user = Auth::user();  // 現在ログイン中のユーザーのデータを$userに保存し、↓のcompact関数でmypage.blade.phpのビューに渡す

        return view('users.mypage', compact('user'));  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $user = Auth::user();
 
         return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = Auth::user();
        
        /* ↓　(? クエスチョンマーク)は三項演算子で、「＜条件式＞?＜条件式が真の場合＞:＜条件式が偽の場合＞」って使う
           updateなので、ユーザー情報が渡された状態でビューが表示され、'name'が更新されていたら、その新たな'name'を、更新されていない場合は、元の'name'を保存するってことかな
        */
        $user->name = $request->input('name') ? $request->input('name') : $user->name;
        $user->email = $request->input('email') ? $request->input('email') : $user->email;
        $user->postal_code = $request->input('postal_code') ? $request->input('postal_code') : $user->postal_code;
        $user->address = $request->input('address') ? $request->input('address') : $user->address;
        $user->phone = $request->input('phone') ? $request->input('phone') : $user->phone;
        $user->update();

        return to_route('mypage');
    }       
}
