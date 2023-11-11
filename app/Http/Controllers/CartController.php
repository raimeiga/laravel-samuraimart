<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // 現在カートに入っている商品一覧を表示（カート機能）
    public function index()
    {   // ユーザーのIDを元にこれまで追加したカートの中身を$cart変数に保存し、下のcompact関数でビュー（index.blade.php)に渡す
        $cart = Cart::instance(Auth::user()->id)->content(); 
 
        $total = 0;

        foreach ($cart as $c) {
            $total += $c->qty * $c->price;
        }

        return view('carts.index', compact('cart', 'total'));
    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {   // ユーザーのIDを元に、カートのデータ（商品のidやname↓）を作成し、add()関数を使って送信されたデータを元に商品を追加
        Cart::instance(Auth::user()->id)->add(
            [
                'id' => $request->id, 
                'name' => $request->name, 
                'qty' => $request->qty, 
                'price' => $request->price, 
                'weight' => $request->weight, 
            ] 
        );
        // 商品をカートに追加した後、そのまま商品の個別ページへとリダイレクト
        return to_route('products.show', $request->get('id'));
    } 
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user_shoppingcarts = DB::table('shoppingcart')->where('instance', Auth::user()->id)->get();
        $count = $user_shoppingcarts->count();  //現在までのユーザーが注文したカートの数を取得

        $count += 1;  //新しくデータベースに登録するカートのデータ用にカートのIDを一つ増やしている
        Cart::instance(Auth::user()->id)->store($count);    //ユーザーのIDを使ってカート内の商品情報などをデータベースへと保存

        //　データベース内のshoppingcartテーブルへにアクセスし、where()を使ってユーザーのIDとカート数$countを使い、作成したカートのデータを更新
        DB::table('shoppingcart')->where('instance', Auth::user()->id)->where('number', null)->update(['number' => $count, 'buy_flag' => true]);

        Cart::instance(Auth::user()->id)->destroy();

        return to_route('carts.index');
    }
}
