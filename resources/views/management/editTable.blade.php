@extends("layouts.app")

@section('content')
<div class="container">
  <div class="row justify-content-center">
    @include('management.inc.sidebar')
    <div class="col-md-8">
      <i class="fas fa-chair"></i> テーブル編集
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
      <form action="/management/table/{{$table->id}}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="tableName">テーブル名</label>
          <input type="text" name="name" class="form-control" value="{{$table->name}}" place-holder="テーブル">
        </div>
        <button type="submit" class="btn btn-warning">更新　</button>
      </form>
    </div>
  </div>
</div>
@endsection