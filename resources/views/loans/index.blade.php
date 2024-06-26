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

                        @include('loans.includes.toolbar')
                    </div>

                    <div class="card-body table-responsive">
                    	{{-- FILTERS --}}

                    	<select id="fName">
                    	    <option></option>
                    	    <option value="%%">All</option>
                    	</select>

                    	<select id="fType">
                    	    <option></option>
                    	    <option value="%%">All</option>
                    	</select>

                    	<select id="fStatus">
                    	    <option></option>
                    	    <option value="%%">All</option>
                    	    <option value="Applied">Applied</option>
                    	    <option value="Approved">Approved</option>
                    	    <option value="Disapproved">Disapproved</option>
                    	    <option value="For Payment">For Payment</option>
                    	    <option value="Overdue">Overdue</option>
                    	    <option value="Paid">Paid</option>
                    	</select>

                        <h3 class="float-right">
                            <a class="btn btn-success btn-sm" onclick="exporto()">
        						<i class="fas fa-file-export"> Export</i>
                            </a>
                        </h3>

                    	<br>
                    	<br>

                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>ID</th>
                    				<th>Name</th>
                    				<th>Contract #</th>
                    				<th>Type</th>
                    				<th>Amount</th>
                    				<th>Rate</th>
                    				<th>Paid Months</th>
                    				<th>Monthly Payment</th>
                    				<th>Total Payment</th>
                    				<th>Revenue</th>
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
	<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
	<style>
		.center{
			text-align: center;
		}
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/select2.min.js') }}"></script>
	<script src="{{ asset('js/numeral.min.js') }}"></script>

	<script>
		var fName = "%%";
		var fType = "%%";
		var fStatus = "%%";

		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.loans') }}",
                	dataType: "json",
                	dataSrc: "",
					data: f => {
						f.select = "*";
						f.load = ['branch.user'];
						f.filters = getFilters();
					}
				},
				columns: [
					{data: 'id'},
					{data: 'branch.user.fname'},
					{data: 'contract_no'},
					{data: 'type'},
					{data: 'amount'},
					{data: 'percent'},
					{data: 'months'},
					{data: 'amount'},
					{data: 'amount'},
					{data: 'amount'},
					{data: 'status'},
					{data: 'actions'},
				],
        		pageLength: 25,
				columnDefs: [
					{
						targets: [0,1,2,3,4,5,6,7,8,9,10],
						className: "center"
					},
					{
						targets: 4,
						render: amount => {
							return "₱" + numeral(amount).format("0,0.00");
						}
					},
					{
						targets: 5,
						render: percent => {
							return percent + "%";
						}
					},
					{
						targets: 6,
						render: (a,b,c) => {
							return c.paid_months + " / " + a;
						}
					},
					{
						targets: 7,
						render: (amount,b,c) => {
							let percent = c.percent / 100 / 12;
							amount = c.amount * -1;

							return "₱" + numeral((percent) * amount * Math.pow((1 + (percent)), c.months) / (1 - Math.pow((1 + (percent)), c.months))).format("0,0.00");
						}
					},
					{
						targets: 8,
						render: (amount,b,c) => {
							let percent = c.percent / 100 / 12;
							amount = c.amount * -1;

							return "₱" + numeral(((percent) * amount * Math.pow((1 + (percent)), c.months) / (1 - Math.pow((1 + (percent)), c.months))) * c.months).format("0,0.00");
						}
					},
					{
						targets: 9,
						render: (amount,b,c) => {
							let percent = c.percent / 100 / 12;
							amount = c.amount * -1;

							return "₱" + numeral((((percent) * amount * Math.pow((1 + (percent)), c.months) / (1 - Math.pow((1 + (percent)), c.months))) * c.months) + amount).format("0,0.00");
						}
					},
					{
						targets: 11,
						width: "130px"
					}
				]
				// drawCallback: function(){
				// 	init();
				// }
			});

			// FILTERS
			// FNAME
			$('#fName').select2({
			    width: '300px',
			    placeholder: "Select Client"
			});

			$.ajax({
			    url: '{{ route('branch.get') }}',
			    data: {
			        select: ['id', 'user_id'],
			        load: ['user']
			    },
			    success: result => {
			        result = JSON.parse(result);

			        let tempString = "";
			        result.forEach(branch => {
			        	let user = branch.user;
			            tempString += `
			                <option value="${branch.id}">${(user.fname || user.lname) ? (user.fname ? user.fname + " " + (user.lname ?? "") : "") : user.username} (#${branch.id})</option>
			            `;
			        });

			        $('#fName').append(tempString);

			        $('#fName').change(() => {
			            fName = $('#fName').val();
			            reload();
			        });
			    }
			})

			// FTYPE
			$('#fType').select2({
			    width: '150px',
			    placeholder: "Select Type"
			});

			$.ajax({
			    url: '{{ route('loan.get') }}',
			    data: {
			        select: 'type'
			    },
			    success: result => {
			        result = JSON.parse(result);
			        let options = [];

			        result.forEach(option => {
			            options.push(option.type);
			        });

			        // REMOVE DUPLICATE
			        options = [...new Set(options)];

			        let tempString = "";
			        options.forEach(option => {
			            tempString += `
			                <option value="${option}">${option}</option>
			            `;
			        });

			        $('#fType').append(tempString);

			        $('#fType').change(() => {
			            fType = $('#fType').val();
			            reload();
			        });
			    }
			})

			// FSTATUS
			$('#fStatus').select2({
			    width: '150px',
			    placeholder: "Select Status"
			});

	        $('#fStatus').change(() => {
	            fStatus = $('#fStatus').val();
	            reload();
	        });
		});

		function getFilters(){
			return {
				fName: fName,
				fType: fType,
				fStatus: fStatus
			};
		}

		function view(id){
			$.ajax({
				url: "{{ route('loan.get') }}",
				data: {
					select: '*',
					where: ['id', id],
					load: ['branch.user']
				},
				success: loan => {
					loan = JSON.parse(loan)[0];
					showDetails(loan);
				}
			})
		}

		function create(){
			Swal.fire({
				html: `
					<div class="row iRow">
					    <div class="col-md-4 iLabel">
					        Client
					    </div>
					    <div class="col-md-8 iInput">
					        <select name="branch_id" class="form-control">
					        </select>
					    </div>
					</div>

					<div class="row iRow">
					    <div class="col-md-4 iLabel">
					        Type
					    </div>
					    <div class="col-md-8 iInput">
					        <select name="type" class="form-control">
					        	<option value="">Select Type</option>
					        	<option value="Personal Loan">Personal Loan</option>
					        	<option value="Housing Loan">Housing Loan</option>
					        	<option value="Business Loan">Business Loan</option>
					        	<option value="Car Loan">Car Loan</option>
					        	<option value="Student Loan">Student Loan</option>
					        	<option value="Debt Consolidation Loan">Debt Consolidation Loan</option>
					        	<option value="General Expense">General Expense</option>
					        </select>
					    </div>
					</div>

					${input("amount", "Amount", null, 4, 8, 'number')}
					${input("percent", "Interest Rate", null, 4, 8, 'number')}
					${input("months", "Months", null, 4, 8, 'number', 'min=1 max=60')}

					<br>
					<br>

					${input("collateral1", "Collateral 1", " ", 4, 8)}
					${input("file1", "Upload File", null, 4, 8, 'file', 'accept="image/*"')}
					${input("collateral2", "Collateral 2", " ", 4, 8)}
					${input("file2", "Upload File", null, 4, 8, 'file', 'accept="image/*"')}
					${input("collateral3", "Collateral 3", " ", 4, 8)}
					${input("file3", "Upload File", null, 4, 8, 'file', 'accept="image/*"')}

					<br>
					<br>
					<div class="row iRow">
					    <div class="col-md-4 iLabel">
					        Total Payment
					    </div>
					    <div class="col-md-8 iInput" id="tPayment">
					    </div>
					</div>

					<div class="row iRow">
					    <div class="col-md-4 iLabel">
					        Monthly Payment
					    </div>
					    <div class="col-md-8 iInput" id="mPayment">
					    </div>
					</div>
				`,
				width: '500px',
				confirmButtonText: 'Save',
				showCancelButton: true,
				cancelButtonColor: errorColor,
				cancelButtonText: 'Cancel',
				didOpen: () => {

					$.ajax({
						url: "{{ route("branch.get") }}",
						data: {
							select: "*",
							load: ["user"]
						},
						success: result => {
							result = JSON.parse(result);
							let percents = [];
							let string = "";

							if(result.length == 0){
								string = `
									<option value="">No Client Available</option>
								`;
							}
							else{
								string = `
									<option value="">Select Client</option>
								`;

								result.forEach(e => {
									percents[e.id] = e.percent;
									string += `
										<option value="${e.id}">${e.user.fname}</option>
									`;
								});
							}

							$('[name="branch_id"]').append(string);
							$('[name="branch_id"]').select2();
							$('[name="type"]').select2({
								tags: true
							});

							// $('[name="branch_id"]').change(e => {
							// 	$('[name="percent"]').val(percents[e.target.value]);
							// 	$('[name="percent"]').trigger('keyup');
							// });

							$("[name='amount'], [name='months'], [name='percent']").on('keyup', e => {
								let amount = $("[name='amount']").val();
								let percent = $("[name='percent']").val();
								let months = $("[name='months']").val();

								if(amount && percent && months){
									percent = percent / 100 / 12;
									amount = amount * -1;

									let n2 = (percent) * amount * Math.pow((1 + (percent)), months) / (1 - Math.pow((1 + (percent)), months));
									let n1 = n2 * months;

									$('#tPayment').html("₱" + numeral(n1).format('0,0.00'));
									$('#mPayment').html("₱" + numeral(n2).format('0,0.00'));
								}
							});
						}
					})
				},
				preConfirm: () => {
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;

			            if($('.swal2-container input:placeholder-shown').length || $('[name="branch_id"]').val() == "" || $('[name="type"]').val() == ""){
			                Swal.showValidationMessage('Fill all fields');
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();

					let formData = new FormData();
					formData.append('type', $("[name='type']").val());
					formData.append('branch_id', $("[name='branch_id']").val());
					formData.append('amount', $("[name='amount']").val());
					formData.append('percent', $("[name='percent']").val());
					formData.append('months', $("[name='months']").val());
					formData.append('collateral1', $("[name='collateral1']").val());
					formData.append('file1', $('[name="file1"]').prop('files')[0]);
					formData.append('collateral2', $("[name='collateral2']").val());
					formData.append('file2', $('[name="file2"]').prop('files')[0]);
					formData.append('collateral3', $("[name='collateral3']").val());
					formData.append('file3', $('[name="file3"]').prop('files')[0]);
					formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

					saveLoan(formData);
				}
			});
		}

		async function saveLoan(formData){
			await fetch('{{ route('loan.store') }}', {
			    method: "POST", 
			    body: formData,
			}).then(result => {
				ss("Success");
				reload();
			});
		}

		function showDetails(loan){
			console.log(loan);
			let pDetails = "";
			if(["For Payment", "Overdue", "Payment"].includes(loan.status)){
				pDetails = `
					<br>
					<br>
					<div class="row iRow">
					    <div class="col-md-4 iLabel">
					        Payment Channel
					    </div>
					    <div class="col-md-8 iInput">
					    	${loan.payment_channel}
					    </div>
					</div>

					<div class="row iRow">
					    <div class="col-md-4 iLabel">
					        Reference
					    </div>
					    <div class="col-md-8 iInput">
					    	${loan.reference}
					    </div>
					</div>
				`;
			}

			let disabled = "disabled";
			let disabled2 = "";
			if(loan.status == "Applied"){
				disabled = "";
				disabled2 = "disabled";
			}

			Swal.fire({
				html: `
					<div class="row iRow">
					    <div class="col-md-4 iLabel">
					        Client
					    </div>
					    <div class="col-md-8 iInput">
					        <select name="branch_id" class="form-control" disabled>
					        </select>
					    </div>
					</div>

					<div class="row iRow">
					    <div class="col-md-4 iLabel">
					        Type
					    </div>
					    <div class="col-md-8 iInput">
					        <select name="type" class="form-control" disabled>
					        	<option value="">Select Type</option>
					        	<option value="Personal Loan">Personal Loan</option>
					        	<option value="Housing Loan">Housing Loan</option>
					        	<option value="Business Loan">Business Loan</option>
					        	<option value="Car Loan">Car Loan</option>
					        	<option value="Student Loan">Student Loan</option>
					        	<option value="Debt Consolidation Loan">Debt Consolidation Loan</option>
					        	<option value="General Expense">General Expense</option>
					        </select>
					    </div>
					</div>

					${input("contract_no", "Contract Ref", loan.contract_no, 4, 8, 'text', 'disabled')}
					${input("amount", "Amount", loan.amount, 4, 8, 'number', 'disabled')}
					${input("percent", "Interest Rate", loan.percent, 4, 8, 'number', disabled)}
					${input("months", "Months", loan.months, 4, 8, 'number', 'min=1 max=60 ' + disabled)}

					${input("source_of_income", "Source of Income", loan.source_of_income, 4, 8, 'text', disabled)}
					${input("repayment_plan", "Repayment Plan", loan.repayment_plan, 4, 8, 'text', disabled)}
					${input("type_of_organization", "Org Type", loan.type_of_organization, 4, 8, 'text', disabled)}
					${input("work_name", "Work/Business Name", loan.work_name, 4, 8, 'text', disabled)}
					${input("work_address", "Address", loan.work_address, 4, 8, 'text', disabled)}
					${input("position", "Position", loan.position, 4, 8, 'text', disabled)}
					${input("salary", "Salary", loan.salary, 4, 8, 'number', disabled)}
					${input("date_of_employment", "Date of Employment", loan.date_of_employment, 4, 8, 'text', disabled)}
					${input("industry", "Industry", loan.industry, 4, 8, 'text', disabled)}
					${input("capitalization", "Capitalization", loan.capitalization, 4, 8, 'text', disabled)}
					${input("tin", "TIN", loan.tin, 4, 8, 'text', disabled)}

					<br>
					<br>

					${input("collateral1", "Collateral 1", loan.collateral1 ?? " ", 4, 8)}
				    <div class="row iRow">
		                <div class="col-md-4 iLabel">
		                    Upload File
		                </div>
		                <div class="col-md-6 iInput">
		                    <input type="file" name="file1" placeholder="Enter Upload File" class="form-control" value="" accept="image/*">
		                </div>
		                <div class="col-md-2 iInput">
		                	<a class="btn btn-success" data-toggle="tooltip" title="View" href='${loan.file1 ?? "javascript:void(0);"}' target="_blank">
		                		<i class="fas fa-search"></i>
		                	</a>
		                </div>
		            </div>

					${input("collateral2", "Collateral 2", loan.collateral2 ?? " ", 4, 8)}
				    <div class="row iRow">
		                <div class="col-md-4 iLabel">
		                    Upload File
		                </div>
		                <div class="col-md-6 iInput">
		                    <input type="file" name="file2" placeholder="Enter Upload File" class="form-control" value="" accept="image/*">
		                </div>
		                <div class="col-md-2 iInput">
		                	<a class="btn btn-success" data-toggle="tooltip" title="View" href='${loan.file2 ?? "javascript:void(0);"}' target="_blank">
		                		<i class="fas fa-search"></i>
		                	</a>
		                </div>
		            </div>

					${input("collateral3", "Collateral 3", loan.collateral3 ?? " ", 4, 8)}
				    <div class="row iRow">
		                <div class="col-md-4 iLabel">
		                    Upload File
		                </div>
		                <div class="col-md-6 iInput">
		                    <input type="file" name="file3" placeholder="Enter Upload File" class="form-control" value="" accept="image/*">
		                </div>
		                <div class="col-md-2 iInput">
		                	<a class="btn btn-success" data-toggle="tooltip" title="View" href='${loan.file3 ?? "javascript:void(0);"}' target="_blank">
		                		<i class="fas fa-search"></i>
		                	</a>
		                </div>
		            </div>

					<br>
					<br>

					<div class="row iRow">
					    <div class="col-md-4 iLabel">
					        Status
					    </div>
					    <div class="col-md-8 iInput">
					        <select name="status" class="form-control" ${disabled2}>
					        	@if(auth()->user()->role == "Super Admin")
					        	<option value="Approved">Approved</option>
					        	<option value="Disapproved">Disapproved</option>
					        	@endif
					        	<option value="Overdue">Overdue</option>
					        	<option value="Paid">Paid</option>
					        </select>
					    </div>
					</div>
					${pDetails}
					<br>
					<br>
					<div class="row iRow">
					    <div class="col-md-4 iLabel">
					        Total Payment
					    </div>
					    <div class="col-md-8 iInput" id="tPayment">
					    </div>
					</div>

					<div class="row iRow">
					    <div class="col-md-4 iLabel">
					        Monthly Payment
					    </div>
					    <div class="col-md-8 iInput" id="mPayment">
					    </div>
					</div>
				`,
				width: '500px',
				confirmButtonText: 'Save',
				showCancelButton: true,
				cancelButtonColor: errorColor,
				cancelButtonText: 'Cancel',
				didOpen: () => {
					$.ajax({
						url: "{{ route("branch.get") }}",
						data: {
							select: "*",
							load: ["user"]
						},
						success: result => {
							result = JSON.parse(result);
							let percents = [];
							let string = "";

							if(result.length == 0){
								string = `
									<option value="">No Client Available</option>
								`;
							}
							else{
								string = `
									<option value="">Select Client</option>
								`;

								result.forEach(e => {
									percents[e.id] = e.percent;
									string += `
										<option value="${e.id}">${e.user.fname}</option>
									`;
								});
							}

							$('[name="branch_id"]').append(string);
							$('[name="date_of_employment"]').flatpickr({
								altInput: true,
								altFormat: 'F j, Y',
								dateFormat: 'Y-m-d',
							});
							$('[name="branch_id"]').select2();
							$('[name="type"]').select2({
								tags: true
							});
							$('[name="type"]').append(`<option value="${loan.type}">${loan.type}</option>`);

							// $('[name="branch_id"]').change(e => {
							// 	$('[name="percent"]').val(percents[e.target.value]);
							// 	$('[name="percent"]').trigger('keyup');
							// });

							$("[name='amount'], [name='months']").on('keyup', e => {
								let amount = $("[name='amount']").val();
								let percent = $("[name='percent']").val();
								let months = $("[name='months']").val();

								if(amount && percent && months){
									let n2 = (amount * (percent / 100)) + (amount / months);
									let n1 = n2 * months;

									$('#tPayment').html("₱" + numeral(n1).format('0,0.00'));
									$('#mPayment').html("₱" + numeral(n2).format('0,0.00'));
								}
							});

							$('[name="branch_id"]').val(loan.branch_id).trigger('change');
							$('[name="months"]').trigger('keyup');


					        @if(auth()->user()->role == "Admin")
					        	$('[name="status"]').append(`<option value="${loan.status}">${loan.status}</option>`);
					        @endif

							$('[name="status"]').val(loan.status).trigger('change');
							$('[name="type"]').val(loan.type).trigger('change');
						}
					})
				},
				preConfirm: () => {
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;

			            if($('[name="branch_id"]').val() == "" || $('[name="type"]').val() == ""){
			                Swal.showValidationMessage('Fill all fields');
			            }

			            if($("[name='source_of_income']").val() == 0){
			                Swal.showValidationMessage('Months must not be 0');
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();

					let formData = new FormData();
					formData.append('id', loan.id);
					formData.append('status', $("[name='status']").val());
					formData.append('percent', $("[name='percent']").val());
					formData.append('months', $("[name='months']").val());
					formData.append('source_of_income', $("[name='source_of_income']").val());
					formData.append('repayment_plan', $("[name='repayment_plan']").val());
					formData.append('type_of_organization', $("[name='type_of_organization']").val());
					formData.append('work_name', $("[name='work_name']").val());
					formData.append('work_address', $("[name='work_address']").val());
					formData.append('position', $("[name='position']").val());
					formData.append('salary', $("[name='salary']").val());
					formData.append('date_of_employment', $("[name='date_of_employment']").val());
					formData.append('industry', $("[name='industry']").val());
					formData.append('capitalization', $("[name='capitalization']").val());
					formData.append('tin', $("[name='tin']").val());
					formData.append('collateral1', $("[name='collateral1']").val());
					formData.append('file1', $('[name="file1"]').prop('files')[0]);
					formData.append('collateral2', $("[name='collateral2']").val());
					formData.append('file2', $('[name="file2"]').prop('files')[0]);
					formData.append('collateral3', $("[name='collateral3']").val());
					formData.append('file3', $('[name="file3"]').prop('files')[0]);
					formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

					updateLoan(formData);
				}
			});
		}

		async function updateLoan(formData){
			await fetch('{{ route('loan.update2') }}', {
			    method: "POST", 
			    body: formData,
			}).then(result => {
				ss("Success");
				reload();
			});
		}

		function disburse(id){
			Swal.fire({
				html: `
					<div class="row iRow">
					    <div class="col-md-4 iLabel">
					        Payment Type
					    </div>
					    <div class="col-md-8 iInput">
					        <select name="payment_channel" class="form-control">
					        	<option value="">Select Type</option>
					        	<option value="Cash">Cash</option>
					        	<option value="Check">Check</option>
					        	<option value="E-Wallet">E-Wallet</option>
					        	<option value="Credit Card">Credit Card</option>
					        </select>
					    </div>
					</div>
					${input("reference", "Reference #", null, 4, 8)}
					${input("date_disbursed", "Date", null, 4, 8)}
				`,
				title: "Enter Details",
				width: '500px',
				confirmButtonText: 'Save',
				showCancelButton: true,
				cancelButtonColor: errorColor,
				cancelButtonText: 'Cancel',
				didOpen: () => {
					$('[name="date_disbursed"]').flatpickr({
					    altInput: true,
					    altFormat: 'F j, Y',
					    dateFormat: 'Y-m-d',
					})
				},
				preConfirm: () => {
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;

			            if($('.swal2-container input:placeholder-shown').length || $('[name="payment_channel"]').val() == ""){
			                Swal.showValidationMessage('Fill all fields');
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					$.ajax({
						url: "{{ route('transaction.store') }}",
						type: "POST",
						data: {
							loan_id: id,
							type: "DR",
							payment_channel: $("[name='payment_channel']").val(),
							reference: $("[name='reference']").val(),
							payment_date: $("[name='date_disbursed']").val(),
							_token: $('meta[name="csrf-token"]').attr('content')
						}
					});

					$.ajax({
						url: "{{ route('loan.update') }}",
						type: "POST",
						data: {
							id: id,
							payment_channel: $("[name='payment_channel']").val(),
							reference: $("[name='reference']").val(),
							date_disbursed: $("[name='date_disbursed']").val(),
							status: "For Payment",
							credited: 1,
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

		function pay(id){
			$.ajax({
				url: '{{ route('loan.get') }}',
				data: {
					select: "*",
					where: ['id', id],
					load: ['branch']
				},
				success: loan => {
					loan = JSON.parse(loan)[0];
					let rPayment = ((loan.amount * (loan.percent / 100)) + (loan.amount / loan.months)).toFixed(2);

					Swal.fire({
						html: `
							<div class="row iRow">
							    <div class="col-md-4 iLabel">
							        Select Transaction
							    </div>
							    <div class="col-md-8 iInput">
							        <select id="transaction" class="form-control">
							        </select>
							    </div>
							</div>
						`,
						confirmButtonText: "Save",
						showCancelButton: true,
						cancelButtonColor: errorColor,
						cancelButtonText: 'Cancel',
						didOpen: () => {
							$.ajax({
								url: '{{ route('transaction.get') }}',
								data: {
									where: ['type', 'CR'],
									where2: ['loan_id', null],
									select: '*'
								},
								success: result => {
									result = JSON.parse(result);
									let string = "";
									
									if(result.length){
										string += `
											<option value="">Select Transaction</option>
										`;

										result.forEach(trx => {
											string += `
												<option data-amount="${trx.amount}" value="${trx.id}">${trx.payment_channel} - ${trx.amount} (#${trx.trx_number})</option>
											`;
										});
									}
									else{
										string = `
											<option value="">No New Transactions</option>
										`;
									}

									$('#transaction').append(string);
									$('#transaction').select2();
								}
							})
						},
						preConfirm: e => {
						    swal.showLoading();
						    return new Promise(resolve => {
						    	let bool = true;

					            if($('#transaction').val() == ""){
					                Swal.showValidationMessage('Select Transaction');
					            }
					            // else{
					            // 	let payment = $('#transaction option:selected').data('amount');
			            		// 	if(payment < rPayment){
			            		// 		Swal.showValidationMessage('The payment is less than the required monthly payment');
			            		// 	}
					            // }

					            bool ? setTimeout(() => {resolve()}, 500) : "";
						    });
						},
					}).then(result => {
						if(result.value){
							let id = $('#transaction option:selected').val();

							update({
								url: "{{ route('transaction.update') }}",
								data: {
									id: id,
									user_id: loan.branch.id,
									loan_id: loan.id
								}
							},	() => {
								let payments = loan.payments;

								if(payments == null){
									payments = JSON.stringify([id]);
								}
								else{
									payments = JSON.parse(payments);
									payments.push(id);
								}

								update({
									url: "{{ route('loan.update') }}",
									data: {
										id: loan.id,
										payments: payments
									},
									message: "Success"
								},	() => {
									reload();
								});
							});
						}
					});
				}
			})
		}

		function payments(id){
			$.ajax({
				url: '{{ route('transaction.get') }}',
				data: {
					select: '*',
					where: ['loan_id', id],
					where2: ['type', 'CR']
				},
				success: result => {
					result = JSON.parse(result);
					let string = ``;
					let total = 0;

					if(result.length == 0){
						string = `
							<tr>
								<td colspan="4">No Payments Made</td>
							</tr>
						`;
					}
					else{
						result.forEach(payment => {
							total += payment.amount;

							string += `
								<tr>
									<td>${payment.id}</td>
									<td>₱${numeral(payment.amount).format("0,0.00")}</td>
									<td>${payment.payment_channel}</td>
									<td>${payment.trx_number}</td>
									<td>${moment(payment.payment_date).format(dateFormat2)}</td>
								</tr>
							`;
						});
					}

					Swal.fire({
						title: 'List of Payments',
						html: `
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th>ID</th>
										<th>Amount</th>
										<th>Payment Channel</th>
										<th>Reference Number</th>
										<th>Payment Date</th>
									</tr>
								</thead>
								<tbody id="payment-table">

								</tbody>
							</table>

							<div style="text-align: left; font-weight: bold;">
								<span>Total Payment: ₱${numeral(total).format("0,0.00")}</span>
							</div>
						`,
						didOpen: () => {
							$('#payment-table').append(string);
						},
						width: '800px'
					})
				}
			})
		}

		function matrix(id){
			$.ajax({
				url: '{{ route('loan.get') }}',
				data: {
					where: ['id', id],
					select: '*',
				},
				success: result => {
					let loan = JSON.parse(result)[0];
					
					let percent = loan.percent / 100 / 12;
					let amount = loan.amount * -1;
					let months = loan.months;

					let mPayment = (percent) * amount * Math.pow((1 + (percent)), months) / (1 - Math.pow((1 + (percent)), months));
					
					let string = ``;
					let balance = loan.amount;

					for(i = 0; i <= months; i++){
						if(i == 0){
							string += `
								<tr>
									<td>${i}</td>
									<td></td>
									<td></td>
									<td>0.00</td>
									<td>${numeral(balance).format('0,0.00')}</td>
									<td>${numeral(balance).format('0,0.00')}</td>
								</tr>
							`;
						}
						else{
							let temp = balance - (mPayment - (balance * percent));
							console.log(temp, mPayment);
							string += `
								<tr>
									<td>${i}</td>
									<td>${moment(loan.created_at).add(i, 'month').format('MM/DD/YY')}</td>
									<td>${numeral(mPayment - (balance * percent)).format('0,0.00')}</td>
									<td>${numeral(balance * percent).format('0,0.00')}</td>
									<td>${numeral(mPayment).format('0,0.00')}</td>
									<td>${numeral((temp + (balance * percent)) <= mPayment ? 0 : temp).format('0,0.00')}</td>
								</tr>
							`;
							balance = balance - (mPayment - (balance * percent));
						}
					}

					Swal.fire({
						title: 'Loan Matrix',
						width: '1000px',
						html: `
							<div style="text-align: left; font-weight: bold;">
								Loan Amount: ₱${numeral(amount * -1).format('0,0.00')}
								<br>
								Monthly Installment: ₱${numeral(mPayment).format('0,0.00')}
								<br>
								Contractual Rate (Monthly): ${numeral(percent * 100).format('0,0.00')}
								<br>
								No. of Monthly Installment: ${months}
							</div>

							<div>
								<table class="table table-hover">
									<thead>
										<tr>
											<th>Period</th>
											<th>Date</th>
											<th>Principal</th>
											<th>Interest</th>
											<th>Cash Flows</th>
											<th>Balance</th>
										</tr>
									</thead>
									<br>
									<tbody>
										${string}
									</tbody>
								</table>
							</div>
						`
					})
				}
			})
		}

		function del(id){
			sc("Confirmation", "Are you sure you want to delete?", result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('loan.delete') }}",
						data: {id: id},
						message: "Success"
					}, () => {
						reload();
					})
				}
			});
		}

		function exporto(){
			let data = {
				select: "*",
				load: ['branch.user'],
				filters: getFilters()
			};
			window.open("/export/loans?" + $.param(data), "_blank");
		}
	</script>
@endpush