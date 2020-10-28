@extends("layouts.app")

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="list-group">
          <a href="management/category" class="list-group-item list-group-item-action"><i class="fas fa-align-justify"></i> カテゴリー</a>
          <a class="list-group-item list-group-item-action"><i class="fas fa-hamburger"></i> メニュー</a>
          <a class="list-group-item list-group-item-action"><i class="fas fa-chair"></i> テーブル</a>
          <a class="list-group-item list-group-item-action"><i class="fas fa-users-cog"></i> ユーザー</a>
        </div>
      </div>
      <div class="col-md-8">
        <i class="fas fa-align-justify"></i> カテゴリー作成
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
        <form action="/management/category" method="POST">
          @csrf
          <div class="form-group">
            <label for="categoryName">カテゴリー名</label>
            <input type="text" name="name" class="form-control" place-holder="カテゴリー">
          </div>
          <button type="submit" class="btn btn-primary">保存</button>
        </form>
      </div>
    </div>
  </div>
@endsection
