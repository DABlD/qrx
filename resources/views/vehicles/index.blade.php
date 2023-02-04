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
                    				<th>cid</th>
                    				<th>ID</th>
                    				<th>Vehicle ID</th>
                    				<th>Type</th>
                    				<th>Passenger Limit</th>
                    				<th>Driver</th>
                    				<th>Conductor</th>
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
	<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

	<style>
		td{
			text-align: center;
		}

		th{
			text-align: center !important;
		}

		.ta{
			text-align: left;
		}
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/datatables.bundle.min.js') }}"></script>
	<script src="{{ asset('js/select2.min.js') }}"></script>

	<script>
		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.vehicle') }}",
                	dataType: "json",
                	dataSrc: "",
					data: {
						select: "*",
						load: ['company'],
						@if(auth()->user()->role == "Company")
							where: ['company_id', {{ auth()->user()->id }}]
						@endif
					}
				},
				columns: [
					{data: 'company.fname', visible: false},
					{data: 'id'},
					{data: 'vehicle_id'},
					{data: 'type'},
					{data: 'passenger_limit'},
					{
						data: 'driver',
						render: driver => {
							return driver != null ? driver : "-";
						}
					},
					{
						data: 'conductor',
						render: conductor => {
							return conductor != null ? conductor : "-";
						}
					},
					{data: 'actions'},
				],
        		pageLength: 25,
		        drawCallback: function (settings) {
		            let api = this.api();
		            let rows = api.rows({ page: 'current' }).nodes();
		            let last = null;
		 
		            api.column(0, { page: 'current' })
		                .data()
		                .each(function (company, i, row) {
		                    if (last !== company) {
		                        $(rows)
		                            .eq(i)
		                            .before(`
		                            	<tr class="group">
		                            		<td colspan="7" class="ta">
		                            			${company}
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
					@if(auth()->user()->role == 'Admin')
						<div class="row iRow">
						    <div class="col-md-3 iLabel">
						        Company
						    </div>
						    <div class="col-md-9 iInput">
						        <select name="company_id" id="company_id" class="form-control">
						        	<option value="">Select Company</option>
						        </select>
						    </div>
						</div>
					@endif
					
	                ${input("vehicle_id", "Vehicle ID", null, 3, 9)}
					
					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Route
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="route_id" id="route_id" class="form-control">
					        	<option value="">Select Route</option>
					        </select>
					    </div>
					</div>
					
					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Type
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="type" id="type" class="form-control">
					        	<option value="">Select Type</option>
					        	<option value="Car">Car</option>
					        	<option value="Bus">Bus</option>
					        	<option value="Trycicle">Trycicle</option>
					        	<option value="Taxi">Taxi</option>
					        	<option value="Ferry">Ferry</option>
					        	<option value="Train">Train</option>
					        </select>
					    </div>
					</div>

					${input("passenger_limit", "Passenger Limit", null, 3, 9, 'number', 'min=0')}
					${input("driver", "Driver", null, 3, 9)}
					${input("conductor", "Conductor", null, 3, 9)}
				`,
				width: '800px',
				confirmButtonText: 'Add',
				showCancelButton: true,
				cancelButtonColor: errorColor,
				cancelButtonText: 'Cancel',
				didOpen: () => {
					@if(auth()->user()->role == "Admin")
						$.ajax({
							url: '{{ route('company.get') }}',
							data: {
								select: "*",
								where: ["role", "Company"]
							},
							success: companys => {
								companys = JSON.parse(companys);

								let companyString = "";
								companys.forEach(company => {
									companyString += `
										<option value="${company.id}">${company.fname}</option>
									`;
								});

								$('#company_id').append(companyString);
								$('#company_id').select2();
							}
						});

						$('#company_id').on('change', e => {
							$('#route_id option:not(:first-child)').remove();
							$('#route_id').select2().val('');
							$.ajax({
								url: '{{ route('route.get') }}',
								data: {
									select: "*",
									where: ["company_id", e.target.value]
								},
								success: routes => {
									routes = JSON.parse(routes);

									let routeString = "";
									routes.forEach(route => {
										routeString += `
											<option value="${route.id}">${route.from} - ${route.to} (${route.direction})</option>
										`;
									});

									$('#route_id').append(routeString);
									$('#route_id').select2();
								}
							});
						});
					@else
						$.ajax({
							url: '{{ route('route.get') }}',
							data: {
								select: "*",
								where: ["company_id", {{ auth()->user()->id }}]
							},
							success: routes => {
								routes = JSON.parse(routes);

								let routeString = "";
								routes.forEach(route => {
									routeString += `
										<option value="${route.id}">${route.from} - ${route.to} (${route.direction})</option>
									`;
								});

								$('#route_id').append(routeString);
								$('#route_id').select2();
							}
						});
					@endif
				},
				preConfirm: () => {
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;

			            if($('.swal2-container input:placeholder-shown').length){
			                Swal.showValidationMessage('Fill all fields');
			            }
			            else if($('#company_id').val() == ""){
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
							company_id: $("[name='company_id']").val() ?? {{ auth()->user()->id }},
							vehicle_id: $("[name='vehicle_id']").val(),
							route_id: $("[name='route_id']").val(),
							type: $("[name='type']").val(),
							passenger_limit: $("[name='passenger_limit']").val(),
							driver: $("[name='driver']").val(),
							conductor: $("[name='conductor']").val(),
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
					
					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Route
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="route_id" id="route_id" class="form-control">
					        	<option value="">Select Route</option>
					        </select>
					    </div>
					</div>

					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Type
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="type" id="type" class="form-control">
					        	<option value="">Select Type</option>
					        	<option value="Car">Car</option>
					        	<option value="Bus">Bus</option>
					        	<option value="Trycicle">Trycicle</option>
					        	<option value="Taxi">Taxi</option>
					        	<option value="Ferry">Ferry</option>
					        	<option value="Train">Train</option>
					        </select>
					    </div>
					</div>

					${input("passenger_limit", "Passenger Limit", vehicle.passenger_limit, 3, 9, 'number', 'min=0')}
					${input("driver", "Driver", vehicle.driver, 3, 9)}
					${input("conductor", "Conductor", vehicle.conductor, 3, 9)}
	                <br>
				`,
				didOpen: () => {
					$.ajax({
						url: '{{ route('route.get') }}',
						data: {
							select: "*",
							where: ["company_id", vehicle.company_id]
						},
						success: routes => {
							routes = JSON.parse(routes);

							let routeString = "";
							routes.forEach(route => {
								routeString += `
									<option value="${route.id}">${route.from} - ${route.to} (${route.direction})</option>
								`;
							});

							$('#route_id').append(routeString);
							$('#route_id').select2();
							
							$('#type').val(vehicle.type).change();
							$('#route_id').val(vehicle.route_id).change();
						}
					});
				},
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
							route_id: $("[name='route_id']").val(),
							type: $("[name='type']").val(),
							passenger_limit: $("[name='passenger_limit']").val(),
							driver: $("[name='driver']").val(),
							conductor: $("[name='conductor']").val(),
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