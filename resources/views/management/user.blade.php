@extends("layouts.app")

@section('content')
<div class="container">
  <div class="row justify-content-center">
    @include('management.inc.sidebar')
    <div class="col-md-8">
      <i class="fas fa-users"></i> ユーザー
      <a href="/management/user/create" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i>ユーザー作成</a>
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
            <th scope="col">権限</th>
            <th scope="col">メール</th>
            <th scope="col">編集</th>
            <th scope="col">削除</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->role }}</td>
            <td>{{ $user->email }}</td>
            <td><a href="/management/user/{{$user->id}}/edit" class="btn btn-warning">編集</a></td>
            <td>
              <form action="/management/user/{{$user->id}}" method="post">
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