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

                        @include('ads.includes.toolbar')
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>ID</th>
                    				<th>Title</th>
                    				<th>Description</th>
                    				<th>Url</th>
                    				<th>Type</th>
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
					url: "{{ route('datatable.ad') }}",
                	dataType: "json",
                	dataSrc: "",
					data: {
						select: "*",
					}
				},
				columns: [
					{data: 'id'},
					{data: 'title'},
					{data: 'description'},
					{
						data: 'url',
						render: url => {
							return `<a href="${url}" target="_blank">Click here to view</a>`;
						}
					},
					{data: 'type'},
					{data: 'actions'},
				],
        		pageLength: 25,
			});
		});

		function view(id){
			$.ajax({
				url: "{{ route('ad.get') }}",
				data: {
					select: '*',
					where: ['id', id],
				},
				success: ad => {
					ad = JSON.parse(ad)[0];
					showDetails(ad);
				}
			})
		}

		function create(){
			Swal.fire({
				html: `
	                ${input("title", "Title", null, 3, 9)}
					${input("description", "Description", null, 3, 9, 'email')}
	                ${input("url", "Url", null, 3, 9)}

	                <br>
					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Type
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="type" id="type" class="form-control">
					        	<option value="">Select Type</option>
					        	<option value="MP4">MP4</option>
					        	<option value="GIF">GIF</option>
					        	<option value="JPEG">JPEG</option>
					        </select>
					    </div>
					</div>
					<br>
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

			            if($('.swal2-container input:placeholder-shown').length || $('#type').val() == ""){
			                Swal.showValidationMessage('Fill all fields');
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					$.ajax({
						url: "{{ route('ad.store') }}",
						type: "POST",
						data: {
							title: $("[name='title']").val(),
							description: $("[name='description']").val(),
							url: $("[name='url']").val(),
							type: $("[name='type']").val(),
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

		function showDetails(ad){
			Swal.fire({
				html: `
	                ${input("id", "", ad.id, 3, 9, 'hidden')}
	                ${input("title", "Title", ad.title, 3, 9)}
					${input("description", "Description", ad.description, 3, 9)}
	                ${input("url", "Url", ad.url, 3, 9)}

	                <br>
					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Type
					    </div>

						<div class="col-md-9 iInput">
						    <select name="type" id="type" class="form-control">
						    	<option value="">Select Type</option>
						    	<option value="MP4" ${ad.type == "MP4" ? "selected" : ""}>MP4</option>
						    	<option value="GIF" ${ad.type == "GIF" ? "selected" : ""}>GIF</option>
						    	<option value="JPEG" ${ad.type == "JPEG" ? "selected" : ""}>JPEG</option>
						    </select>
						</div>
					</div>
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

			            if($('.swal2-container input:placeholder-shown').length || $('#type').val() == ""){
			                Swal.showValidationMessage('Fill all fields');
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('ad.update') }}",
						data: {
							id: $("[name='id']").val(),
							title: $("[name='title']").val(),
							description: $("[name='description']").val(),
							url: $("[name='url']").val(),
							type: $("[name='type']").val(),
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
						url: "{{ route('ad.delete') }}",
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