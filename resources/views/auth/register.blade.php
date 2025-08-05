<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <title>
    Register
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css') }}" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">

    </div>
  </div>
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('../assets/img/illustrations/illustration-signup.jpg'); background-size: cover;">
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                  <h4 class="font-weight-bolder">Registration</h4>
                  <p class="mb-0">Enter your email and password to register</p>
                </div>
                <div class="card-body">
                    <form role="form" method="POST" action="{{ route('register') }}">
                      @csrf

                      <!-- Name Field -->
                      <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" style="background-color: #cec8c848" required autofocus autocomplete="name">
                        @error('name')
                          <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                      </div>

                      <!-- Email Field -->
                      <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}"  style="background-color: #cec8c848" required autocomplete="username">
                        @error('email')
                          <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                      </div>

                      <!-- Password Field -->
                      <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" class="form-control" type="password" name="password"  style="background-color: #cec8c848" required autocomplete="new-password">
                        @error('password')
                          <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                      </div>

                      <!-- Confirm Password Field -->
                      <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password_confirmation" class="form-control" type="password" name="password_confirmation"  style="background-color: #cec8c848" required autocomplete="new-password">
                        @error('password_confirmation')
                          <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                      </div>

                      <!-- Select Account Type-->
                      {{-- <div class="mb-3">
                        <div class="col-md-12">
                            <label for="account_type" class="form-label">Account Type: </label>
                        </div>
                        <div class="col-md-12">
                        <select class="form-select ml-3" id="account_type"
                            name="account_type" style="background-color: #cec8c848; width:100%" required>
                            <option selected>Select your Choice</option>
                            @foreach ($accountstype as $accountstype)
                                <option value="{{ $accountstype->id }}">
                                    {{ $accountstype->account }}</option>
                            @endforeach
                        </select>
                        </div>
                      </div> --}}

                      <!-- Submit Button -->
                      <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">Register</button>
                      </div>
                    </form>
                  </div>

                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-2 text-sm mx-auto">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">Log-In</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!--   Core JS Files   -->
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->

  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('assets/js/material-dashboard.min.js') }}"></script>
</body>

</html>
