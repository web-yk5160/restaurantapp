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
        <i class="fas fa-align-justify"></i> カテゴリー
        <a href="/management/category/create" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i>カテゴリー作成</a>
        <hr>
        @if(Session()->has('status'))
          <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">X</button>
            {{ Session()->get('status') }}
          </div>
        @endif
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">カテゴリー</th>
              <th scope="col">編集</th>
              <th scope="col">削除　</th>
            </tr>
          </thead>
          <tbody>
            @foreach($categories as $category)
              <tr>
                <th scope="row">{{ $category->id  }}</th>
                <td>{{ $category->name }}</td>
                <td>
                  <a href="/management/category/{{$category->id}}/edit" class="btn btn-warning">編集</a>
                </td>
                <td>
                  <form action="/management/category/{{$category->id}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="削除" class="btn btn-danger">
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $categories->links() }}
      </div>
    </div>
  </div>
@endsection
