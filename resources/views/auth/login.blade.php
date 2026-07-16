@extends('layouts.simple')

@section('content')

@section('title', 'Login')
@section('description', 'Login to your account')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/oneui.min.css') }}">
@endpush

{{--  @section('content')  --}}
<div id="page-container">
    <main id="main-container">
        @php
        $photoUrl = asset('/media/photos/photo28@2x.jpg');
    @endphp

    <div class="bg-image" style="background-image: url('{{ $photoUrl }}');">            <div class="row g-0 bg-primary-dark-op">

          {{-- Meta Info Section --}}
          <div class="hero-static col-lg-4 d-none d-lg-flex flex-column justify-content-center">
            <div class="p-4 p-xl-5 flex-grow-1 d-flex align-items-center">
              <div class="w-100">
                <a class="link-fx fw-semibold fs-2 text-white" href="{{ url('/') }}">
                  Icon Villas
                </a>
                <p class="text-white-75 me-xl-8 mt-2">
                  Welcome to Icon Villas Accounting and Financial System.
                </p>
              </div>
            </div>
            {{--  <div class="p-4 p-xl-5 d-xl-flex justify-content-between align-items-center fs-sm">
              <p class="fw-medium text-white-50 mb-0">
                <strong>OneUI 5.11</strong> &copy; <span data-toggle="year-copy"></span>
              </p>
              <ul class="list list-inline mb-0 py-2">
                <li class="list-inline-item"><a class="text-white-75 fw-medium" href="#">Legal</a></li>
                <li class="list-inline-item"><a class="text-white-75 fw-medium" href="#">Contact</a></li>
                <li class="list-inline-item"><a class="text-white-75 fw-medium" href="#">Terms</a></li>
              </ul>
            </div>  --}}
          </div>
          {{-- END Meta Info Section --}}

          {{-- Main Section --}}
          <div class="hero-static col-lg-8 d-flex flex-column align-items-center bg-body-extra-light">
            <div class="p-3 w-100 d-lg-none text-center">
              <a class="link-fx fw-semibold fs-3 text-dark" href="{{ url('/') }}">
                OneUI
              </a>
            </div>

            <div class="p-4 w-100 flex-grow-1 d-flex align-items-center">
              <div class="w-100">
                {{-- Header --}}
                <div class="text-center mb-5">
                    <img src="{{ asset('media/photos/icon-villas-logo.png') }}" alt="Villas Logo" style="height: 50px;">

                  {{--  <p class="mb-3"><i class="fa fa-2x fa-circle-notch text-primary-light"></i></p>  --}}
                  <h1 class="fw-bold mb-2">Sign In</h1>
                  {{--  <p class="fw-medium text-muted">
                    Welcome, please login or <a href="{{ route('register') }}">sign up</a> for a new account.
                  </p>  --}}
                </div>
                {{-- END Header --}}

                {{-- Sign In Form --}}
                <div class="row g-0 justify-content-center">
                  <div class="col-sm-8 col-xl-4">
                    <form class="js-validation-signin" action="{{ route('login') }}" method="POST">
                      @csrf
                      {{-- Username or Email --}}
                      <div class="mb-4">
                        <input type="text" name="email" class="form-control form-control-lg form-control-alt py-3" placeholder="Email" value="{{ old('email') }}" required autofocus>
                      </div>

                      {{-- Password --}}
                      <div class="mb-4">
                        <input type="password" name="password" class="form-control form-control-lg form-control-alt py-3" placeholder="Password" required>
                      </div>

                      {{-- Forgot + Submit --}}
                      <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                          <a class="text-muted fs-sm fw-medium d-block d-lg-inline-block mb-1" href="{{ route('password.request') }}">
                            Forgot Password?
                          </a>
                        </div>
                        <div>
                          <button type="submit" class="btn btn-lg btn-alt-primary">
                            <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> Sign In
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                {{-- END Sign In Form --}}
              </div>
            </div>

            {{-- Footer for mobile --}}
            {{--  <div class="px-4 py-3 w-100 d-lg-none d-flex flex-column flex-sm-row justify-content-between fs-sm text-center text-sm-start">
              <p class="fw-medium text-black-50 py-2 mb-0">
                <strong>OneUI 5.11</strong> &copy; <span data-toggle="year-copy"></span>
              </p>
              <ul class="list list-inline py-2 mb-0">
                <li class="list-inline-item"><a class="text-muted fw-medium" href="#">Legal</a></li>
                <li class="list-inline-item"><a class="text-muted fw-medium" href="#">Contact</a></li>
                <li class="list-inline-item"><a class="text-muted fw-medium" href="#">Terms</a></li>
              </ul>
            </div>
          </div>  --}}
          {{-- END Main Section --}}
        </div>
      </div>
    </main>
  </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/op_auth_signin.min.js') }}"></script>
    <script src="{{ asset('assets/js/oneui.app.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof One !== 'undefined') {
                One.helpers('core-browser');
            }
        });
    </script>
@endpush
