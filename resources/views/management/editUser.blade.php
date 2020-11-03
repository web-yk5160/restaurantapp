@extends("layouts.app")

@section('content')
<div class="container">
  <div class="row justify-content-center">
    @include('management.inc.sidebar')
    <div class="col-md-8">
      <i class="fas fa-users"></i> ユーザー編集
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
      <form action="/management/user/{{$user->id}}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="name">名前</label>
          <input type="text" name="name" value="{{$user->name}}" class="form-control" place-holder="名前">
        </div>
        <div class="form-group">
          <label for="email">メールアドレス</label>
          <input type="email" name="email" value="{{$user->email}}" class="form-control" place-holder="メールアドレス">
        </div>
        <div class="form-group">
          <label for="password">パスワード</label>
          <input type="password" name="password" class="form-control" place-holder="パスワード">
        </div>
        <div class="form-group">
          <label for="role">権限</label>
          <select name="role" class="form-control">
            <option value="admin" {{$user->role == "admin" ? 'selected': ''}}>管理者</option>
            <option value="admin" {{$user->role == "cashier" ? 'selected': ''}}>レジ係</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary">保存</button>
      </form>
    </div>
  </div>
</div>
@endsection