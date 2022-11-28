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

                        @include('devices.includes.toolbar')
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>ID</th>
                    				<th>Vehicle ID</th>
                    				<th>Type</th>
                    				<th>Passenger Limit</th>
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
	<link rel="stylesheet" href="{{ asset('css/datatables.bundle.min.css') }}">
	{{-- <link rel="stylesheet" href="{{ asset('css/datatables.bootstrap4.min.css') }}"> --}}
	{{-- <link rel="stylesheet" href="{{ asset('css/datatables-jquery.min.css') }}"> --}}
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/datatables.bundle.min.js') }}"></script>
	{{-- <script src="{{ asset('js/datatables.bootstrap4.min.js') }}"></script> --}}
	{{-- <script src="{{ asset('js/datatables-jquery.min.js') }}"></script> --}}

	<script>
		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.vehicle') }}",
                	dataType: "json",
                	dataSrc: "",
					data: {
						select: "*",
					}
				},
				columns: [
					{data: 'id'},
					{data: 'vehicle_id'},
					{data: 'type'},
					{data: 'passenger_limit'},
					{data: 'actions'},
				],
        		pageLength: 25,
			});
		});

		function view(id){
			$.ajax({
				url: "{{ route('vehicle.get') }}",
				data: {
					select: '*',
					where: ['id', id],
				},
				success: vehicle => {
					vehicle = JSON.parse(vehicle)[0];
					showDetails(vehicle);
				}
			})
		}

		function create(){
			Swal.fire({
				html: `
	                ${input("vehicle_id", "Vehicle ID", null, 3, 9)}
					${input("type", "Type", null, 3, 9)}
					${input("passenger_limit", "Passenger Limit", null, 3, 9, 'number', 'min=0')}
				`,
				width: '800px',
				confirmButtonText: 'Add',
				showCancelButton: true,
				cancelButtonColor: errorColor,
				cancelButtonText: 'Cancel',
				preConfirm: () => {
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;

			            if($('.swal2-container input:placeholder-shown').length){
			                Swal.showValidationMessage('Fill all fields');
			            }
			            else{
			            	let bool = false;
            				$.ajax({
            					url: "{{ route('vehicle.get') }}",
            					data: {
            						select: "id",
            						where: ["vehicle_id", $("[name='vehicle_id']").val()]
            					},
            					success: result => {
            						result = JSON.parse(result);
            						if(result.length){
            			    			Swal.showValidationMessage('Vehicle ID already exists');
	            						setTimeout(() => {resolve()}, 500);
            						}
            					}
            				});
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					$.ajax({
						url: "{{ route('vehicle.store') }}",
						type: "POST",
						data: {
							vehicle_id: $("[name='vehicle_id']").val(),
							type: $("[name='type']").val(),
							passenger_limit: $("[name='passenger_limit']").val(),
							_token: $('meta[name="csrf-token"]').attr('content')
						},
						success: () => {
							ss("Success");
							reload();
						}
					})
				}
			});
		}

		function showDetails(vehicle){
			Swal.fire({
				html: `
	                ${input("id", "", vehicle.id, 3, 9, 'hidden')}
	                ${input("vehicle_id", "Vehicle", vehicle.vehicle_id, 3, 9)}
					${input("type", "Type", vehicle.type, 3, 9)}
					${input("passenger_limit", "Passenger Limit", vehicle.passenger_limit, 3, 9, 'number', 'min=0')}
	                <br>
				`,
				width: '800px',
				confirmButtonText: 'Update',
				showCancelButton: true,
				cancelButtonColor: errorColor,
				cancelButtonText: 'Cancel',
				preConfirm: () => {
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;

			            if($('.swal2-container input:placeholder-shown').length){
			                Swal.showValidationMessage('Fill all fields');
			            }
			            else{
			            	let bool = false;
            				$.ajax({
            					url: "{{ route('vehicle.get') }}",
            					data: {
            						select: "id",
            						where: ["vehicle_id", $("[name='vehicle_id']").val()]
            					},
            					success: result => {
            						result = JSON.parse(result);
            						if(result.length && result[0].id != vehicle.id){
            			    			Swal.showValidationMessage('Vehicle ID already exists');
	            						setTimeout(() => {resolve()}, 500);
            						}
            					}
            				});
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('vehicle.update') }}",
						data: {
							id: $("[name='id']").val(),
							vehicle_id: $("[name='vehicle_id']").val(),
							type: $("[name='type']").val(),
							passenger_limit: $("[name='passenger_limit']").val(),
						},
						message: "Success"
					},	() => {
						reload();
					});
				}
			});
		}

		function del(id){
			sc("Confirmation", "Are you sure you want to delete?", result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('vehicle.delete') }}",
						data: {id: id},
						message: "Success"
					}, () => {
						reload();
					})
				}
			});
		}
	</script>
@endpush