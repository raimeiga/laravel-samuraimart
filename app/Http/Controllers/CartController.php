<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
 use Illuminate\Support\Facades\Auth;

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
    public function destroy($id)
    {
        //
    }
}
