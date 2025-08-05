<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('ClientCss/images/neurosyncLogo.png') }}">
    <!-- bootstrap  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- external css    -->
    <link rel="stylesheet" href="{{ asset('ClientCss/frontpage.css') }}">
    
    <!-- SweetAlert2-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- <style>
       .swal2-container {
            z-index: 9999 !important; /* Set a high z-index */
        }
    </style> --}}
</head>
  <body>
    <!-- nave bar  -->
   <div class=" top-bg ">
   <header>
    <div class="container-fluid">
      <div class="row d-flex justify-content-between align-items-center">
        <div class="col-md-2">
          <div class="front-log-img">
            <img class="img-fluid" src="{{ asset('ClientCss/images/c4d-White.png') }}" alt="">
          </div>
        </div>
        <div class="col-md-8">
          <ul class="nav justify-content-center mt-4">
            <li class="nav-item">
              <a class="nav-link  active-nav" aria-current="page" href="{{ route('showFrontPage') }}">Home</a>
            </li>
            <li class="nav-item px-3">
              <a class="nav-link" href="#">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">About Us</a>
            </li>
        </ul>
        </div>
        <div class="col-md-2">
          <div class="card-login">
            @if(auth()->check())
                @if(auth()->user()->is_admin == "1")
                <a href="{{ route('showWallesterDashboard') }}">Go to Admin Dashboard</a>
                @else
                <a href="{{ route('showClientDashboard') }}">Go to Dashboard</a>
                @endif
            @else
                <a href="{{ route('showClientLogin') }}">Card Login</a>
            @endif
          </div>
        </div>
      </div>
    </div>
   </header>
   <!-- <div class="d-flex justify-content-between align-items-center">

   </div> -->

      <div class="container-fluid d-flex justify-content-center flex-column mt-5 mid-text" style="">
        <div class="row  ">
            <div class="col-md-12 ">
              <div class="d-flex justify-content-center flex-column">
               <h2 class="get-instant-txt">Get Instant Cards</h2>
               <p class="get-instant-para mt-3">Easily create unlimited virtual cards in seconds. Empower you or your business with secure, fast, and flexible payments.</p>
               <div class="grab-your-card" style="z-index: 1000001 !important;">
                <a href="{{ route('showClientRegister') }}" style="text-decoration: none">Grab Your Card</a>
               </div>
              </div>
            </div>
        </div>
      </div>

    <div class="images-container">

        <div class="row" >
          <div class="col-md-4 d-flex justify-content-end" >
            <img class="img-fluid left-icon-1" src="{{ asset('ClientCss/images/shopping_cart.png') }}" alt="" >
          </div>
          <div class="col-md-8"></div>
        </div>

        <div class="row" >
          <div class="col-md-4 d-flex justify-content-end">
            <img class="img-fluid left-icon-2" src="{{ asset('ClientCss/images/currency_pound.png') }}" alt="" >
          </div>
          <div class="col-md-8"></div>
        </div>

        <div class="row" >
          <div class="col-md-4 d-flex justify-content-end">
            <img class="img-fluid left-icon-3"  src="{{ asset('ClientCss/images/add_card.png') }}" alt="" style="">
          </div>
          <div class="col-md-8"></div>
        </div>

    </div>

        <div class="frame-img">
          <img class="img-fluid" src="{{ asset('ClientCss/images/Frame1o.png') }}" alt="" >
        </div>
        <div class="images-container">
          <div class="row" >
            <div class="col-md-8 d-flex justify-content-end">
              <img  class="img-fluid right-icon-1" src="{{ asset('ClientCss/images/account_balance_wallet.png') }}" alt="" style="">
            </div>
            <div class="col-md-4"></div>
          </div>
          <div class="row" >
            <div class="col-md-8 d-flex justify-content-end">
              <img class="img-fluid right-icon-2"  src="{{ asset('ClientCss/images/contactless.png') }}" alt=""  style="">
            </div>
            <div class="col-md-4"></div>
          </div>
          <div class="row" >
            <div class="col-md-8 d-flex justify-content-end">
              <img class="img-fluid right-icon-3"  src="{{ asset('ClientCss/images/flash_on.png') }}" alt="" style="">
            </div>
            <div class="col-md-4"></div>
          </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

     <!-- SweetAlert2 Js-->
     @if (session('success'))
     <script>
         Swal.fire({
             icon: 'success',
             title: '{{ session('success') }}',
             showConfirmButton: false,  // Hide the confirm button
             timer: 2500, // Closes after 3 seconds
             timerProgressBar: true,
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
            title: '{{ session("error") }}',
            showConfirmButton: false,  
            timer: 2500, // Closes after 3 seconds (3000 milliseconds)
            timerProgressBar: true, 
        });
    </script>
     @endif

</body>
</html>
