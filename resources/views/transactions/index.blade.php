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

                        @include('transactions.includes.toolbar')
                    </div>

                    <div class="card-body table-responsive">
                    	{{-- FILTERS --}}
                    	<select id="fType">
                    	    <option></option>
                    	    <option value="%%">All</option>
                    	    <option value="DR">DR</option>
                    	    <option value="CR">CR</option>
                    	</select>

                    	<select id="fChannel">
                    	    <option></option>
                    	    <option value="%%">All</option>
                    	</select>

                        <h3 class="float-right">
                            <a class="btn btn-success btn-sm" onclick="exporto()">
        						<i class="fas fa-file-export"> Export</i>
                            </a>
                        </h3>

                        <br>
                        <br>

                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>ID</th>
                    				<th>Contract #</th>
                    				<th>Type</th>
                    				<th>Amount</th>
                    				<th>Ref #</th>
                    				<th>Payment Channel</th>
                    				<th>Date</th>
                    				<th>Actions</th>
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
	<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
	<style>
		.center{
			text-align: center;
		}
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/select2.min.js') }}"></script>
	<script src="{{ asset('js/numeral.min.js') }}"></script>

	<script>
		var fType = "%%";
		var fChannel = "%%";

		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.transactions') }}",
                	dataType: "json",
                	dataSrc: "",
					data: f => {
						f.select = "*";
						f.load = ['loan'];
						f.filters = getFilters();
					}
				},
				columns: [
					{data: 'id'},
					{data: 'type'},
					{data: 'type'},
					{data: 'amount'},
					{data: 'trx_number'},
					{data: 'payment_channel'},
					{data: 'payment_date'},
					{data: 'actions'},
				],
        		pageLength: 25,
				columnDefs: [
					{
						targets: [0,1,2,3,4,5,6,7],
						className: "center"
					},
					{
						targets: 1,
						render: (a,b,data) => {
							if(data.loan){
								return data.loan.contract_no;
							}

							return "";
						}
					},
					{
						targets: 3,
						render: amount => {
							return "₱" + numeral(amount).format("0,0.00");
						}
					},
					{
						targets: 6,
						render: date => {
							return moment(date).format(dateTimeFormat2);
						}
					},
				]
				// drawCallback: function(){
				// 	init();
				// }
			});

			// FILTERS
			// FTYPE
			$('#fChannel').select2({
			    width: '300px',
			    placeholder: "Select Client"
			});

			$.ajax({
			    url: '{{ route('transaction.get') }}',
			    data: {
			        select: ['id', 'payment_channel'],
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
			        options.forEach(trx => {
			            tempString += `
			                <option value="${trx}">${trx}</option>
			            `;
			        });

			        $('#fChannel').append(tempString);

			        $('#fChannel').change(() => {
			            fChannel = $('#fChannel').val();
			            reload();
			        });
			    }
			})

			// FTYPE
			$('#fType').select2({
			    width: '150px',
			    placeholder: "Select Type"
			});

	        $('#fType').change(() => {
	            fType = $('#fType').val();
	            reload();
	        });
		});

		function getFilters(){
			return {
				fType: fType,
				fChannel: fChannel
			};
		}

		function exporto(){
			let data = {
				select: "*",
				load: ['loan'],
				filters: getFilters()
			};

			window.open("/export/transactions?" + $.param(data), "_blank");
		}
	</script>
@endpush