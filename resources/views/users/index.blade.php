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
                            Clients
                        </h3>

                        @include('users.includes.toolbar')
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>ID</th>
                    				<th>First Name</th>
                    				<th>Middle Name</th>
                    				<th>Last Name</th>
                    				<th>Gender</th>
                    				<th>Contact</th>
                    				{{-- <th>Interest Rate</th> --}}
                    				<th>Verified</th>
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
	<style>
		.center{
			text-align: center;
		}
	</style>
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
					url: "{{ route('datatable.branch') }}",
                	dataType: "json",
                	dataSrc: "",
					data: {
						select: "*",
						load: [['user']]
					}
				},
				columns: [
					{data: 'id'},
					{data: 'user.fname'},
					{data: 'user.mname'},
					{data: 'user.lname'},
					{data: 'user.gender'},
					{data: 'user.contact'},
					// {data: 'percent'},
					{data: 'id_verified'},
					{data: 'actions'},
				],
        		pageLength: 25,
				// drawCallback: function(){
				// 	init();
				// },
				columnDefs: [
					// {
					// 	targets: [4],
					// 	render: percent => {
					// 		return `${percent}%`;
					// 	}
					// },
					{
						targets: [0,1,2,3,4,5,6],
						className: "center"
					},
					{
						targets: [6],
						render: status => {
							return status ? "Yes" : "No";
						}
					}
				],
			});
		});

		function view(id){
			$.ajax({
				url: "{{ route('branch.get') }}",
				data: {
					select: '*',
					where: ['id', id],
					load: ['user']
				},
				success: admin => {
					admin = JSON.parse(admin)[0];

					$.ajax({
						url: "{{ route('kyc.get') }}",
						data: {
							select: '*',
							where: ['mobile_number', admin.user.contact],
						},
						success: kyc => {
							kyc = JSON.parse(kyc)[0];
							showDetails(admin, kyc);
						}
					})
				}
			})
		}

		function create(){
			Swal.fire({
				html: `
	                ${input("fname", "First Name", null, 3, 9)}
	                ${input("mname", "Middle Name", null, 3, 9)}
	                ${input("lname", "Last Name", null, 3, 9)}
					${input("email", "Email", null, 3, 9, 'email')}
					${input("gender", "Gender", null, 3, 9)}
					${input("contact", "Contact", null, 3, 9)}
					${input("address", "Address", null, 3, 9)}

	                <br>
	                ${input("username", "Username", null, 3, 9)}
					${input("work_status", "Work Status", null, 3, 9)}

	                <br>
	                ${input("password", "", 12345678, 3, 9, 'hidden')}
				`,
				title: "Enter Client Details",
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
			            else if($("[name='password']").val().length < 8){
			                Swal.showValidationMessage('Password must at least be 8 characters');
			            }
			            else{
			            	let bool = false;
            				$.ajax({
            					url: "{{ route('user.get') }}",
            					data: {
            						select: "id",
            						where: ["email", $("[name='email']").val()]
            					},
            					success: result => {
            						result = JSON.parse(result);
            						if(result.length){
            			    			Swal.showValidationMessage('Email already used');
	            						setTimeout(() => {resolve()}, 500);
            						}
            						else{
			            				$.ajax({
			            					url: "{{ route('user.get') }}",
			            					data: {
			            						select: "id",
			            						where: ["username", $("[name='username']").val()]
			            					},
			            					success: result => {
			            						result = JSON.parse(result);
			            						if(result.length){
			            			    			Swal.showValidationMessage('Username already used');
				            						setTimeout(() => {resolve()}, 500);
			            						}
			            					}
			            				});
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
						url: "{{ route('branch.store') }}",
						type: "POST",
						data: {
							fname: $("[name='fname']").val(),
							mname: $("[name='mname']").val(),
							lname: $("[name='lname']").val(),
							email: $("[name='email']").val(),
							gender: $("[name='gender']").val(),
							contact: $("[name='contact']").val(),
							address: $("[name='address']").val(),
							username: $("[name='username']").val(),
							password: $("[name='password']").val(),
							work_status: $("[name='work_status']").val(),
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

		function showDetails(branch, kyc){
			Swal.fire({
				html: `
	                ${input("id", "", branch.id, 3, 9, 'hidden')}
	                ${input("fname", "First Name", branch.user.fname, 3, 9)}
	                ${input("mname", "Middle Name", branch.user.mname, 3, 9)}
	                ${input("lname", "Last Name", branch.user.lname, 3, 9)}
					${input("email", "Email", branch.user.email, 3, 9, 'email')}
					${input("gender", "Gender", branch.user.gender, 3, 9)}
					${input("contact", "Contact", branch.user.contact, 3, 9)}
					${input("address", "Address", branch.user.address, 3, 9)}

	                <br>
	                ${input("username", "Username", branch.user.username, 3, 9)}
					${input("work_status", "Work Status", branch.work_status, 3, 9)}

					<br>
					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Status
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="id_verified" class="form-control">
					        	<option value="0" ${branch.id_verified == 0 ? "selected" : ""}>Not Verified</option>
					        	<option value="1" ${branch.id_verified == 1 ? "selected" : ""}>Verified</option>
					        </select>
					    </div>
					</div>
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
            					url: "{{ route('user.get') }}",
            					data: {
            						select: "id",
            						where: ["email", $("[name='email']").val()]
            					},
            					success: result => {
            						result = JSON.parse(result);
            						if(result.length && result[0].id != branch.user.id){
            			    			Swal.showValidationMessage('Email already used');
	            						setTimeout(() => {resolve()}, 500);
            						}
			            			else{
			            				$.ajax({
			            					url: "{{ route('user.get') }}",
			            					data: {
			            						select: "id",
			            						where: ["username", $("[name='username']").val()]
			            					},
			            					success: result => {
			            						result = JSON.parse(result);
			            						if(result.length && result[0].id != branch.user.id){
			            			    			Swal.showValidationMessage('Username already used');
				            						setTimeout(() => {resolve()}, 500);
			            						}
			            					}
			            				});
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
						url: "{{ route('branch.update') }}",
						data: {
							id: $("[name='id']").val(),
							fname: $("[name='fname']").val(),
							mname: $("[name='mname']").val(),
							lname: $("[name='lname']").val(),
							email: $("[name='email']").val(),
							gender: $("[name='gender']").val(),
							contact: $("[name='contact']").val(),
							address: $("[name='address']").val(),
							username: $("[name='username']").val(),
							work_status: $("[name='work_status']").val(),
							// id_type: $("[name='id_type']").val(),
							// id_num: $("[name='id_num']").val(),
							id_verified: $("[name='id_verified']").val(),
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
						url: "{{ route('branch.delete') }}",
						data: {id: id},
						message: "Success"
					}, () => {
						reload();
					})
				}
			});
		}

		function res(id){
			sc("Confirmation", "Are you sure you want to restore?", result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('user.restore') }}",
						data: {id: id},
						message: "Success"
					}, () => {
						reload();
					})
				}
			});
		}

		function imp(){
			Swal.fire({
			    title: 'Select File',
			    html: `
			        <form id="form" method="POST" action="{{ route('user.import') }}" enctype="multipart/form-data">
			            @csrf
			            <input type="file" name="file" class="swal2-file">
			        </form>
			    `
			}).then(file => {
			    if(file.value){
			        $('#form').submit();
			    }
			});
		}
	</script>
@endpush