<!-- 商品情報の編集ページ -->
<div>
     <h2>Edit Product</h2>
 </div>
 <div>
     <a href="{{ route('products.index') }}"> Back</a>
 </div>
 
 <form action="{{ route('products.update',$product->id) }}" method="POST">
     @csrf
     @method('PUT')
 
     <div>
         <strong>Name:</strong>             <!-- 「product->name」で登録済み商品の名前が示される -->
         <input type="text" name="name" value="{{ $product->name }}" placeholder="Name"> 
     </div>
     <div>                                      
         <strong>Description:</strong>                              <!-- 「product->description」で登録済み商品の説明が示される -->
         <textarea style="height:150px" name="description" placeholder="description">{{ $product->description }}</textarea>
     </div>
     <div>
         <strong>Price:</strong>                <!-- 「product->price」で登録済み商品の価格が示される -->
         <input type="number" name="price"  value="{{ $product->price }}">
     </div>
     <div>
         <strong>Category:</strong>
         <select name="category_id">
             @foreach ($categories as $category)  <!-- ↓のif文がわからないから、いつか解明しよう 教材は「5.6 商品登録時にカテゴリを選択できるようにしよう」 -->
             @if ($category->id == $product->category_id)
                 <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
             @else
                 <option value="{{ $category->id }}">{{ $category->name }}</option>
             @endif
         @endforeach
         </select>
     </div>
     <div>
         <button type="submit">Submit</button>
     </div>
 
 </form>