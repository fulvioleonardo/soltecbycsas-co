@extends('tenant.layouts.auth')

@section('content')
<section class="auth auth__form-{{ $login->position_form }}">
    @include('tenant.auth.partials.side_left')
    <article class="auth__form">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="d-flex justify-content-center">
                <div class="row">
                    @if ($useLoginGlobal)
                        @if ($login->logo ?? false)
                            @if ($login->show_logo_in_form)
                                <img class="auth__logo-form" src="{{ $login->logo }}" alt="Logo formulario" />
                            @endif
                        @endif
                    @else
                        @if ($login->show_logo_in_form)
                            @if($company->logo)
                                <img class="auth__logo-form" src="{{ asset('storage/uploads/logos/' . $company->logo) }}" alt="Logo formulario" width="250" />
                            @else
                                <img class="auth__logo-form" src="{{asset('logo/tulogo.png')}}" alt="Logo formulario" width="250" />
                            @endif
                        @endif
                    @endif
                    <br>
                </div>
            </div>
            <div class="text-center">
                <h1 class="auth__title">Bienvenido a<br>{{ $company->trade_name }}</h1>
                <p>Ingresa a tu cuenta</p>
            </div>
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" autofocus>
                @if ($errors->has('email'))
                    <div div class="invalid-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>
            <div class="form-group">
                <div class="d-flex justify-content-between">
                    <label for="password">Contraseña</label>
                    <a href="{{ url('password/reset') }}" tabindex="5">¿Has olvidado tu contraseña?</a>
                </div>
                <div class="position-relative">
                    <input type="password" name="password" id="password" class="form-control hide-password {{ $errors->has('password') ? 'is-invalid' : '' }}">
                    <button type="button" class="btn btn-eye" id="btnEye" tabindex="4">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                @if ($errors->has('password'))
                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                @endif
            </div>
            <button type="submit" class="btn btn-signin btn-block">INICIAR SESIÓN</button>
        </form>
    </article>
</section>
@endsection

@push('scripts')
    <script>
        var inputPassword = document.getElementById('password');
        var btnEye = document.getElementById('btnEye');
        btnEye.addEventListener('click', function () {
            if (inputPassword.classList.contains('hide-password')) {
                inputPassword.type = 'text';
                inputPassword.classList.remove('hide-password');
                btnEye.innerHTML = '<i class="fa fa-eye-slash"></i>'
            } else {
                inputPassword.type = 'password';
                inputPassword.classList.add('hide-password');
                btnEye.innerHTML = '<i class="fa fa-eye"></i>'
            }
        });
    </script>
@endpush