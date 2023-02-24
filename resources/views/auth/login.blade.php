<!doctype html>
<html lang="en">
  <head>
  	<title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="{{asset('/assets/auth_login/css/style.css')}}">
	<link rel="shortcut icon" href="{{asset('assets/images/icon-digitran-logo.png')}}" />
	</head>
	<body class="img js-fullheight" style="background-image: url({{asset('/assets/auth_login/images/bg.jpg')}});">
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">LOGIN</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
		      	<h3 class="mb-4 text-center">DGT - TIMESHEET</h3>

		      	<form method="POST" action="{{ route('login') }}" class="signin-form">
				@csrf
					{{-- User name Đăng nhập --}}
		      		<div class="form-group">
		      			<input type="user_name" class="form-control 
						@error('user_name') is-invalid @enderror"
						name="user_name"  value="{{ old('user_name') }}"
						placeholder="Username" required>

						{{-- Hiển thị lỗi đăng nhập --}}
						@error('user_name')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
		      		</div>

					{{-- Password Đăng nhập --}}
					<div class="form-group">
						<input id="password-field" type="password" class="form-control
						@error('password') is-invalid @enderror
						"name="password" placeholder="Password" required>

						@error('password')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					<span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
					</div>

	            <div class="form-group">
	            	<button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
	            </div>
	            <div class="form-group d-md-flex">
	            	{{-- <div class="w-50">
		            	<label class="checkbox-wrap checkbox-primary">Remember Me
									  <input type="checkbox" checked>
									  <span class="checkmark"></span>
									</label>
								</div>
								<div class="w-50 text-md-right">
									<a href="#" style="color: #fff">Forgot Password</a>
								</div>
	            	</div> --}}
				</form>
				<p class="w-100 text-center">&mdash; DGT Welcome &mdash;</p>
				
				</div>
					</div>
				</div>
		</div>
	</section>

	<script src="{{asset('/assets/auth_login/js/jquery.min.js')}}"></script>
  <script src="{{asset('/assets/auth_login/js/popper.js')}}"></script>
  <script src="{{asset('/assets/auth_login/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('/assets/auth_login/js/main.js')}}"></script>

	</body>
</html>

