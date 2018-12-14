@extends('layouts.admin_login')
@section('content')
<div class="content d-flex justify-content-center align-items-center">

	<!-- Login form -->
	<form class="login-form" method="post" action="{{ route('admin.login') }}" id="loginform">
		@csrf
		<div class="card mb-0">
			<div class="card-body">
				<div class="text-center mb-3">
					<i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
					<h5 class="mb-0">Login to your account</h5>
					<span class="d-block text-muted">Enter your credentials below</span>
				</div>

				<div class="form-group form-group-feedback form-group-feedback-left">
					<input type="email" name="email" required value="{{ old('email') }}" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email">
					<div class="form-control-feedback">
						<i class="icon-user text-muted"></i>
					</div>
					@if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
				</div>

				<div class="form-group form-group-feedback form-group-feedback-left">
					<input type="password" name="password" required class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{ old('password') }}" placeholder="Password">
					<div class="form-control-feedback">
						<i class="icon-lock2 text-muted"></i>
					</div>
					@if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 ml-2"></i></button>
				</div>

				<!-- <div class="text-center">
					<a href="login_password_recover.html">Forgot password?</a>
				</div> -->
			</div>
		</div>
	</form>
	<!-- /login form -->

</div>
@endsection