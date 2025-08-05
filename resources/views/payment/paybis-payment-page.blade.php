@extends('layouts.clientMaster')

@section('title')
@php
    $activePage = 'top up';
@endphp
Paybis Widget Test
@endsection

@section('css')
    <style>
      .dotdotclass {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
      }
    </style>

    <!--SweetAlert2-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('pagecontent')
{{-- @php
    dd($responseData);
@endphp --}}
<nav aria-label=" breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('showClientDashboard') }}"style="text-decoration: none; color:black">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('showClientsTopUpHistoryPage') }}"style="text-decoration: none; color:black">Top Up</a></li>
        <li class="breadcrumb-item breadcrumb-text-color"><a href="#" style="text-decoration: none;">Crypto Exchange</a></li>
    </ol>
</nav>
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between pb-2">


                <h4 class="dark-text-weight">Select Currencies for Crypto Exchange</h4>
                <div class="d-flex">
                    {{-- <button class="expensecard-import-btn mx-2 text-uppercase"  data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-plus"></i> &nbsp;Send Invite</button>Paystrax --}}
                </div>
            </div>
            <hr style="padding: 0; margin: 0;">
            <!-- <p class="mt-3">Total number of transactios</p> -->
        </div>
    </div>
    <div class="p-2 my-4 ">
        <div class="text-center justify-contents-center ">
            {{-- <a href="{{ url('/client-top-up-history') }}" class="text-end btn btn-primary p-2 my-3">Redirect Back To Website</a> --}}
        </div>
    
        <div class="p-4 container">
            <form method="POST" action="{{  route('getCryptoPaymentQuoteId') }}">
                @csrf
            
                <!-- Select for "From" Currency -->
                <div class="mb-3">
                    <label for="from_currency">From Currency</label>
                    <select id="from_currency" name="from_currency" class="form-control">
                        @foreach ($responseData->data[0]->pairs as $pair)
                            <option value="{{ $pair->from }}">{{ $pair->from }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Select for "To" Currency (initially empty) -->
                <div class="mb-3">
                    <label for="to_currency">To Currency</label>
                    <select id="to_currency" name="to_currency" class="form-control">
                        <!-- Options will be populated dynamically based on "from_currency" selection -->
                    </select>
                </div>
                
                
                <div class="mb-3">
                    <label for="amount">Enter Amount</label>
                    <input type="number" class="form-control" min="0" step="0.01" id="amount" name="amount" placeholder="Enter your Amount" required>    
                </div>
            
                <!-- Submit Button -->
                <div class="text-end">
                 <button type="submit" class="expensecard-import-btn mx-2 text-uppercase text-end">Submit</button>
                </div>
            </form>
        </div>
    </div>


   
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Function to populate "To Currency" select based on the "From Currency"
        $('#from_currency').on('change', function() {
            var fromCurrency = $(this).val();
            var toCurrencyOptions = [];

            // Find the matching pair from the data
            @php
                $pairs = $responseData->data[0]->pairs;
            @endphp

            @foreach ($pairs as $pair)
                if (fromCurrency === "{{ $pair->from }}") {
                    @foreach ($pair->to as $toCurrency)
                        toCurrencyOptions.push('<option value="{{ $toCurrency->currencyCode }}">{{ $toCurrency->currency }} ({{ $toCurrency->currencyCode }})</option>');
                    @endforeach
                }
            @endforeach

            // Populate the "To Currency" select with the new options
            $('#to_currency').html(toCurrencyOptions.join(''));
        });

        // Trigger change event on page load to populate "To Currency" initially
        $('#from_currency').trigger('change');
    });
</script>
@endsection
<!-- resources/views/sandbox.blade.php -->
{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paybis Widget Test</title>
    <!--SweetAlert2-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body onload="openWidget()"> --}}
    {{-- <div id="paybis-widget-container">
        <!-- This will be where the widget is shown -->
    </div> --}}

    {{-- <div class="text-center justify-contents-center py-5 my-5">
        <a href="{{ url('/client-top-up-history') }}" class="text-center btn btn-primary p-2 my-5">Redirect Back To Website</a>
    </div>

    <div class="p-4 container">
        <form method="POST" action="{{  route('getCryptoPaymentQuoteId') }}">
            @csrf
        
            <!-- Select for "From" Currency -->
            <div class="mb-3">
                <label for="from_currency">From Currency</label>
                <select id="from_currency" name="from_currency" class="form-control">
                    @foreach ($responseData->data[0]->pairs as $pair)
                        <option value="{{ $pair->from }}">{{ $pair->from }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Select for "To" Currency (initially empty) -->
            <div class="mb-3">
                <label for="to_currency">To Currency</label>
                <select id="to_currency" name="to_currency" class="form-control">
                    <!-- Options will be populated dynamically based on "from_currency" selection -->
                </select>
            </div>
            
            
            <div class="mb-3">
                <label for="amount">Enter Amount</label>
                <input type="number" min="0" step="0.01" id="amount" name="amount" placeholder="Enter your Amount">    
            </div>
        
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to populate "To Currency" select based on the "From Currency"
            $('#from_currency').on('change', function() {
                var fromCurrency = $(this).val();
                var toCurrencyOptions = [];
    
                // Find the matching pair from the data
                @php
                    $pairs = $responseData->data[0]->pairs;
                @endphp
    
                @foreach ($pairs as $pair)
                    if (fromCurrency === "{{ $pair->from }}") {
                        @foreach ($pair->to as $toCurrency)
                            toCurrencyOptions.push('<option value="{{ $toCurrency->currency }}">{{ $toCurrency->currency }}</option>');
                        @endforeach
                    }
                @endforeach
    
                // Populate the "To Currency" select with the new options
                $('#to_currency').html(toCurrencyOptions.join(''));
            });
    
            // Trigger change event on page load to populate "To Currency" initially
            $('#from_currency').trigger('change');
        });
    </script> --}}

    <!-- Paybis Sandbox Widget JavaScript Integration -->
    {{-- <script>
        ! function() {
            if (window.PartnerExchangeWidget = window.PartnerExchangeWidget || {
                    open(e) {
                        window.partnerWidgetSettings = {
                            immediateOpen: e
                        }
                    }
                }, "PartnerExchangeWidget" !== window.PartnerExchangeWidget.constructor.name) {
                (() => {
                    const e = document.createElement("script");
                    e.type = "text/javascript";
                    e.defer = true;
                    e.src = "https://widget.sandbox.paybis.com/partner-exchange-widget.js"; // Sandbox URL
                    const t = document.getElementsByTagName("script")[0];
                    t.parentNode.insertBefore(e, t);
                })();
            }
        }();
    </script>

    <script>
        function openWidget() {
            const requestId = '{{ $requestId }}'; // Replace with your unique request ID
            window.PartnerExchangeWidget.open({
                requestId: requestId
            });
        }
    </script> --}}
   
    {{-- @if (session('success'))
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
</html> --}}
