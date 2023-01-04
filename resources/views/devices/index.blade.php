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
                    				<th>Device ID</th>
                    				<th>Description</th>
                    				<th>Route</th>
                    				<th>Station</th>
                    				<th>Status</th>
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
						load: ['route', 'station', 'company'],
						@if(auth()->user()->role == "Company")
							where: ['company_id', {{ auth()->user()->id }}]
						@endif
					}
				},
				columns: [
					{data: 'company.fname', visible: false},
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
					{data: 'status'},
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
		                            		<td colspan="7">
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

					@if(auth()->user()->role == "Admin")
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

								$('#company_id').on('change', e => {
									selectRouteAndStation(e.target.value);
								});
							}
						});
					@else
						selectRouteAndStation({{ auth()->user()->id }});
					@endif
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
							company_id: $("[name='company_id']").val() ?? {{ auth()->user()->id }},
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

		function selectRouteAndStation(id){
			$.ajax({
				url: '{{ route('route.get') }}',
				data: {
					select: "*",
					where: ['company_id', id]
				},
				success: routes => {
					routes = JSON.parse(routes);

					let routeString = "";
					routes.forEach(route => {
						routeString += `
							<option value="${route.id}">${route.from} - ${route.to} (${route.direction})</option>
						`;
					});

					if(routeString != ""){
						$('#route_id').html(`<option value="">Select Route</option>`);
					}
					else{
						$('#route_id').html(`<option value="">No Route</option>`);
					}

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
					<br>

					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Status
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="status" id="status" class="form-control">
					        	<option value="Active" ${device.status == "Active" ? "selected" : ""}>Active</option>
					        	<option value="Inactive" ${device.status == "Inactive" ? "selected" : ""}>Inactive</option>
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
							status: $("[name='status']").val(),
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

		// ADS
		function ads(id, ids){
			Swal.fire({
				html: `
					<center>
						<b><h3>Ads</h3></b>
						<div class="float-right">
						    <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Assign AD" onclick="assignAd(${id})">
						        <i class="fas fa-plus fa-2xl"></i>
						    </a>
						</div>
					</center>


					<br>
					<br>

					<table id="ads" class="table">
						<thead>
							<tr>
								<td>ID</td>
								<td>Title</td>
								<td>Description</td>
								<td>URL</td>
								<td>Type</td>
								<td>Actions</td>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				`,
				didOpen: () => {
					$.ajax({
						url: '{{ route('ad.get') }}',
						data: {
							wherein: ['id', ids ? ids : []],
							select: "*",
						},
						success: ads => {
							ads = JSON.parse(ads);

							let string = "";

							if(ads.length){
								ads.forEach(ad => {
									string += `
										<tr>
											<td>${ad.id}</td>
											<td>${ad.title}</td>
											<td>${ad.description}</td>
											<td>
												<a href="${ad.url}" target="_blank">View</a>
											</td>
											<td>${ad.type}</td>
											<td>
												<a class="btn btn-danger btn-sm" data-toggle="tooltip" title="Remove Ad" onclick="removeAd(${id}, ${ad.id})">
												    <i class="fas fa-trash"></i>
												</a>
											</td>
										</tr>
									`;
								});
							}
							else{
								string = `
									<tr>
										<td colspan="6" style="text-align: center;">
											No Assigned Ads
										</td>
									</tr>
								`;
							}

							$('#ads tbody').append(string);
						}
					})
				}
			})
		}

		function assignAd(id){
			$.ajax({
				url: "{{ route("device.get") }}",
				data: {
					select: "ad_id",
					where: ['id', id]
				},
				success: device => {
					device = JSON.parse(device)[0];
					let ids = device.ad_id ? JSON.parse(device.ad_id) : [];
					console.log(ids);

					Swal.fire({
						html: `
							<div class="row iRow">
							    <div class="col-md-3 iLabel">
							        Ad
							    </div>
							    <div class="col-md-9 iInput">
							        <select name="ad" id="ad" class="form-control">
							        	<option value="">Select Ad</option>
							        </select>
							    </div>
							</div>
						`,
						didOpen: () => {
							$.ajax({
								url: '{{ route('ad.get') }}',
								data: {
									select: "*",
								},
								success: ads => {
									ads = JSON.parse(ads);
									let adString = "";
									ads.forEach(ad => {
										if(!ids.includes(ad.id.toString())){
											adString += `
												<option value="${ad.id}">
													${ad.title} - ${ad.description} (${ad.type})
												</option>
											`;
										}
									});

									$('#ad').append(adString);
									$('#ad').select2();
								}
							})
						},
						confirmButtonText: 'Add',
						showCancelButton: true,
						cancelButtonColor: errorColor,
						cancelButtonText: 'Cancel',
						preConfirm: () => {
						    swal.showLoading();
						    return new Promise(resolve => {
						    	let bool = true;

					            if($('#ad').val() == ""){
					                Swal.showValidationMessage('No Ad selected');
					            }

					            bool ? setTimeout(() => {resolve()}, 500) : "";
						    });
						},
					}).then(result => {
						if(result.value){
							ids.push(parseInt($('#ad').val()));

							swal.showLoading();
							$.ajax({
								url: "{{ route('device.update') }}",
								type: "POST",
								data: {
									id: id,
									ad_id: ids,
									_token: $('meta[name="csrf-token"]').attr('content')
								},
								success: () => {
									ss("Success");
									setTimeout(() => {
										ads(id, ids);
									}, 800);
								}
							})
						}
					});
				}
			})
		}

		function removeAd(id, aid){
			swal.showLoading();

			$.ajax({
				url: "{{ route("device.get") }}",
				data: {
					select: "ad_id",
					where: ['id', id]
				},
				success: device => {
					device = JSON.parse(device)[0];
					let ids = JSON.parse(device.ad_id);
					
					ids = ids.filter(value => {
						return value != aid;
					});

					$.ajax({
						url: "{{ route('device.update') }}",
						type: "POST",
						data: {
							id: id,
							ad_id: ids,
							_token: $('meta[name="csrf-token"]').attr('content')
						},
						success: () => {
							ss("Successfully Removed");
							setTimeout(() => {
								ads(id, ids);
							}, 800);
						}
					})
				}
			});
		}
	</script>
@endpush