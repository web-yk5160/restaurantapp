@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">メイン機能</div>

        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif

          <div class="row text-center">
            @if(Auth::user()->checkAdmin())
            <div class="col-sm-4">
              <a href="/management">
                <h4>マネジメント</h4>
                <img width="50px" src="{{ asset('images/management.svg') }}" />
              </a>
            </div>
            @endif
            <div class="col-sm-4">
              <a href="/cashier">
                <h4>レジ / 会計</h4>
                <img width="50px" src="{{ asset('images/cashier.svg') }}" />
              </a>
            </div>
            @if(Auth::user()->checkAdmin())
            <div class="col-sm-4">
              <a href="/report">
                <h4>レポート</h4>
                <img width="50px" src="{{ asset('images/report.svg') }}" />
              </a>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection