@extends("layouts.app")

@section('content')
<div class="container">
  <div class="row justify-content-center">
    @include('management.inc.sidebar')
    <div class="col-md-8">
      <i class="fas fa-hamburger"></i> メニュー
      <a href="/management/menu/create" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i>メニュー作成</a>
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
            <th scope="col">名前</th>
            <th scope="col">価格</th>
            <th scope="col">画像</th>
            <th scope="col">内容</th>
            <th scope="col">カテゴリー</th>
            <th scope="col">編集</th>
            <th scope="col">削除</th>
          </tr>
        </thead>
        <tbody>
          @foreach($menus as $menu)
          <tr>
            <td>{{ $menu->id }}</td>
            <td>{{ $menu->name }}</td>
            <td>{{ $menu->price }}</td>
            <td>
              <img src="{{ asset('menu_images')}}/{{ $menu->image }}" alt="{{ $menu->name }}" width="120px"
                height="120px" class="img-thumbnail">
            </td>
            <td>{{ $menu->description }}</td>
            <td>{{ $menu->category->name }}</td>
            <td><a href="/management/menu/{{$menu->id}}/edit" class="btn btn-warning">編集</a></td>
            <td>
              <form action="/management/menu/{{$menu->id}}" method="post">
                @csrf
                @method('DELETE')
                <input type="submit" value="削除" class="btn btn-danger">
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

    </div>
  </div>
</div>
@endsection