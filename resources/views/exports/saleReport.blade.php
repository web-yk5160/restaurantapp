<table class="table">
  <thead>
    <tr>
      <th>#</th>
      <th>レシートID</th>
      <th>日付</th>
      <th>テーブル</th>
      <th>スタッフ</th>
      <th>合計金額</th>
    </tr>
  </thead>
  <tbody>
    @php
    $countSale = 1;
    @endphp
    @foreach($sales as $sale)
    <tr>
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
    <tr>
      <td colspan="5">{{$dateStart}}から{{$dateEnd}}の合計数量</td>
      <td>{{number_format($totalSale)}}</td>
    </tr>
  </tbody>
</table>