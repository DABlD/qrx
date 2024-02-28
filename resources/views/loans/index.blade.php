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
		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.loans') }}",
                	dataType: "json",
                	dataSrc: "",
					data: {
						select: "*",
						load: ['branch.user']
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
				]
				// drawCallback: function(){
				// 	init();
				// }
			});
		});

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
							$('[name="branch_id"], [name="type"]').select2();

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

			Swal.fire({
				html: `
					<div class="row iRow">
					    <div class="col-md-4 iLabel">
					        Branch
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
					${input("percent", "Interest Rate"	, loan.percent, 4, 8, 'number', 'disabled')}
					${input("months", "Months", loan.months, 4, 8, 'number', 'min=1 max=60 disabled')}

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
					        <select name="status" class="form-control">
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
									<option value="">No Branch Available</option>
								`;
							}
							else{
								string = `
									<option value="">Select Branch</option>
								`;

								result.forEach(e => {
									percents[e.id] = e.percent;
									string += `
										<option value="${e.id}">${e.user.fname}</option>
									`;
								});
							}

							$('[name="branch_id"]').append(string);
							$('[name="branch_id"], [name="type"]').select2();

							$('[name="branch_id"]').change(e => {
								$('[name="percent"]').val(percents[e.target.value]);
								$('[name="percent"]').trigger('keyup');
							});

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

							$('[name="status"]').val(loan.status).trigger('change');
							$('[name="type"]').val(loan.type).trigger('change');
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
					formData.append('id', loan.id);
					formData.append('status', $("[name='status']").val());
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
					${input("payment_channel", "Payment Channel", null, 4, 8)}
					${input("reference", "Reference #", null, 4, 8)}
				`,
				title: "Enter Details",
				width: '500px',
				confirmButtonText: 'Save',
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
	</script>
@endpush