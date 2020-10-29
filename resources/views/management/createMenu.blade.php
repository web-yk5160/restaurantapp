@extends("layouts.app")

@section('content')
<div class="container">
  <div class="row justify-content-center">
    @include('management.inc.sidebar')
    <div class="col-md-8">
      <i class="fas fa-hamburger"></i> メニュー作成
      <hr>
      @if($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
      <form action="/management/menu" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="menuName">メニュー名</label>
          <input type="text" name="name" class="form-control" place-holder="メニュー">
        </div>
        <label for="menuPrice">価格</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">¥</span>
          </div>
          <input type="text" name="price" class="form-control" aria-label="Amount">
          <div class="input-group-append">
            <span class="input-group-text">.00</span>
          </div>
        </div>
        <label for="MenuImage">画像</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">アップロード</span>
          </div>
          <div class="custom-file">
            <input type="file" name="image" class="custom-file-input" id="inputGroupFile01">
            <label class="custom-file-label" for="inputGroupFile01">ファイルを選択</label>
          </div>
        </div>

        <div class="form-group">
          <label for="Description">内容</label>
          <input type="text" name="description" class="form-control" placeholder="内容を入力">
        </div>

        <div class="form-group">
          <label for="Category">カテゴリー</label>
          <select class="form-control" name="category_id">
            @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-primary">保存</button>
      </form>
    </div>
  </div>
</div>
@endsection