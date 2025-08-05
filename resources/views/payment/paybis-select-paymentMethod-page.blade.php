<!doctype html>
<html lang="en">
  <head>
    <title>Select Payment Method</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!--SweetAlert2-->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    <div class="container my-3">
        <div class="p-2">
            <h4>Amount Entered: {{ $requestedAmount }}</h4>
        </div>
        <div class="p-2 ">
           Select Payment Methods: Quote Id: {{ $quoteId }}
        </div>
        <form action="{{ route('paybis3') }}" method="POST">
            @csrf
            <input type="text" name="quoteId" value="{{ $quoteId }}" hidden>
            <div class="mb-3">
                <label for="email">Enter Email:</label>
                <input type="email" class="form-input" name="email" value="" required>
            </div>
            
        @foreach($paymentMethodsAvailable as $pm)
        <div class="card mb-2">
            <div class="card-header">
               <h4><input type="radio" name="paymentMethod" id="paymentMethod" value="{{ $pm->id }}" @if($loop->first)checked @endif> {{ $pm->name }} </h4>
            </div>
            <div class="card-body">
               From <strong> {{ $pm->amountFrom->currencyCode }} {{ $pm->amountFrom->amount }} </strong>
               To <strong>{{ $pm->amountTo->currencyCode }} {{ $pm->amountTo->amount }}</strong> (i.e, Equal To {{ $pm->amountToEquivalent->currencyCode }} {{ $pm->amountToEquivalent->amount }})
               <hr class="my-1"/>
               
               <div class="row">
                    <div class="col-md-6">
                         Fees:
                         <div class="pl-3">
                            <div> Network Fees: {{ $pm->fees->networkFee->currencyCode }} {{ $pm->fees->networkFee->amount }}</div>
                            <div> Service Fee:  {{ $pm->fees->serviceFee->currencyCode }} {{ $pm->fees->serviceFee->amount }}</div>
                            <div> Total Fee:    {{ $pm->fees->totalFee->currencyCode }} {{ $pm->fees->totalFee->amount }}</div>
                         </div>
                        
                    </div>
                    <div class="col-md-6">
                         Fees: 
                         <div class="pl-3">
                            <div>  Network Fees:  {{ $pm->feesInCrypto->networkFee->currencyCode }} {{ $pm->feesInCrypto->networkFee->amount }}</div>
                            <div> Service Fee:   {{ $pm->feesInCrypto->serviceFee->currencyCode }} {{ $pm->feesInCrypto->serviceFee->amount }}</div>
                            <div>Total Fee:     {{ $pm->feesInCrypto->totalFee->currencyCode }} {{ $pm->feesInCrypto->totalFee->amount }}</div>
                         </div>
                        
                    </div>
                </div>  

            </div>
            <div class="card-footer">
                Expires at: {{ \Carbon\Carbon::parse($pm->expiresAt)->format('D, d/M/Y @ H:m') }}
            </div>
        </div>
        @endforeach
        <div><button type="submit" class="btn btn-success">Submit</button></div>
        </form>
    </div>
      
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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