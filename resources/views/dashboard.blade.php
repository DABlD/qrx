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

        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-ticket mr-1"></i>
                            Payments for the last 30 days
                        </h3>
                    </div>

                    <div class="card-body">
                        <canvas id="sales" width="100%"></canvas>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>

@endsection

@push('scripts')
    <script src="{{ asset('js/chart.min.js') }}"></script>

    <script>
        $(document).ready(() => {
            var myChart, ctx;

            Swal.fire('Loading Data');
            swal.showLoading();

            $.ajax({
                url: '{{ route("report.payments") }}',
                success: result =>{
                    result = JSON.parse(result);
                    console.log(result,

                            [{
                              data: {
                                January: 10,
                                February: 20
                              }
                            }]);

                    ctx = document.getElementById('sales').getContext('2d');
                    myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: result.labels,
                            datasets: result.dataset

                            // datasets: [{
                            //   data: {
                            //     January: 10,
                            //     February: 20
                            //   }
                            // }]
                        }
                    });
                    swal.close();

                    console.log('test');
                }
            })
        });
    </script>
@endpush