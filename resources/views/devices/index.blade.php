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
                    				<th>Device ID</th>
                    				<th>Description</th>
                    				<th>Route</th>
                    				<th>Station</th>
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
	{{-- <link rel="stylesheet" href="{{ asset('css/datatables.bootstrap4.min.css') }}"> --}}
	{{-- <link rel="stylesheet" href="{{ asset('css/datatables-jquery.min.css') }}"> --}}
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/datatables.bundle.min.js') }}"></script>
	<script src="{{ asset('js/select2.min.js') }}"></script>
	{{-- <script src="{{ asset('js/datatables.bootstrap4.min.js') }}"></script> --}}
	{{-- <script src="{{ asset('js/datatables-jquery.min.js') }}"></script> --}}

	<script>
		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.device') }}",
                	dataType: "json",
                	dataSrc: "",
					data: {
						select: "*",
						load: ['route', 'station']
					}
				},
				columns: [
					{data: 'id'},
					{data: 'device_id'},
					{data: 'description'},
					{
						data: 'route_id',
						render: (rid, b, device) => {
							return rid != null ? `${device.route.from} - ${device.route.to} (${device.route.direction})` : "N/A";
						}
					},
					{
						data: 'station_id',
						render: (sid, b, device) => {
							return sid != null ? `${device.station.name}` : "N/A";
						}
					},
					{data: 'actions'},
				],
        		pageLength: 25,
			});
		});

		function view(id){
			$.ajax({
				url: "{{ route('device.get') }}",
				data: {
					select: '*',
					where: ['id', id],
				},
				success: device => {
					device = JSON.parse(device)[0];
					showDetails(device);
				}
			})
		}

		function create(){
			Swal.fire({
				html: `
	                ${input("device_id", "Device", null, 3, 9)}
					${input("description", "Description", null, 3, 9, 'email')}

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
					        Station
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="station_id" id="station_id" class="form-control">
					        	<option value="">Select Route First</option>
					        </select>
					    </div>
					</div>
	                <br>
				`,
				didOpen: () => {
					$.ajax({
						url: '{{ route('route.get') }}',
						data: {
							select: "*",
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
							$('#station_id').select2();

							$('#route_id').on('change', e => {
								$.ajax({
									url: '{{ route('station.get') }}',
									data: {
										select: "*",
										where: ["route_id", $('#route_id').val()]
									},
									success: stations => {
										stations = JSON.parse(stations);

										let stationString = "";
										stations.forEach(station => {
											stationString += `
												<option value="${station.id}">${station.name} (${station.label})</option>
											`;
										});

										if(stationString != ""){
											$('#station_id').select2('destroy');
											$('#station_id').html(`<option value="">Select Station</option>`);
										}

										$('#station_id').append(stationString);
										$('#station_id').select2();
									}
								})
							});
						}
					})
				},
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
            					url: "{{ route('device.get') }}",
            					data: {
            						select: "id",
            						where: ["device_id", $("[name='device_id']").val()]
            					},
            					success: result => {
            						result = JSON.parse(result);
            						if(result.length){
            			    			Swal.showValidationMessage('Device ID already exists');
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
						url: "{{ route('device.store') }}",
						type: "POST",
						data: {
							device_id: $("[name='device_id']").val(),
							description: $("[name='description']").val(),
							route_id: $("[name='route_id']").val(),
							station_id: $("[name='station_id']").val(),
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

		function showDetails(device){
			Swal.fire({
				html: `
	                ${input("id", "", device.id, 3, 9, 'hidden')}
	                ${input("device_id", "Device", device.device_id, 3, 9)}
					${input("description", "Description", device.description, 3, 9)}

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
					        Station
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="station_id" id="station_id" class="form-control">
					        	<option value="">Select Route First</option>
					        </select>
					    </div>
					</div>
	                <br>
				`,
				didOpen: () => {
					$.ajax({
						url: '{{ route('route.get') }}',
						data: {
							select: "*",
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
							$('#station_id').select2();

							$('#route_id').on('change', e => {
								$.ajax({
									url: '{{ route('station.get') }}',
									data: {
										select: "*",
										where: ["route_id", $('#route_id').val()]
									},
									success: stations => {
										stations = JSON.parse(stations);

										let stationString = "";
										stations.forEach(station => {
											stationString += `
												<option value="${station.id}">${station.name} (${station.label})</option>
											`;
										});

										if(stationString != ""){
											$('#station_id').select2('destroy');
											$('#station_id').html(`<option value="">Select Station</option>`);
										}

										$('#station_id').append(stationString);
										$('#station_id').select2();
										$('#station_id').val(device.station_id).change();
									}
								})
							});

							$('#route_id').val(device.route_id).change();
						}
					})
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
            					url: "{{ route('device.get') }}",
            					data: {
            						select: "id",
            						where: ["device_id", $("[name='device_id']").val()]
            					},
            					success: result => {
            						result = JSON.parse(result);
            						if(result.length && result[0].id != device.id){
            			    			Swal.showValidationMessage('Device ID already exists');
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
						url: "{{ route('device.update') }}",
						data: {
							id: $("[name='id']").val(),
							device_id: $("[name='device_id']").val(),
							description: $("[name='description']").val(),
							route_id: $("[name='route_id']").val(),
							station_id: $("[name='station_id']").val(),
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
						url: "{{ route('device.delete') }}",
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