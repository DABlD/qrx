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

                        @include('users.includes.toolbar2')
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>ID</th>
                    				<th>Username</th>
                    				<th>Name</th>
                    				<th>Gender</th>
                    				<th>Contact</th>
                    				<th>Date Created</th>
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
					url: "{{ route('datatable.user2') }}",
                	dataType: "json",
                	dataSrc: "",
					data: {
						select: "*",
						where: ['role', 'Admin']
					}
				},
				columns: [
					{data: 'id'},
					{data: 'username'},
					{data: 'fname'},
					{data: 'gender'},
					{data: 'contact'},
					{data: 'created_at'},
					{data: 'actions'},
				],
        		pageLength: 25,
				// drawCallback: function(){
				// 	init();
				// },
				columnDefs: [
					{
						targets: [0,1,2,3,4,5],
						className: "center"
					},
					{
						targets: [5],
						render: date => {
							return moment(date).format('MMM DD, YYYY HH:mm:ss');
						}
					}
				],
			});
		});

		function view(id){
			$.ajax({
				url: "{{ route('user.get') }}",
				data: {
					select: '*',
					where: ['id', id]
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
					${input("gender", "Gender", null, 3, 9)}
					${input("contact", "Contact", null, 3, 9)}

					<br>
	                ${input("username", "Username", null, 3, 9)}
	                ${input("password", "Password", null, 3, 9, 'password')}
	                ${input("password_confirmation", "Confirm Password", null, 3, 9, 'password')}
				`,
				title: "Enter Staff Details",
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

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					$.ajax({
						url: "{{ route('user.store2') }}",
						type: "POST",
						data: {
							fname: $("[name='fname']").val(),
							gender: $("[name='gender']").val(),
							contact: $("[name='contact']").val(),
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

		function showDetails(user){
			Swal.fire({
				html: `
	                ${input("id", "", user.id, 3, 9, 'hidden')}
	                
					${input("fname", "Name", user.fname, 3, 9)}
					${input("gender", "Gender", user.gender, 3, 9)}
					${input("contact", "Contact", user.contact, 3, 9)}

					<br>
	                ${input("username", "Username", user.username, 3, 9)}
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

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('user.update') }}",
						data: {
							id: $("[name='id']").val(),
							fname: $("[name='fname']").val(),
							gender: $("[name='gender']").val(),
							contact: $("[name='contact']").val(),
							username: $("[name='username']").val()
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

		function changePassword(id){
			Swal.fire({
			    html: `
			        ${input("password", "Password", null, 5, 7, 'password')}
			        ${input("password_confirmation", "Confirm Password", null, 5, 7, 'password')}
			    `,
			    confirmButtonText: 'Update',
			    showCancelButton: true,
			    cancelButtonColor: errorColor,
			    cancelButtonText: 'Exit',
			    width: "500px",
			    preConfirm: () => {
			        swal.showLoading();
			        return new Promise(resolve => {
			            setTimeout(() => {
			                if($('.swal2-container input:placeholder-shown').length){
			                    Swal.showValidationMessage('Fill all fields');
			                }
			                else if($("[name='password']").val().length < 8){
			                    Swal.showValidationMessage('Password must at least be 8 characters');
			                }
			                else if($("[name='password']").val() != $("[name='password_confirmation']").val()){
			                    Swal.showValidationMessage('Password do not match');
			                }
			            resolve()}, 500);
			        });
			    },
			}).then(result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('user.updatePassword') }}",
						data: {
							id: id,
							password: $("[name='password']").val(),
						}
					}, () => {
						ss("Success");
					});
				}
			});
		}
	</script>
@endpush