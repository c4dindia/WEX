<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Trinity Payment - Sign In</title>
  <link href="{{ asset('newUI/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('newUI/css/fontawesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('newUI/css/register-login.css') }}" rel="stylesheet">
  <link rel="icon" href="{{ asset('newUI/images/favicon.png') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="container-fluid vh-100">
    <div class="row h-100">
      <div class="col-xxl-7 col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12 left-panel">
        <div class="leftpanel-logo">
          <img src="{{ asset('newUI/images/logo.png') }}" alt="" class="img-fluid">
        </div>
        <div class="leftpanel-text">
          <h2>Welcome Back</h2>
          <h6>Enter your email and password to access you account</h6>
        </div>
        <div class="left-section">
          <form method="POST" action="{{ route('clientLogin') }}" class="sign-in-form">
            @csrf
            <div class="form-group mb-4">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="Enter email" name="email">
            </div>
            <div class="form-group mb-2">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
            </div>
            <span class="" style="width: 100%;font-size: 12px;color: red;">
              @error('email')
                <div class="text-danger mt-2 mb-2" style="font-size: 14px;">{{ $message }}</div>
              @enderror
              @error('password')
                <div class="text-danger mt-2 mb-2" style="font-size: 14px;">{{ $message }}</div>
              @enderror
            </span>
            <div class="form-check mb-5 d-flex justify-content-end">
              <p><a href="#" class="text-dark">Forget your Password</a></p>
            </div>
            <div class="submit-btn">
              <button type="submit" class="btn btn-primary" disabled>Login</button>
            </div>
            <p class="text-center small mt-4">Don't have an Account? <a href="#" class="text-dark"><u>Register Now</u> </a></p>
          </form>
        </div>
      </div>
      <div class="col-xxl-5 col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 right-panel">
        <div class="rightpanel-logo">
          <img src="{{ asset('newUI/images/logo.png') }}" alt="" class="img-fluid">
        </div>
        <div class="rightpanel-text">
          <h1>Welcome to Trinity Payment Solutions</h1>
          <h6>Knowing your Business is our Business</h6>
        </div>
      </div>
    </div>
  </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const btn = document.querySelector('.submit-btn button');

    function toggleButton() {
      btn.disabled = !(email.value.trim() && password.value.trim());
    }

    email.addEventListener('input', toggleButton);
    password.addEventListener('input', toggleButton);
  });

  function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      toggleIcon.classList.remove('fa-eye');
      toggleIcon.classList.add('fa-eye-slash');
    } else {
      passwordInput.type = 'password';
      toggleIcon.classList.remove('fa-eye-slash');
      toggleIcon.classList.add('fa-eye');
    }
  }
</script>

@if (session('success'))
<script>
  Swal.fire({
    icon: 'success',
    title: '{{ session('success') }}',
    showConfirmButton: true,
    confirmButtonColor: '#7e1718'
  }).then((result) => {
    if (result.isConfirmed) {
      @php
          session()->forget('success');
      @endphp
      location.reload(); // Reload the page
    }
  });
</script>
@endif

@if (session('error'))
<script>
  Swal.fire({
    icon: 'error',
    title: '{{ session('error') }}',
    showConfirmButton: true,
    confirmButtonColor: '#7e1718'
  }).then((result) => {
  // Flush the session message
    @php
      session()->forget('error');
    @endphp
  });
</script>
@endif
</html>