<div class="container">
     @foreach ($major_category_names as $major_category_name)
         <h2>{{ $major_category_name }}</h2>
         @foreach ($categories as $category)
             @if ($category->major_category_name === $major_category_name)
             <label class="samuraimart-sidebar-category-label"><a href="{{ route('products.index', ['category' => $category->id]) }}">{{ $category->name }}</a></label>             @endif
         @endforeach                                                 <!--  ↑ 呼び出すルーティングの後に連想配列[ ]で変数を渡すことで、コントローラー側（今回はindexアクション）へ値を渡している -->
     @endforeach
 </div>