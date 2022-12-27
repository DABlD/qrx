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

                        @include('company.includes.toolbar')
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>ID</th>
                    				<th>Username</th>
                    				<th>Name</th>
                    				<th>Contact</th>
                    				<th>Email</th>
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
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/select2.min.js') }}"></script>

	<script>
		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.company') }}",
                	dataType: "json",
                	dataSrc: "",
					data: {
						select: "*",
					}
				},
				columns: [
					{data: 'id'},
					{data: 'username'},
					{data: 'fname'},
					{data: 'contact'},
					{data: 'email'},
					{data: 'actions'},
				],
        		pageLength: 25,
				// drawCallback: function(){
				// 	init();
				// }
			});
		});

		function view(id){
			$.ajax({
				url: "{{ route('company.get') }}",
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
	                ${input("fname", "Name", null, 3, 9)}
	                ${input("contact", "Contact", null, 3, 9)}
	                ${input("email", "Email", null, 3, 9)}

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
						url: "{{ route('company.store') }}",
						type: "POST",
						data: {
							username: $("[name='username']").val(),
							fname: $("[name='fname']").val(),
							contact: $("[name='contact']").val(),
							email: $("[name='email']").val(),
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

		function showDetails(company){
			Swal.fire({
				html: `
	                ${input("id", "", company.id, 3, 9, 'hidden')}

	                ${input("fname", "Name", company.fname, 3, 9)}
	                ${input("contact", "Contact", company.contact, 3, 9)}
	                ${input("email", "Email", company.email, 3, 9)}

	                <br>
	                ${input("username", "Username", company.username, 3, 9)}
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
            						if(result.length && result[0].id != user.id){
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
			            						if(result.length && result[0].id != user.id){
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
						url: "{{ route('company.update') }}",
						data: {
							id: $("[name='id']").val(),
							username: $("[name='username']").val(),
							fname: $("[name='fname']").val(),
							contact: $("[name='contact']").val(),
							email: $("[name='email']").val(),
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
						url: "{{ route('company.delete') }}",
						data: {id: id},
						message: "Success"
					}, () => {
						reload();
					})
				}
			});
		}

		function themes(id){
			$.ajax({
				url: '{{ route('theme.get') }}',
				data: {
					select: '*',
					where: ['company_id', id]
				},
				success: themes => {
					themes = JSON.parse(themes);
					themeString = "";

					themes.forEach(theme => {
						let temp = "";
						if(theme.name.includes('img')){
							temp = `
								<img src="${theme.value}" id="${theme.name}" alt="${theme.name}" width="100px;" height="100px">
								<br>
								<br>
								${input(theme.name, '', theme.value, 0, 10, 'file')}
							`;
						}
						else if(theme.name.includes('color')){
							temp = input(theme.name, '', theme.value, 0, 12, 'color');
						}
						else{
							temp = input(theme.name, '', theme.value, 0, 12);
						}

						themeString += `
							<div class="row">
							    <div class="col-md-5">
							    	${theme.name.replace('_', '').replace(/(^\w{1})|(\s+\w{1})/g, letter => letter.toUpperCase())}
							    </div>
							    <div class="col-md-7">
							    	${temp}
						        </div>
						    </div>
						    <br>
						`;
					});

				    Swal.fire({
				        width: '600px',
				        html: themeString,
				        didOpen: () => {
				            $('.swal2-container .col-md-5').css({
				                'text-align': 'left',
				                'margin': 'auto'
				            });
				            $('.swal2-container .col-md-7 div').css({
				                'text-align': 'center',
				                'margin': 'auto'
				            });

				            $('[type="file"]').on('change', e => {
				                var reader = new FileReader();
				                reader.onload = function (e2) {
				                    let name = $(e.target).prop('name');
				                    $(`#${name}`).attr('src', e2.target.result);
				                }

				                reader.readAsDataURL(e.target.files[0]); 
				            });
				        }
				    }).then(result => {
				        if(result.value){
				            swal.showLoading();

				            let formData = new FormData();
				            formData.append('cid', id);
				            formData.append('logo_img', $('[name="logo_img"]').prop('files')[0]);
				            formData.append('login_banner_img', $('[name="login_banner_img"]').prop('files')[0]);
				            formData.append('login_bg_img', $('[name="login_bg_img"]').prop('files')[0]);
				            formData.append('sidebar_bg_color', $('[name="sidebar_bg_color"]').val());
				            formData.append('table_header_color', $('[name="table_header_color"]').val());
				            formData.append('table_header_font_color', $('[name="table_header_font_color"]').val());
				            formData.append('sidebar_font_color', $('[name="sidebar_font_color"]').val());
				            formData.append('table_group_color', $('[name="table_group_color"]').val());
				            formData.append('table_group_font_color', $('[name="table_group_font_color"]').val());
				            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

				            updateTheme(formData);
				        }
				    })
				}
			})
		}

		async function updateTheme(formData){
		    await fetch('{{ route('theme.update') }}', {
		        method: "POST", 
		        body: formData,
		    }).then(result => {
		        console.log(result);
		        ss("Successfully Updated Theme", "Refreshing");
		        setTimeout(() => {
		            // window.location.reload();
		        }, 1200);
		    });
		}
	</script>
@endpush