<?php

namespace App\Http\Controllers\Cashier;

use App\Menu;
use App\Sale;
use App\Table;
use App\Category;
use App\SaleDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('cashier.index')->with('categories', $categories);
    }

    public function getTables()
    {
        $tables = Table::all();
        $html = '';
        foreach($tables as $table) {
            $html .= '<div class="col-md-2 mb-4">';
            $html .=
            '<button class="btn btn-primary btn-table" data-id="'.$table->id.'" data-name="'.$table->name.'">
            <img class="img-fluid" src="'.url('/images/table.svg').'"/>
            <br>';
            if($table->status == '空') {
                $html .= '<span class="badge badge-success">'.$table->name.'</span>';
            } else { // テーブルが空ではない
                $html .= '<span class="badge badge-danger">'.$table->name.'</span>';
            }
            $html .= '</button>';
            $html .= '</div>';
        }
        return $html;
    }

    public function getMenuByCategory($category_id)
    {
        $menus = Menu::where('category_id', $category_id)->get();
        $html = '';
        foreach($menus as $menu) {
            $html .= '
            <div class="col-md-3 text-center">
                <a class="btn btn-outline-secondary btn-menu" data-id="'.$menu->id.'">
                    <img class="img-fluid" src="'.url('/menu_images/'.$menu->image).'">
                    <br>
                    '.$menu->name.'
                    <br>
                    '.number_format($menu->price).'円
                </a>
            </div>
            ';
        }
        return $html;
    }

    public function orderFood(Request $request)
    {
        $user = Auth::user();
        $menu = Menu::find($request->menu_id);
        $table_id = $request->table_id;
        $table_name = $request->table_name;
        $sale = Sale::where('table_id', $table_id)->where('sale_status', '未払い')->first();
        // 選択されたテーブルにSaleがない時Saleを作成
        if (!$sale) {
            $sale = new Sale();
            $sale->table_id = $table_id;
            $sale->table_name = $table_name;
            $sale->user_id = $user->id;
            $sale->user_name = $user->name;
            $sale->save();
            $sale_id = $sale->id;
            // テーブルstatusを更新
            $table = Table::find($table_id);
            $table->status = "満";
            $table->save();
        } else {
            // 選択されたテーブルにSaleがあれば
            $sale_id = $sale->id;
        }

        // saleDetailsテーブルにオーダーされたメニューを追加
        $saleDetail = new SaleDetail;
        $saleDetail->sale_id =$sale_id;
        $saleDetail->menu_id = $menu->id;
        $saleDetail->menu_name = $menu->name;
        $saleDetail->menu_price = $menu->price;
        $saleDetail->quantity = $request->quantity;
        $saleDetail->save();

        // salesテーブルのtotal priceを更新
        $sale->total_price = $sale->total_price + ($request->quantity * $menu->price);
        $sale->save();

        $html = $this->getSaleDetails($sale_id);
        return $html;
    }

    public function getSaleDetailsByTable($table_id)
    {
        $sale = Sale::where('table_id', $table_id)->where('sale_status', '未払い')->first();
        $html = '';
        if($sale) {
            $sale_id = $sale->id;
            $html .= $this->getSaleDetails($sale_id);
        } else {
            $html .= '選択されたテーブルに売上詳細情報がありません';
        }
        return $html;
    }

    private function getSaleDetails($sale_id)
    {
        // 全てのSaleDetailを表示
        $html = '<p>セールID:'. $sale_id .'</p>';
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->get();
        $html .= '<div class="table-responsive-md" style="overflow-y:scroll;
        height: 400px; border: 1px solid #343A40;">
        <table class="table table-stripped table-dark">
            <thead>
                <tr>
                    <th class="col">ID</th>
                    <th class="col">Menu</th>
                    <th class="col">Quantity</th>
                    <th class="col">Price</th>
                    <th class="col">Total</th>
                    <th class="col">Status</th>
                </tr>
            </thead>
            <tbody>';
        $showBtnPayment = true;
        foreach($saleDetails as $saleDetail) {

            $html .= '
            <tr>
                <td>'.$saleDetail->menu_id.'</td>
                <td>'.$saleDetail->menu_name.'</td>
                <td>'.$saleDetail->quantity.'</td>
                <td>'.$saleDetail->menu_price.'</td>
                <td>'.($saleDetail->menu_price * $saleDetail->quantity).'</td>';
                if ($saleDetail->status == '未確認') {
                    $showBtnPayment = false;
                    $html .= '<td><a data-id="'.$saleDetail->id.'" class="btn btn-danger btn-delete-saledetail"><i class="far fa-trash-alt"></i></a></td>';
                } else { // status == "確認"
                    $html .= '<td><i class="fas fa-check-circle"></i></td>';
                }

            $html .= '</tr>';
        }
        $html .= '</tbody></table></div>';

        $sale = Sale::find($sale_id);
        $html .= '<hr>';
        $html .= '<h3>合計金額: '.number_format($sale->total_price).'円</h3>';

        if ($showBtnPayment) {
            $html .= '<button data-id="'.$sale->id.'" data-totalAmount="'.$sale->total_price.'" class="btn btn-success btn-block btn-payment" data-toggle="modal" data-target="#exampleModal">お支払い</button>';
        } else {
            $html .= '<button data-id="'.$sale->id.'" class="btn btn-warning btn-block btn-confirm-order">注文の確認</button>';
        }
        return $html;
    }

    public function confirmOrderStatus(Request $request)
    {
        $sale_id = $request->sale_id;
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->update(['status' => '確認']);
        $html = $this->getSaleDetails($sale_id);
        return $html;
    }

    public function deleteSaleDetail(Request $request)
    {
        $saleDetail_id = $request->saleDetail_id;
        $saleDetail = SaleDetail::find($saleDetail_id);
        $sale_id = $saleDetail->sale_id;
        $menu_price = ($saleDetail->menu_price * $saleDetail->quantity);
        $saleDetail->delete();
        // 合計金額の更新
        $sale = Sale::find($sale_id);
        $sale->total_price = $sale->total_price - $menu_price;
        $sale->save();

        // sale idを持つ売上詳細があるかチェック
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->first();
        if($saleDetail){
            $html = $this->getSaleDetails($sale_id);
        } else {
            $html = '選択されたテーブルに売上詳細情報がありません';
        }
        return $html;
    }

    public function savePayment(Request $request)
    {
        $saleID = $request->saleID;
        $receivedAmount = $request->receivedAmount;
        $paymentType = $request->paymentType;
        // salesテーブルの売上の更新
        $sale = Sale::find($saleID);
        $sale->total_received = $receivedAmount;
        $sale->change = $receivedAmount - $sale->total_price;
        $sale->payment_type = $paymentType;
        $sale->sale_status = "支払済";
        $sale->save();
        // tablesテーブルの更新
        $table = Table::find($sale->table_id);
        $table->status = "空";
        $table->save();
        return "/cashier/showReceipt/".$saleID;
    }

    public function showReceipt($saleID)
    {
        $sale = Sale::find($saleID);
        $saleDetails = SaleDetail::where('sale_id', $saleID)->get();
        return view('cashier.showReceipt')->with('sale', $sale)->with('saleDetails', $saleDetails);
    }
}