@extends('layouts.app')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $clients }}</h3>
                        <p>Clients</p>
                    </div>

                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $loans }}</h3>
                        <p>Loans</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-route"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $payments }}</h3>
                        <p>Payments</p>
                    </div>

                    <div class="icon">
                        <i class="fa fa-mobile"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>₱{{ number_format($revenue, 2) }}</h3>
                        <p>Revenue</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-truck"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
    <style>
        #c-nav .nav-link.active{
            color: #fff !important;
            background-color: #007bff !important;
            border: none !important;
        }

        #c-nav .nav-link{
            border: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/chart.min.js') }}"></script>
@endpush