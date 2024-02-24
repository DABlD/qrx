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

                        @include('users.includes.toolbar')
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>ID</th>
                    				<th>Name</th>
                    				<th>Gender</th>
                    				<th>Contact</th>
                    				<th>Interest Rate</th>
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
					{data: 'user.gender'},
					{data: 'user.contact'},
					{data: 'percent'},
					{data: 'id_verified'},
					{data: 'actions'},
				],
        		pageLength: 25,
				// drawCallback: function(){
				// 	init();
				// },
				columnDefs: [
					{
						targets: [4],
						render: percent => {
							return `${percent}%`;
						}
					},
					{
						targets: [0,1,2,3,4,5],
						className: "center"
					},
					{
						targets: [5],
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
					showDetails(admin);
				}
			})
		}

		function create(){
			Swal.fire({
				html: `
	                ${input("fname", "Name", null, 3, 9)}
					${input("email", "Email", null, 3, 9, 'email')}
					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Role
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="role" class="form-control">
					        	<option value="Admin">Admin</option>
					        	<option value="Company">Company</option>
					        	<option value="Coast Guard">Coast Guard</option>
					        </select>
					    </div>
					</div>

	                <br>
	                ${input("username", "Username", null, 3, 9)}
	                ${input("password", "Password", null, 3, 9, 'password')}
	                ${input("password_confirmation", "Confirm Password", null, 3, 9, 'password')}
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
			            else if($("[name='password']").val().length < 8){
			                Swal.showValidationMessage('Password must at least be 8 characters');
			            }
			            else if($("[name='password']").val() != $("[name='password_confirmation']").val()){
			                Swal.showValidationMessage('Password do not match');
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
						url: "{{ route('user.store') }}",
						type: "POST",
						data: {
							fname: $("[name='fname']").val(),
							email: $("[name='email']").val(),
							role: $("[name='role']").val(),
							username: $("[name='username']").val(),
							password: $("[name='password']").val(),
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

		function showDetails(branch){
			Swal.fire({
				html: `
	                ${input("id", "", branch.id, 3, 9, 'hidden')}
	                ${input("fname", "Name", branch.user.fname, 3, 9)}
					${input("email", "Email", branch.user.email, 3, 9, 'email')}
					${input("gender", "Gender", branch.user.gender, 3, 9)}
					${input("contact", "Contact", branch.user.contact, 3, 9)}

	                <br>
	                ${input("username", "Username", branch.user.username, 3, 9)}
					${input("work_status", "Work Status", branch.work_status, 3, 9)}
					${input("percent", "Interest Rate", branch.percent, 3, 9, 'number', 'min=0 max=100')}

					<br>
					${input("id_type", "ID Type", branch.id_type, 3, 9)}
					${input("id_num", "ID Number", branch.id_num, 3, 9)}
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
							email: $("[name='email']").val(),
							gender: $("[name='gender']").val(),
							contact: $("[name='contact']").val(),
							username: $("[name='username']").val(),
							work_status: $("[name='work_status']").val(),
							percent: $("[name='percent']").val(),
							id_type: $("[name='id_type']").val(),
							id_num: $("[name='id_num']").val(),
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
						url: "{{ route('user.delete') }}",
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
	</script>
@endpush