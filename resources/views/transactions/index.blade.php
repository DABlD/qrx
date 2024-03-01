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
		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.transactions') }}",
                	dataType: "json",
                	dataSrc: "",
					data: {
						select: "*",
						load: ['loan']
					}
				},
				columns: [
					{data: 'id'},
					{data: 'type'},
					{data: 'type'},
					{data: 'amount'},
					{data: 'trx_number'},
					{data: 'payment_channel'},
					{data: 'date'},
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
		});
	</script>
@endpush