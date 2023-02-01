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
                    				<th>cid</th>
                    				<th>ID</th>
                    				<th>Ticket</th>
                    				<th>No</th>
                    				<th>Amount</th>
                    				{{-- <th>Status</th> --}}
                    				<th>Origin</th>
                    				<th>Destination</th>
                    				<th>Date Created</th>
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
		let device = "%%";

		$(document).ready(()=> {
			$.ajax({
				url: '{{ route('device.get') }}',
				data: {
					select: "*",
					where: ['status', 'Active']
				},
				success: result => {
					result = JSON.parse(result);
					
					devices = "";
					result.forEach(temp => {
						devices += `
							<option value="${temp.device_id}">${temp.description} - ${temp.device_id}</option>
						`;
					});

					$('#device').append(devices);
				}
			});

			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.sale') }}",
                	dataType: "json",
                	dataSrc: "",
					data: f => {
						f.select = ["*"];
						f.load = ['origin', 'destination', 'company'];
						f.from = from;
						f.to = to;
						f.status = status;
						f.device = device + "%";
						@if(auth()->user()->role == "Company")
							f.where = ['company_id', {{ auth()->user()->id }}];
						@endif
					}
				},
				columns: [
					{data: 'di', visible: false},
					{data: 'id'},
					{data: 'ticket'},
					{data: 'ticket_no'},
					{data: 'amount'},
					// {data: 'status'},
					{data: 'origin.name'},
					{data: 'destination.name'},
					{
						data: 'created_at',
						render: date => {
							return moment(date).format(dateTimeFormat2)
						}
					},
				],
        		pageLength: 25,
		        drawCallback: function (settings) {
		            let api = this.api();
		            let rows = api.rows({ page: 'current' }).nodes();
		            let last = null;

		            let ids = [];
		 
		            api.column(0, { page: 'current' })
		                .data()
		                .each(function (company, i, row) {
		                    if (last !== company) {
		                        $(rows)
		                            .eq(i)
		                            .before(`
		                            	<tr class="group">
		                            		<td colspan="7">
		                            			${company} (${row.length})
		                            		</td>
		                            	</tr>
		                            `);
		 
		                        last = company;
		                    }
		                });

		        	let grps = $('[class="group"]');
		        	grps.each((index, group) => {
		        		if(!$(group).next().is(':visible')){
		        			$(group).remove();
		        		}
		        	});
		        },
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
			$('#device').select2();

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

			$('#device').on('change', e => {
				device = e.target.value;
				reload();
			});
		});

		function exportToExcel(){
			let data = {
			    from: from,
			    to: to,
			    status: status,
			    device: device + "%"
			};

			window.open("{{ route('export.sales') }}?" + $.param(data), "_blank");
		}
	</script>
@endpush