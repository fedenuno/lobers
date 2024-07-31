@extends('layouts.blank')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group">
        <label for="emailaddress">{{ __('Email Address') }}</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="off" autofocus placeholder="{{ __('Enter your email') }}">
         @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-muted float-right"><small>{{ __('Forgot Your Password?') }}</small></a>
        @endif
        <label for="password">{{ __('Password') }}</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Enter your password') }}">
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <!--div class="form-group mb-3">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="custom-control-label" for="checkbox-signin">{{ __('Remember Me') }}</label>
        </div>
    </div-->
    <div class="form-group mb-0 text-center">
        <button class="btn btn-primary btn-block" type="submit"><i class="mdi mdi-login"></i> {{ __('Login') }} </button>
    </div>
</form>
@endsection