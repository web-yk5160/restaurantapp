@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row" id="table-detail"></div>
  <div class="row justify-content-center">
    <div class="col-md-5">
      <button class="btn btn-primary btn-block" id="btn-show-tables">テーブル表示</button>
      <div id="selected-table"></div>
      <div id="order-detail"></div>
    </div>
    <div class="col-md-7">
      <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist ">
          @foreach($categories as $category)
          <a class="nav-item nav-link" data-id="{{$category->id}}" data-toggle="tab">
            {{ $category->name }}
          </a>
          @endforeach
        </div>
      </nav>
      <div id="list-menu" class="row mt-2"></div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">お支払い</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h3 class="totalAmount"></h3>
        <h3 class="changeAmount"></h3>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">¥</span>
          </div>
          <input type="number" id="received-amount" class="form-control">
        </div>
        <div class="form-group">
          <label for="payment">お支払い方法</label>
          <select class="form-control" id="payment-type">
            <option value="現金">現金</option>
            <option value="クレジットカード">クレジットカード</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-save-payment" disabled>お支払い</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // テーブルを隠す
  $("#table-detail").hide();

  // ボタンを押下後テーブル表示
  $("#btn-show-tables").click(function() {
    if ($("#table-detail").is(":hidden")) {
      $.get("/cashier/getTable", function(data) {
        $("#table-detail").html(data);
        $("#table-detail").slideDown('fast');
        $("#btn-show-tables").html('閉じる').removeClass('btn-primary').addClass('btn-danger');
      })
    } else {
      $("#table-detail").slideUp('fast');
      $("#btn-show-tables").html('テーブルを表示').removeClass('btn-danger').addClass('btn-primary');
    }
  });

  // カテゴリーごとのメニューの読み込み
  $(".nav-link").click(function() {
    $.get("/cashier/getMenuByCategory/" +
      $(this).data("id"),
      function(data) {
        $("#list-menu").hide();
        $("#list-menu").html(data);
        $("#list-menu").fadeIn('fast');
      });
  })

  var SELECTED_TABLE_ID = "";
  var SELECTED_TABLE_NAME = "";
  var SALE_ID = "";
  // ボタンがクリックされたらテーブル情報を表示
  $("#table-detail").on('click', ".btn-table", function() {
    SELECTED_TABLE_ID = $(this).data("id");
    SELECTED_TABLE_NAME = $(this).data("name");
    $("#selected-table").html('<br><h3>Table: ' + SELECTED_TABLE_NAME +
      '</h3><hr>');
    $.get('/cashier/getSaleDetailsByTable/' + SELECTED_TABLE_ID, function(data) {
      $("#order-detail").html(data);
    });
  });

  $("#list-menu").on('click', '.btn-menu', function() {
    if (SELECTED_TABLE_ID == "") {
      alert('お客さんのテーブルをはじめに選択してください');
    } else {
      var menu_id = $(this).data("id");
      $.ajax({
        type: "POST",
        data: {
          "_token": $('meta[name="csrf-token"]').attr('content'),
          "menu_id": menu_id,
          "table_id": SELECTED_TABLE_ID,
          "table_name": SELECTED_TABLE_NAME,
          "quantity": 1
        },
        url: "/cashier/orderFood",
        success: function(data) {
          $("#order-detail").html(data);
        }
      });
    }
  });

  $("#order-detail").on('click', ".btn-confirm-order", function() {
    var SaleId = $(this).data("id");
    $.ajax({
      type: "POST",
      data: {
        "_token": $('meta[name="csrf-token"]').attr('content'),
        "sale_id": SaleId
      },
      url: "/cashier/confirmOrderStatus",
      success: function(data) {
        $("#order-detail").html(data);
      }
    });
  });

  // 売上詳細の削除
  $("#order-detail").on('click', '.btn-delete-saledetail', function() {
    var saleDetailId = $(this).data("id");
    $.ajax({
      type: "POST",
      data: {
        "_token": $('meta[name="csrf-token"]').attr('content'),
        "saleDetail_id": saleDetailId
      },
      url: "/cashier/deleteSaleDetail",
      success: function(data) {
        $("#order-detail").html(data);
      }
    })
  });

  // 数量の追加
  $("#order-detail").on('click', '.btn-increase-quantity', function() {
    var saleDetailId = $(this).data("id");
    $.ajax({
      type: "POST",
      data: {
        "_token": $('meta[name="csrf-token"]').attr('content'),
        "saleDetail_id": saleDetailId
      },
      url: "/cashier/increase-quantity",
      success: function(data) {
        $("#order-detail").html(data);
      }
    })
  });

  // 数量の削除
  $("#order-detail").on('click', '.btn-decrease-quantity', function() {
    var saleDetailId = $(this).data("id");
    $.ajax({
      type: "POST",
      data: {
        "_token": $('meta[name="csrf-token"]').attr('content'),
        "saleDetail_id": saleDetailId
      },
      url: "/cashier/decrease-quantity",
      success: function(data) {
        $("#order-detail").html(data);
      }
    })
  });

  // お支払いボタンをクリック
  $("#order-detail").on('click', '.btn-payment', function() {
    var totalAmount = $(this).attr('data-totalAmount');
    $(".totalAmount").html("合計金額 " + totalAmount + "円");
    $("#received-amount").val('');
    $(".changeAmount").html('');
    SALE_ID = $(this).data("id");
  });

  // お釣りの計算
  $("#received-amount").keyup(function() {
    var totalAmount = $(".btn-payment").attr('data-totalAmount');
    var receivedAmount = $(this).val();
    var changeAmount = receivedAmount - totalAmount;
    $(".changeAmount").html("お返し金額 " + changeAmount + "円");

    // ユーザーが合計金額以上を入力後にボタンをクリックできる。それ以外はボタンを押せない
    if (changeAmount >= 0) {
      $('.btn-save-payment').prop('disabled', false);
    } else {
      $('.btn-save-payment').prop('disabled', true);
    }
  });

  // お支払いの保存
  $(".btn-save-payment").click(function() {
    var receivedAmount = $("#received-amount").val();
    var paymentType = $("#payment-type").val();
    var saleId = SALE_ID;
    // alert(saleId);
    $.ajax({
      type: "POST",
      data: {
        "_token": $('meta[name="csrf-token"]').attr('content'),
        "saleID": saleId,
        "receivedAmount": receivedAmount,
        "paymentType": paymentType
      },
      url: "/cashier/savePayment",
      success: function(data) {
        window.location.href = data
      }
    });
  });

});
</script>
@endsection