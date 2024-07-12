@extends('admin.app')
@section('title')
    Payment Gateway
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Payment Gateway</h4>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                <br>
            @endif
            <!-- Examples -->

            <div class="row mb-5">
                <div class="col-md-12 col-lg-12 mb-3">
                    <form action="{{ route('admin.payment.gateway') }}" method="POST">
                        @csrf
                        <div class="card mb-4">
                            <h5 class="card-header">Payment Gateway Setup</h5>
                            <div class="card-body">
                                <div class="mb-3 row">
                                    <label for="html5-text-input" class="col-md-2 col-form-label">Paypal Client ID</label>
                                    <div class="col-md-10">
                                        <input class="form-control" name="client_id" type="text" value="{{ $paymentSetting->client_id ?? old('client_id') }}" id="html5-text-input" />
                                        @if($errors->has('client_id'))
                                            <div class="text-danger">{{ $errors->first('client_id') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="html5-search-input" class="col-md-2 col-form-label">Paypal Client Secret</label>
                                    <div class="col-md-10">
                                        <input class="form-control" name="client_secret" type="text" value="{{ $paymentSetting->client_id ?? old('client_secret') }}" id="html5-search-input" />
                                        @if($errors->has('client_secret'))
                                            <div class="text-danger">{{ $errors->first('client_secret') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="html5-email-input" class="col-md-2 col-form-label">Mode</label>
                                    <div class="col-md-10">
                                        <select class="form-control" name="mode">
                                            <option value="sandbox" {{ $paymentSetting->mode == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                            <option value="live" {{ $paymentSetting->mode == 'live' ? 'selected' : '' }}>Live</option>
                                        </select>
                                        @if($errors->has('mode'))
                                            <div class="text-danger">{{ $errors->first('mode') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Examples -->

        <!-- / Content -->

        <div class="content-backdrop fade"></div>
    </div>
@endsection
