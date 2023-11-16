<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;  //Productモデルの使用宣言 $Productと書けば、Productインスタンスを使用できる
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;    
/* ↑ Authファサードを利用することで、「現在ログイン中のユーザー」を取得
     Authファサードは、クラスをインスタンス化しなくても、ファイル内でAuth::user()を記述することで、
     現在ログイン中のユーザーのデータ（Userモデルのインスタンス）を取得できる。
*/
use Illuminate\Http\Request;
use App\Models\ShoppingCart;
use Illuminate\Pagination\LengthAwarePaginator;


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
    
    //　パスワードの変更のアクション
    public function update_password(Request $request)
     {
         $validatedData = $request->validate([
             'password' => 'required|confirmed',
         ]);
 
         $user = Auth::user();
         //passwordとpassword_confirmationが同一のものであるかを確認  ↓if文で一致・不一致で条件分岐させる
         if ($request->input('password') == $request->input('password_confirmation')) {  //同じである場合のみパスワードを暗号化しデータベースへと保存
             $user->password = bcrypt($request->input('password')); 
             $user->update();
         } else {
             return to_route('mypage.edit_password'); //異なっていた場合は、パスワード変更画面へとリダイレクト
         } 
         return to_route('mypage');
     }

     //  パスワード変更画面を表示するアクション
     public function edit_password()
     {
         return view('users.edit_password');
     }

     // お気に入りした商品を取得し、ビューに渡す処理
     public function favorite()
     {
         $user = Auth::user();
        //  ユーザーがお気に入り登録した商品一覧を取得し、その下のcompact関数でビュー(favorite.blade.php)に渡す
         $favorites = $user->favorites(Product::class)->get(); 
 
         return view('users.favorite', compact('favorites'));
     }

     public function destroy(Request $request)
     {
         Auth::user()->delete();
         return redirect('/');
     }

     // ↓ 注文履歴の一覧を表示するアクション
     public function cart_history_index(Request $request)
     {
         $page = $request->page != null ? $request->page : 1;
         $user_id = Auth::user()->id;
         // ↓ ShoppingCart.phpで書いたgetCurrentUserOrders関数（メソッドかも）を呼び出しているらしい
         $billings = ShoppingCart::getCurrentUserOrders($user_id); 
         $total = count($billings);
         $billings = new LengthAwarePaginator(array_slice($billings, ($page - 1) * 15, 15), $total, 15, $page, array('path' => $request->url()));
 
         return view('users.cart_history_index', compact('billings', 'total'));
     }

}
