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

                        @include('routes.includes.toolbar')
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>cid</th>
                    				<th>ID</th>
                    				<th>From</th>
                    				<th>To</th>
                    				<th>Direction</th>
                    				<th>Base Fare</th>
                    				<th>Per KM Fare</th>
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
	{{-- <link rel="stylesheet" href="{{ asset('css/datatables.bundle.min.css') }}"> --}}
	<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
	{{-- <link rel="stylesheet" href="{{ asset('css/datatables.bootstrap4.min.css') }}"> --}}
	{{-- <link rel="stylesheet" href="{{ asset('css/datatables-jquery.min.css') }}"> --}}

	<style>
		.ss{
			background-color: gray;
		}

		.m-row{
			transform: rotate(-50deg);
			word-break: initial;
			padding-left: 10px;
			/*text-align: left;*/
		}

		.matrix td{
    		white-space: nowrap;
    		height: 30px;
    		/*padding: 5px 5px 5px 5px;*/
		}

		.matrix td:not(:nth-child(1)){
			width: 50px;
			max-width: 50px;
		}

		.matrix td:nth-child(1){
			/*max-width: none;*/
			margin-right: 10px;
			text-align: left;
		}

		.matrix tr:nth-child(2){
			height: 50px;
		}

		.wb{
			text-align: left;
			height: 150px !important;
		}

		.matrix{
			text-align: center;
		}

		.mc{
			border: 1px solid black;
		}
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	{{-- <script src="{{ asset('js/datatables.bundle.min.js') }}"></script> --}}
	<script src="{{ asset('js/select2.min.js') }}"></script>
	{{-- <script src="{{ asset('js/datatables.bootstrap4.min.js') }}"></script> --}}
	{{-- <script src="{{ asset('js/datatables-jquery.min.js') }}"></script> --}}

	<script>
		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.route') }}",
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
					{data: 'from'},
					{data: 'to'},
					{data: 'direction'},
					{data: 'base_fare'},
					{data: 'per_km_fare'},
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
		                            		<td colspan="8">
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
				url: "{{ route('route.get') }}",
				data: {
					select: '*',
					where: ['id', id],
				},
				success: route => {
					route = JSON.parse(route)[0];
					showDetails(route);
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

					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Direction
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="direction" id="direction" class="form-control">
					        	<option value="">Select Direction</option>
					        	<option value="Northbound">Northbound</option>
					        	<option value="Southbound">Southbound</option>
					        </select>
					    </div>
					</div>

	                <br>
	                ${input("from", "From", null, 3, 9)}
	                ${input("to", "To", null, 3, 9)}
	                ${input("base_fare", "Base Fare", null, 3, 9, "number")}
	                ${input("per_km_fare", "Per KM Fare", null, 3, 9, "number")}
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
			            else if($('#direction').val() == ""){
			                Swal.showValidationMessage('Fill all fields');
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					$.ajax({
						url: "{{ route('route.store') }}",
						type: "POST",
						data: {
							company_id: $("[name='company_id']").val() ?? {{ auth()->user()->id }},
							from: $("[name='from']").val(),
							to: $("[name='to']").val(),
							direction: $("[name='direction']").val(),
							base_fare: $("[name='base_fare']").val(),
							per_km_fare: $("[name='per_km_fare']").val(),
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

		function showDetails(route){
			Swal.fire({
				html: `
	                ${input("id", "", route.id, 3, 9, 'hidden')}

					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Direction
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="direction" id="direction" class="form-control">
					        	<option value="">Select Direction</option>
					        	<option value="Northbound">Northbound</option>
					        	<option value="Southbound">Southbound</option>
					        </select>
					    </div>
					</div>

	                <br>
	                ${input("from", "From", route.from, 3, 9)}
	                ${input("to", "To", route.to, 3, 9)}
	                ${input("base_fare", "Base Fare", route.base_fare, 3, 9, "number")}
	                ${input("per_km_fare", "Per KM Fare", route.per_km_fare, 3, 9, "number")}
				`,
				didOpen: () => {
					$('#direction').val(route.direction).change();
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
			            else if($('#direction').val() == ""){
			                Swal.showValidationMessage('Fill all fields');
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('route.update') }}",
						data: {
							id: $("[name='id']").val(),
							from: $("[name='from']").val(),
							to: $("[name='to']").val(),
							direction: $("[name='direction']").val(),
							base_fare: $("[name='base_fare']").val(),
							per_km_fare: $("[name='per_km_fare']").val(),
							_token: $('meta[name="csrf-token"]').attr('content')
						},
						message: "Success"
					}, () => {
						reload();
					})
				}
			});
		}

		function del(id){
			sc("Confirmation", "Are you sure you want to delete?", result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('user.delete') }}",
						data: {id: id},
						message: "Success"
					}, () => {
						reload();
					})
				}
			});
		}

		// STATION FUNCTIONS
		function stations(rid){
			Swal.fire({
				title: 'Stations',
				width: "50%",
				html: `
					<div class="row">
						<div class="col-md-12">
							<div class="float-right">
								<a class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Station" onclick="create2(${rid})">
								    <i class="fas fa-plus fa-2xl"></i>
								</a>
							</div>
						</div>
					</div>
					<br>

					<table id="table2" class="table table-hover" style="width: 100%;">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Label</th>
								<th>Kilometer</th>
								<th>lat</th>
								<th>lng</th>
								<th>Actions</th>
							</tr>
						</thead>

						<tbody>
						</tbody>
					</table>
				`,
				didOpen: () => {
					var table2 = $('#table2').DataTable({
						ajax: {
							url: "{{ route('datatable.station') }}",
		                	dataType: "json",
		                	dataSrc: "",
							data: {
								select: "*",
								where: ["route_id", rid]
							}
						},
						columns: [
							{data: 'id'},
							{data: 'name'},
							{data: 'label'},
							{data: 'kilometer'},
							{
								data: 'lat',
								render: lat => {
									return lat != null ? lat : "-";
								}
							},
							{
								data: 'lng',
								render: lng => {
									return lng != null ? lng : "-";
								}
							},
							{data: 'actions'},
						],
		        		pageLength: 25,
		        		order:[[3, 'asc']]
					});
				}
			})
		}

		function create2(rid){
			Swal.fire({
				title: 'Enter Station Details',
				html: `
	                ${input("name", "Name", null, 3, 9)}
	                ${input("label", "Label", null, 3, 9)}
	                ${input("kilometer", "Kilometer", null, 3, 9, 'number')}
	                <br>
	                ${input("lat", "Latitude", null, 3, 9, 'number')}
	                ${input("lng", "Longitude", null, 3, 9, 'number')}
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
            					url: "{{ route('station.get') }}",
            					data: {
            						select: ["id", "route_id"],
            						where: ["name", $("[name='name']").val()],
            						where2: ["route_id", rid]
            					},
            					success: result => {
            						result = JSON.parse(result);

            						if(result.length){
            			    			Swal.showValidationMessage('Station name already exists');
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
						url: "{{ route('station.store') }}",
						type: "POST",
						data: {
							route_id: rid,
							name: $("[name='name']").val(),
							label: $("[name='label']").val(),
							kilometer: $("[name='kilometer']").val(),
							lat: $("[name='lat']").val(),
							lng: $("[name='lng']").val(),
							_token: $('meta[name="csrf-token"]').attr('content')
						},
						success: () => {
							ss("Success");
							setTimeout(() => {
								stations(rid);
							}, 1000);
						}
					})
				}
				else{
					stations(rid);
				}
			});
		}

		function view2(id){
			$.ajax({
				url: "{{ route('station.get') }}",
				data: {
					select: '*',
					where: ['id', id],
				},
				success: station => {
					station = JSON.parse(station)[0];
					showDetails2(station);
				}
			})
		}

		function showDetails2(station){
			Swal.fire({
				title: "Station Details",
				html: `
	                ${input("id", "", station.id, 3, 9, 'hidden')}
	                ${input("name", "Name", station.name, 3, 9)}
	                ${input("label", "Label", station.label, 3, 9)}
	                ${input("kilometer", "Kilometer", station.kilometer, 3, 9, 'number')}

	                <br>
	                ${input("lat", "Latitude", station.lat, 3, 9, 'number')}
	                ${input("lng", "Longitude", station.lng, 3, 9, 'number')}
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
            					url: "{{ route('station.get') }}",
            					data: {
            						select: "id",
            						where: ["name", $("[name='name']").val()]
            					},
            					success: result => {
            						result = JSON.parse(result);

            						if(result.length && result[0].id != station.id){
            			    			Swal.showValidationMessage('Station name already exists');
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
						url: "{{ route('station.update') }}",
						data: {
							id: $("[name='id']").val(),
							name: $("[name='name']").val(),
							label: $("[name='label']").val(),
							kilometer: $("[name='kilometer']").val(),
							lat: $("[name='lat']").val(),
							lng: $("[name='lng']").val(),
						},
						message: "Success"
					},	() => {
						setTimeout(() => {
							stations(station.route_id);
						}, 1000);
					});
				}
				else{
					stations(station.route_id);
				}
			});
		}

		function del2(id, rid){
			sc("Confirmation", "Are you sure you want to delete?", result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('station.delete') }}",
						data: {id: id},
						message: "Success"
					}, () => {
						setTimeout(() => {
							stations(rid);
						}, 1000);
					})
				}
				else{
					stations(rid);
				}
			});
		}

		function matrix(id){
			$.ajax({
				url: "{{ route('route.get') }}",
				data: {
					select: "*",
					where: ['id', id],
					load: ['stations']
				},
				success: route => {
					route = JSON.parse(route)[0];
					let stations = route.stations;

					let matrix = "";

					// 1ST ROW
					matrix += `
						<tr>
							${addCell("")}
							${addCell("Destination", stations.length ? stations.length : 1, "wb bold")}
						</tr>
					`;

					// 2ND ROW
					matrix += `
						<tr>
							${addCell("Origin", 1, "bold")}
					`;

					stations.forEach(station => {
						matrix += addCell(station.name, 1, "m-row");
					});

					matrix += "</tr>";

					// MAIN BODY
					stations.forEach(station => {
						matrix += `
							<tr>
							${addCell(station.name + "‎ ‎ ‎ ‎ ")}
						`;

						stations.forEach(station2 => {
							if(station.id == station2.id){
								matrix += `${addCell("", 1, "ss")}`; //SAME STATION
							}
							else{
								let distance = station2.kilometer - station.kilometer;
								    distance = Math.abs(distance) - 1;

								let cost = route.base_fare + (Math.ceil(distance * route.per_km_fare));

								matrix += `${addCell(cost, 1, "mc")}`;
							}
						});
					});

					showMatrix(matrix, stations);
				}
			})
		}

		function addCell(text, cols = 1, _class = ""){
			return `<td colspan="${cols}" class="${_class}">${text}</td>`;
		}

		function showMatrix(matrix, stations){
			Swal.fire({
				title: "Fare Matrix",
				width: `${(stations.length * 80) + 200}px`,
				html: `
					<center>
						<table class="matrix">
							<tbody>
								${matrix}
							</tbody>
						</table>
					</center>
				`,
			})
		}
	</script>
@endpush