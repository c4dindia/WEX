<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login page</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('ClientCss/images/neurosyncLogo.png') }}">
    <!-- SweetAlert2-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- font awesome  -->
    <script src="https://kit.fontawesome.com/6036d46694.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <!-- external css file  -->
     <link rel="stylesheet" href="{{ asset('ClientCss/login.css') }}">
    <!-- boot starp  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>

    <div class="container-fluid">
        <div class="row">
          <!-- left side  -->
            <div class="col-md-6 login-left-side-bg">
                <div class="left-side-container">
                    <div class="login-logo">
                      <img class="mb-2" src="{{ asset('ClientCss/images/c4d-White.png') }}" alt="not found">
                      <span class="text-white-color">Latest Fintech services, at your fingertips</span>
                    </div>
                    <div class="login-card" alt="">
                      <img src="{{ asset('ClientCss/images/c4d_card.png') }}" alt="" style="border-radius: 10px;">
                    </div>
                    <div class="login-pText">
                    <p class="text-white-color">Instant Card Issue . Expense Card . Virtual Card</p>
                    </div>
                </div>
            </div>
            <!-- right side  -->
            <div class="col-md-6">
              <div class="right-side-container">
                <h2 class="login-heading-txt">Login</h2>
                <p class="login-para-text">Welcome to NeuroSync Technology. Kindly login<br><span>to continue!</span> </p>
               <div class="login-form">
                <form method="POST" action="{{ route('clientLogin') }}" class="sign-in-form">
                    @csrf
                  <div class="mb-3">
                    <label for="email" class="form-label px-2">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                  </div>
                  <div class="mb-3 ">
                    <label for="password" class="form-label px-2">Password</label>
                    <div class="d-flex align-items-center position-relative">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        <span class="input-icon" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword()">
                            <i id="toggleIcon" class="fa fa-eye"></i>
                        </span>
                    </div>
                    <span class="" style="width: 100%;font-size: 12px;color: red;">
                        @error('email')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                        @error('password')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </span>
                  </div>
                  <div class="d-flex align-items-center">
                    <input class="mb-0 mt-0" style="width: 15px;cursor:pointer;" type="checkbox" value="" id="termsCheckbox">&nbsp; &nbsp;
                    <span class="form-check-label mt-2" for="flexCheckDefault">
                        By clicking "Confirm", you confirm that you have read and understood the <a href="https://neurosyncventures.com/terms-and-conditions/" target="_blank">Terms and Conditions</a>
                    </span>
                  </div>
                <div class="login-btn">
                  <button type="submit" id="submitButton" disabled>Login</button>
                </div>
                </form>
               </div>
               <p class="dont-have-acc">Don’t have an Account? <a href="{{ route('showClientRegister') }}" class="fw-bold">Register Here</a></p>
              </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Show/Hide Password-->
    <script>
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

    <!-- Checkbox js-->
    <script>
        document.getElementById('termsCheckbox').addEventListener('change', function() {
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = !this.checked;
        });
    </script>

    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: true
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
            showConfirmButton: true
        }).then((result) => {
        // Flush the session message
        @php
            session()->forget('error');
        @endphp
    });
    </script>
    @endif

  </body>
</html>
