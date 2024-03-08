<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{ "QRX | " . "Application" }}</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="{{ asset('fonts/fontawesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/auth/animate.css') }}">
	<link rel="stylesheet" href="{{ asset('css/auth/hamburgers.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/auth/util.css') }}">
	<link rel="stylesheet" href="{{ asset('css/auth/main.css') }}">
	<link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

	<style>
	/*@if(isset($theme['login_bg_img']))
			.container-login100{
				background-image: url("{{ $theme['login_bg_img'] }}");
				background-size: cover;
				background-repeat: no-repeat;
				background-position: center center;
			}
	@endif*/

	.input100{
		display: inline;
		padding: 0 30px 0 30px;
	}
	</style>
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100" style="padding-top: 50px;">

				<div class="login100-pic js-tilt" data-tilt style="width: 100%; text-align: center;">
					<img src="qrx/img/FRONT2.jpg" alt="IMG" height="100px">
				</div>

				<br>
				<br>

				<form class="login100-form validate-form" method="POST" action="{{ route('application.create'); }}" style="width: 100%;">
					@csrf
					<span class="login100-form-title">
						Loan Application
					</span>

					<h2>
						<b>Personal Details</b>
					</h2>
					<br>
					<div class="row">
					    <input type="text" name="fname" placeholder="First Name" class="input100" style="width: 32%;">
					    <input type="text" name="mname" placeholder="Middle Name" class="input100" style="width: 32%;">
					    <input type="text" name="lname" placeholder="Last Name" class="input100" style="width: 32%;">
					    <br><br>
					    <input type="text" name="address" placeholder="Enter Address" class="input100" style="width: 100%;">
					</div>

					<div class="row">
					    <br>
					    <input type="text" name="contact" placeholder="Enter Contact" class="input100" style="width: 49%;">
					    <input type="email" name="email" placeholder="Enter Email" class="input100" style="width: 49%;">
					</div>

					<div class="row">
					    <br>
					    <input type="text" name="birthday" placeholder="Select Birth Date" class="input100" style="width: 49% !important;">
					    <select class="input100" name="gender" style="width: 49%;">
					    	<option value="">Select Gender</option>
					    	<option value="Male">Male</option>	
					    	<option value="Female">Female</option>	
					    </select>
					</div>

					<br>
					<h2>
						<b>Loan Details</b>
					</h2>

					<div class="row">
					    <br>
					    <input type="number" name="amount" placeholder="Loan Amount" class="input100" style="width: 49%;" min="0">
					    <input type="text" name="use_of_loan" placeholder="Loan Purpose" class="input100" style="width: 49%;">
					</div>

					<div class="row">
					    <br>
					    <input type="text" name="source_of_income" placeholder="Source of Income" class="input100" style="width: 49%;">
					    <select class="input100" name="repayment_plan" style="width: 49%;">
					    	<option value="">Select Repayment Plan</option>
					    	<option value="Lumpsum">Lumpsum</option>	
					    	<option value="Installment">Installment</option>	
					    </select>
					</div>

					<br>
					<h2>
						<b>Work Details</b>
					</h2>

					<br>
					Select which applies to you
					<br>
					<input type="radio" name="work" value="Employee"> Employee
					&nbsp;&nbsp;&nbsp;
					<input type="radio" name="work" value="Business"> Business Owner
					<br>

					<select class="input100" name="type_of_organization" style="width: 49%;">
						<option value="">Enter type of organization</option>
						<option value="Government">Government</option>	
						<option value="Private Company">Private Company</option>	
					</select>

					<br>
					<br>
					<input type="text" name="work_name" placeholder="Employer/Business Name" class="input100">
					<br>
					<br>
					<input type="text" name="work_address" placeholder="Employer/Business Address" class="input100">
					<br>
					<br>

					<div id="work_details1" class="wdb">
						<div class="row">
							<br>
							<input type="text" name="position" placeholder="Position" class="input100" style="width: 49%;">
							<input type="number" name="salary" placeholder="Salary" class="input100" style="width: 49%;">
						</div>

						<div class="row">
							<br>
							<input type="text" name="date_of_employment" placeholder="Date of Employment" class="input100" style="width: 49%;">
						</div>
					</div>

					<div id="work_details2" class="wdb">
						<div class="row">
							<br>
							<input type="text" name="industry" placeholder="Industry" class="input100" style="width: 49%;">
							<input type="number" name="capitalization" placeholder="Capitalization" class="input100" style="width: 49%;">
						</div>

						<div class="row">
							<br>
							<input type="text" name="tin" placeholder="TIN" class="input100" style="width: 49%;">
						</div>
					</div>
					
					{{-- END --}}
					<div style="text-align: center;">
						<div class="container-login100-form-btn" style="width: 50%; margin: 0 auto;">
							<button class="login100-form-btn">
								Apply
							</button>
						</div>
					</div>


					<div class="text-center p-t-136" style="visibility: hidden;">
						<a class="txt2" href="{{ route('register') }}">
							Create your account
							<i class="fas fa-arrow-right"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-bundle.min.js') }}"></script>
	<script src="{{ asset('js/auth/tilt.js') }}"></script>
	<script src="{{ asset('js/auth/main.js') }}"></script>
	<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
	<script src="{{ asset('js/flatpickr.min.js') }}"></script>
	<script src="{{ asset('js/moment.min.js') }}"></script>
	<script src="{{ asset('js/select2.min.js') }}"></script>

	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})

		$(document).ready(() => {
			$('.wdb').hide();

			$('[name="birthday"], [name="date_of_employment"]').flatpickr({
			    altInput: true,
			    altFormat: 'F j, Y',
			    dateFormat: 'Y-m-d',
			    maxDate: moment().format("YYYY-MM-DD")
			});

			$('[name="type_of_organization"]').select2({
				tags: true
			});
			$('[name="birthday"], [name="date_of_employment"]').next().css('width', '49.5%');

			$('[name="work"]').on('change', e => {
				let selected = $('[name="work"]:checked').val();
				$('.wdb').hide();

				if(selected == "Employee"){
					$('#work_details1').show();
				}
				else{
					$('#work_details2').show();
				}
			});
		});

		@if($errors->all())
			Swal.fire({
				icon: 'success',
                html: `
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br/>
                    @endforeach
                `,
			});
		@endif
	</script>

</body>
</html>