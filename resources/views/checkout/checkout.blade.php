@extends('app')
@section('title')
    Checkout
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Checkout</h4>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                <br>
            @endif
            <!-- Examples -->
            <div class="row mb-5">
                <div class="col-md-12 col-lg-12 mb-3">
                    <div class="card mb-4">
                        <h5 class="card-header">Item Info</h5>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">Item Name</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="{{ $product->name }}" id="html5-text-input" readonly />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-search-input" class="col-md-2 col-form-label">Amount</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="${{ $product->price }}" id="html5-search-input" readonly />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-email-input" class="col-md-2 col-form-label">Total Payable</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="${{ $product->price }}" id="html5-email-input" readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('checkout', ['id' => $product->id]) }}">
                        @csrf
                        <div class="card mb-4">
                            <h5 class="card-header">Billing Info</h5>
                            <div class="card-body">
                                <div class="mb-3 row">
                                    <label for="html5-url-input" class="col-md-2 col-form-label">Full Name</label>
                                    <div class="col-md-10">
                                        <input
                                            class="form-control"
                                            type="text"
                                            value="{{ old('name') }}"
                                            id="html5-url-input"
                                            name="name"
                                        />
                                        @if($errors->has('name'))
                                            <div class="text-danger">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="html5-tel-input" class="col-md-2 col-form-label">Email</label>
                                    <div class="col-md-10">
                                        <input class="form-control" name="email" type="email" value="{{ old('email') ?? auth()->user()->email }}" id="html5-tel-input" />
                                        @if($errors->has('email'))
                                            <div class="text-danger">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="html5-tel-input" class="col-md-2 col-form-label">Phone</label>
                                    <div class="col-md-10">
                                        <input class="form-control" name="phone" type="tel" value="{{ old('phone') ?? auth()->user()->phone }}" id="html5-tel-input" />
                                        @if($errors->has('phone'))
                                            <div class="text-danger">{{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="html5-password-input" class="col-md-2 col-form-label">Full Address</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="address" id="html5-password-input">{{ old('address') }}</textarea>
                                        @if($errors->has('address'))
                                            <div class="text-danger">{{ $errors->first('address') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <button type="submit" class="btn btn-primary"><i class="tf-icons bx bx-cart-alt me-1"></i>Buy Now</button>
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
