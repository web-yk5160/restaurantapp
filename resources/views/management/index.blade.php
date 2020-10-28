@extends("layouts.app")

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="list-group">
          <a href="/management/category" class="list-group-item list-group-item-action"><i class="fas fa-align-justify"></i> カテゴリー</a>
          <a class="list-group-item list-group-item-action"><i class="fas fa-hamburger"></i> メニュー</a>
          <a class="list-group-item list-group-item-action"><i class="fas fa-chair"></i> テーブル</a>
          <a class="list-group-item list-group-item-action"><i class="fas fa-users-cog"></i> ユーザー</a>
        </div>
      </div>
      <div class="col-md-8">
      </div>
    </div>
  </div>
@endsection
