<a href="{{ route('products.create') }}"> Create New Product</a>  <!-- 商品データの新規登録ページ(create.blade.php)へのリンク -->
 
 <table>
     <tr>
         <th>Name</th>
         <th>Description</th>
         <th>Price</th>
         <th>Category ID</th>
         <th >Action</th>
     </tr>
     @foreach ($products as $product)
     <tr>
         <td>{{ $product->name }}</td>
         <td>{{ $product->description }}</td>
         <td>{{ $product->price }}</td>
         <td>{{ $product->category_id }}</td>
         <td>
         <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                <a href="{{ route('products.show',$product->id) }}">Show</a>  <!-- 商品の個別ページ(show.blade.php)へのリンク -->
                <a href="{{ route('products.edit',$product->id) }}">Edit</a>  <!-- 商品情報の編集ページ(edit.blade.php)へのリンク -->
              @csrf
              @method('DELETE')  <!-- ←　リクエストがDELETEであると分かるように書き、数行上のactionでリクエストの送信先を指定 -->
              <button type="submit">Delete</button>
             </form>
         </td>         
     </tr>
     @endforeach
 </table>
