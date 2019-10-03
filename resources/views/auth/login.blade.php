@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    @include('layouts.headers.guest')

    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <strong>Support</strong><br>
                        </div>
                        <form role="form" method="POST" action="{{ route('login') }}">
                            @csrf
                            @if (isset($errors) && count($errors))

                                Nous rencontrons {{count($errors->all())}} erreur(s)
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }} </li>
                                    @endforeach
                                </ul>

                            @endif
                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }} mb-3">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" placeholder="{{ __('Identifiant') }}" type="text" name="name" value="{{ old('email') }}" required autofocus>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Mot de passe') }}" type="password" required>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="custom-control custom-control-alternative custom-checkbox">
                                <input class="custom-control-input" name="remember" id="customCheckLogin" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="customCheckLogin">
                                    <span class="text-muted">{{ __('Se souvenir de moi') }}</span>
                                </label>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">{{ __('Se connecter') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-3 text-center">
                    <div class="col-12">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-light">
                                <small>{{ __('Mot de passe oublié ?') }}</small>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
