@extends('app')
@section('title')
    Home
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                <br>
            @endif
            <!-- Examples -->
            <div class="row mb-5">
                @foreach($products as $product)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100">
                            <img class="card-img-top" src="{{ asset('assets/img/elements/'.$product->image) }}" alt="Card image cap" />
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">
                                    {{ $product->description }}
                                </p>
                                <a href="{{ route('checkout', ['id' => $product->id]) }}" class="btn btn-outline-primary"><i class="tf-icons bx bx-cart-alt me-1"></i>Buy Now</a> <span style="float: right; font-size: 20px; font-weight: 700;">${{ $product->price }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- Examples -->

        <!-- / Content -->

        <div class="content-backdrop fade"></div>
    </div>
@endsection
