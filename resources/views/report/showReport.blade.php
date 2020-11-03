@extends("layouts.app")

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      @if($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/home">メイン機能</a></li>
          <li class="breadcrumb-item"><a href="/report">レポート</a></li>
          <li class="breadcrumb-item active" aria-current="page">結果</li>
        </ol>
      </nav>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      @if($sales->count() > 0)
      <div class="alert alert-success" role="alert">
        <p>{{$dateStart}}から{{$dateEnd}}の売上合計は{{number_format($totalSale)}}</p>
        <p>合計結果: {{$sales->total()}}</p>
      </div>
      <table class="table">
        <thead>
          <tr class="bg-primary text-right">
            <th scope="col">#</th>
            <th scope="col">レシートID</th>
            <th scope="col">日付</th>
            <th scope="col">テーブル</th>
            <th scope="col">スタッフ</th>
            <th scope="col">合計金額</th>
          </tr>
        </thead>
        <tbody>
          @php
          $countSale = ($sales->currentPage() - 1) * $sales->perPage() + 1;
          @endphp
          @foreach($sales as $sale)
          <tr class="bg-primary text-light">
            <td>{{$countSale++}}</td>
            <td>{{$sale->id}}</td>
            <td>{{date("m/d/Y H:i:s", strtotime($sale->updated_at))}}</td>
            <td>{{$sale->table_name}}</td>
            <td>{{$sale->user_name}}</td>
            <td>{{$sale->total_price}}</td>
          </tr>
          <tr>
            <th></th>
            <th>メニューID</th>
            <th>メニュー</th>
            <th>数量</th>
            <th>価格</th>
            <th>合計金額</th>
          </tr>
          @foreach($sale->saleDetails as $saleDetail)
          <tr>
            <td></td>
            <td>{{$saleDetail->menu_id}}</td>
            <td>{{$saleDetail->menu_name}}</td>
            <td>{{$saleDetail->quantity}}</td>
            <td>{{$saleDetail->menu_price}}</td>
            <td>{{$saleDetail->menu_price * $saleDetail->quantity}}</td>
          </tr>
          @endforeach
          @endforeach
        </tbody>
      </table>

      {{ $sales->appends($_GET)->links() }}

      <form action="/report/show/export" method="GET">
        <input type="hidden" name="dateStart" value="{{$dateStart}}">
        <input type="hidden" name="dateEnd" value="{{$dateEnd}}">
        <input type="submit" class="btn btn-warning" value="エクセルに出力">
      </form>

      @else
      <div class="alert alert-danger" role="alert">
        売上レポートはありません
      </div>
      @endif
    </div>
  </div>
</div>
</div>
@endsection