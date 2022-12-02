@extends('layouts.app')
@section('content')

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table mr-1"></i>
                            List
                        </h3>

                        @include('sales.includes.toolbar')
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>Name</th>
                    				<th>Ticket</th>
                    				<th>No</th>
                    				<th>Gender</th>
                    				<th>Mobile</th>
                    				<th>Origin</th>
                    				<th>Destination</th>
                    				<th>Vehicle</th>
                    				<th>Status</th>
                    				<th>Date Ticket Generated</th>
                    				<th>Date Embarked</th>
                    				<th>Date Disembarked</th>
                    			</tr>
                    		</thead>

                    		<tbody>
                    		</tbody>
                    	</table>
                    </div>
                </div>
            </section>
        </div>
    </div>

</section>

@endsection

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/datatables.bundle.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
	{{-- <link rel="stylesheet" href="{{ asset('css/datatables.bootstrap4.min.css') }}"> --}}
	{{-- <link rel="stylesheet" href="{{ asset('css/datatables-jquery.min.css') }}"> --}}
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/datatables.bundle.min.js') }}"></script>
	<script src="{{ asset('js/flatpickr.min.js') }}"></script>
	<script src="{{ asset('js/select2.min.js') }}"></script>
	{{-- <script src="{{ asset('js/datatables.bootstrap4.min.js') }}"></script> --}}
	{{-- <script src="{{ asset('js/datatables-jquery.min.js') }}"></script> --}}
	<script>
		let from = moment().subtract(6, 'days').format(dateFormat);
		let to = moment().format(dateFormat);
		let status = "%%";

		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.sale') }}",
                	dataType: "json",
                	dataSrc: "",
					data: f => {
						f.select = ["*"];
						f.load = ['origin', 'destination', 'vehicle'];
						f.from = from;
						f.to = to;
						f.status = status;
					}
				},
				columns: [
					{
						data: 'user.name',
						render: (name, b, sale) => {
							let link = "https://qr-transit.onehealthnetwork.com.ph";
							let style = "border-radius: 50%; width: 30px; height: 30px;";

							return `
								<img src="${link + sale.user.photo_url}" alt="img" style="${style}">
								${name}
							`;
						}
					},
					{data: 'ticket'},
					{data: 'ticket_no'},
					{data: 'user.gender'},
					{data: 'user.mobile_number'},
					{data: 'origin.name'},
					{data: 'destination.name'},
					{data: 'vehicle.vehicle_id'},
					{data: 'status'},
					{
						data: 'created_at',
						render: date => {
							return moment(date).format(dateTimeFormat2)
						}
					},
					{
						data: 'embarked_date',
						render: date => {
							return date ? moment(date).format(dateTimeFormat2) : "---";
						}
					},
					{
						data: 'updated_at',
						render: (date, a, sale) => {
							return date ? moment(date).format(dateTimeFormat2) : "---";
						}
					},
				],
        		pageLength: 25,
				// drawCallback: function(){
				// 	init();
				// }
			});

			$('#from').flatpickr({
                altInput: true,
                altFormat: 'F j, Y',
                dateFormat: 'Y-m-d',
                defaultDate: from
			});

			$('#to').flatpickr({
                altInput: true,
                altFormat: 'F j, Y',
                dateFormat: 'Y-m-d',
                defaultDate: to
			});

			$('#status').select2();

			$('#from').on('change', e => {
				from = e.target.value;
				reload();
			});

			$('#to').on('change', e => {
				to = e.target.value;
				reload();
			});

			$('#status').on('change', e => {
				status = e.target.value;
				reload();
			});
		});

		function exportToExcel(){
			let data = {
			    // from: from,
			    // to: to,
			    // fby: fby,
			    // type: type
			};

			window.open("{{ route('export.manifest') }}?" + $.param(data), "_blank");
		}
	</script>
@endpush