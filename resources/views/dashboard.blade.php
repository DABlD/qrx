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

        {{-- 1ST CHART --}}
        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{-- TABS --}}
                            <ul class="nav nav-pills mb-3" role="tablist" id="c-nav">
                              <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="c1-t" data-toggle="pill" data-target="#c1" type="button" role="tab">
                                    Payments last 30 days
                                </button>
                              </li>
                              <li class="nav-item" role="presentation">
                                <button class="nav-link" id="c2-t" data-toggle="pill" data-target="#c2" type="button" role="tab">
                                    Loans last 30 days
                                </button>
                              </li>
                            </ul>
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                          <div class="tab-pane fade show active" id="c1" role="tabpanel">
                              <canvas id="sales" width="100%"></canvas>
                          </div>
                          <div class="tab-pane fade" id="c2" role="tabpanel">
                              <canvas id="types" width="100%"></canvas>
                          </div>
                        </div>
                    </div>
                </div>
            </section>
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

    <script>
        let c2loaded = false;

        $('.nav-link').on('click', e => {
            if(e.target.id == "c2-t" && c2loaded == false){
                loadC2();
            }
        });

        function loadC2(){
            var myChart2, ctx2;

            Swal.fire('Loading Data');
            swal.showLoading();

            $.ajax({
                url: '{{ route("report.types") }}',
                success: result =>{
                    result = JSON.parse(result);
                    ctx2 = document.getElementById('types').getContext('2d');
                    myChart2 = new Chart(ctx2, {
                        type: 'line',
                        data: {
                            labels: result.labels,
                            datasets: result.dataset
                        },
                        options: {
                            scales: {
                                y: {
                                    ticks: {
                                        stepSize: 1,
                                    },
                                    min: 0
                                }
                            }
                        }
                    });
                    swal.close();
                    c2loaded = true;
                }
            })
        }

        $(document).ready(() => {
            var myChart, ctx;

            Swal.fire('Loading Data');
            swal.showLoading();

            $.ajax({
                url: '{{ route("report.payments") }}',
                success: result =>{
                    result = JSON.parse(result);
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
                        },
                        options: {
                            scales: {
                                y: {
                                    min: 0
                                }
                            }
                        }
                    });
                    swal.close();
                }
            })
        });
    </script>
@endpush