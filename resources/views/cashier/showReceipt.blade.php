<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restaurant App - レシート - 売上ID : {{$sale->id}}</title>
  <link rel="stylesheet" type="text/css" href="{{asset('/css/receipt.css')}}" media="all">
  <link rel="stylesheet" type="text/css" href="{{asset('/css/no-print.css')}}" media="print">
</head>

<body>
  <div id="wrapper">
    <div id="receipt-header">
      <h3 id="restaurant-name">Restaurant App</h3>
      <p>住所: </p>
      <p>電話番号: </p>
      <p>領収書: <strong>{{$sale->id}}</strong></p>
    </div>
    <div id="receipt-body">
      <table class="tb-sale-detail">
        <thead>
          <tr>
            <th>#</th>
            <th>メニュー</th>
            <th>数量</th>
            <th>価格</th>
            <th>合計</th>
          </tr>
        </thead>
        <tbody>
          @foreach($saleDetails as $saleDetail)
          <tr>
            <td width="30">{{$saleDetail->menu_id}}</td>
            <td width="180">{{$saleDetail->menu_name}}</td>
            <td width="50">{{$saleDetail->quantity}}</td>
            <td width="55">{{$saleDetail->menu_price}}</td>
            <td width="65">{{$saleDetail->menu_price * $saleDetail->quantity}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <table class="tb-sale-total">
        <tbody>
          <tr>
            <td>合計数量</td>
            <td>{{$saleDetails->count()}}</td>
            <td>合計金額</td>
            <td>{{number_format($sale->total_price)}}</td>
          </tr>
          <tr>
            <td colspan="2">お支払い方法</td>
            <td colspan="2">{{$sale->payment_type}}</td>
          </tr>
          <tr>
            <td colspan="2">お預かり金額</td>
            <td colspan="2">{{$sale->total_received}}</td>
          </tr>
          <tr>
            <td colspan="2">お釣り</td>
            <td colspan="2">{{$sale->change}}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div id="receipt-footer">ありがとうございます！</div>
    <div id="buttons">
      <a href="/cashier">
        <button class="btn btn-back">
          お支払いに戻る
        </button>
      </a>
      <button class="btn btn-print" type="button" onclick="window.print(); return false;">
        印刷
      </button>
    </div>
  </div>
</body>

</html>