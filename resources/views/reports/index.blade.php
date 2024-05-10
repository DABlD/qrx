@extends('layouts.app')
@section('content')

<section class="content">
    <div class="container-fluid">
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
                                {{-- FILTERS --}}
                                    <select id="pType">
                                        <option></option>
                                        <option value="%%">All</option>
                                    </select>

                                    <h3 class="float-right">
                                        <a class="btn btn-success btn-sm" onclick="print('sales')">
                                            PRINT
                                        </a>
                                    </h3>
                                {{-- CHART --}}
                                <canvas id="sales" width="100%"></canvas>
                          </div>
                          <div class="tab-pane fade" id="c2" role="tabpanel">
                                {{-- FILTERS --}}
                                    <select id="pType2">
                                        <option></option>
                                        <option value="%%">All</option>
                                    </select>

                                    <select id="pStatus">
                                        <option></option>
                                        <option value="%%">All</option>
                                        <option value="Applied">Applied</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Disapproved">Disapproved</option>
                                        <option value="For Payment">For Payment</option>
                                        <option value="Overdue">Overdue</option>
                                        <option value="Paid">Paid</option>
                                    </select>

                                    <h3 class="float-right">
                                        <a class="btn btn-success btn-sm" onclick="print('types')">
                                            PRINT
                                        </a>
                                    </h3>
                                {{-- CHART --}}
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
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

    <style>
        #c-nav .nav-link.active{
            color: #fff !important;
            background-color: #007bff !important;
            border: none !important;
        }

        #c-nav .nav-link{
            border: none !important;
        }

        /*.select2-selection__choice{
            color: #f76c6b !important;
            font-weight: bold;
        }*/
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>

    <script>
        let c2loaded = false;
        var myChart, ctx, myChart2, ctx2;

        // FILTERS
        var pType = "%%";

        var pType2 = "%%";
        var pStatus = "%%";

        $('.nav-link').on('click', e => {
            if(e.target.id == "c2-t" && c2loaded == false){
                loadC2();
            }
        });

        function loadC1(){
            Swal.fire('Loading Data');
            swal.showLoading();

            $.ajax({
                url: '{{ route("report.payments") }}',
                data: {
                    pType: pType
                },
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
        }

        function loadC2(){
            Swal.fire('Loading Data');
            swal.showLoading();

            $.ajax({
                url: '{{ route("report.types") }}',
                data: {
                    pType2: pType2,
                    pStatus: pStatus
                },
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
            // LOAD CHART
            loadC1();

            // FOR FILTERS

            // CHART 1
            $('#pType').select2({
                // multiple: true,
                width: '250px',
                placeholder: "Select Payment Channel"
            });

            $.ajax({
                url: '{{ route('transaction.get') }}',
                data: {
                    select: 'payment_channel'
                },
                success: result => {
                    result = JSON.parse(result);
                    let options = [];

                    result.forEach(option => {
                        options.push(option.payment_channel);
                    });

                    // REMOVE DUPLICATE
                    options = [...new Set(options)];

                    let tempString = "";
                    options.forEach(option => {
                        tempString += `
                            <option value="${option}">${option}</option>
                        `;
                    });

                    $('#pType').append(tempString);

                    $('#pType').change(() => {
                        pType = $('#pType').val();
                        myChart.destroy();
                        loadC1();
                    });
                }
            })

            // CHART 2

            $('#pType2').select2({
                // multiple: true,
                width: '250px',
                placeholder: "Select Loan Type"
            });

            $.ajax({
                url: '{{ route('loan.get') }}',
                data: {
                    select: 'type'
                },
                success: result => {
                    result = JSON.parse(result);
                    let options = [];

                    result.forEach(option => {
                        options.push(option.type);
                    });

                    // REMOVE DUPLICATE
                    options = [...new Set(options)];

                    let tempString = "";
                    options.forEach(option => {
                        tempString += `
                            <option value="${option}">${option}</option>
                        `;
                    });

                    $('#pType2').append(tempString);

                    $('#pType2').change(() => {
                        pType2 = $('#pType2').val();
                        myChart2.destroy();
                        loadC2();
                    });
                }
            })

            $('#pStatus').select2({
                width: '250px',
                placeholder: "Select Status"
            });

            $('#pStatus').change(() => {
                pStatus = $('#pStatus').val();
                myChart2.destroy();
                loadC2();
            });
        });

        function print(canvas){
            var canvas = document.getElementById(canvas);
            var win = window.open();
            win.document.write("<br><img src='" + canvas.toDataURL() + "'/>");
            setTimeout(() => {
                win.print();
                win.location.reload();
                win.close();
            },200);
        }
    </script>
@endpush