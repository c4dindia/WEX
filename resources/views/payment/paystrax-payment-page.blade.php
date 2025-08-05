@extends('layouts.clientMaster')

@section('title')
@php
    $activePage = 'top up';
@endphp
    Pay Strax
@endsection

@section('css')
    <style>
        #gpay-button-online-api-id {
            width: 100%;
        }
    </style>
@endsection

@section('pagecontent')

<nav aria-label=" breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('showClientDashboard') }}"style="text-decoration: none; color:black">Home</a></li>
        <li class="breadcrumb-item breadcrumb-text-color"><a href="{{ route('showClientsTopUpHistoryPage') }}" style="text-decoration: none;">Top Up</a></li>
        <li class="breadcrumb-item breadcrumb-text-color"><a href="#" style="text-decoration: none;">Payment</a></li>
    </ol>
</nav>
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between pb-2">
                <h4 class="dark-text-weight"> Payment: <img src="{{ asset('assets/img/logos/visa.png') }}" alt="" height="40px">&nbsp;<img src="{{ asset('assets/img/logos/mastercard.png') }}" alt="" height="40px"></h4>
                <div>

                </div>
            </div>
            <hr style="padding: 0; margin: 0;">
            <!-- <p class="mt-3">Total number of transactios</p> -->
        </div>
    </div>
    <div class="p-2 mt-2 ">
        <div>
            <form action="{{ route('paymentResult') }}" class="paymentWidgets" data-brands="VISA MASTER AMEX" method="get">
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')

{{-- pay strax scripts --}}
<script src="https://code.jquery.com/jquery.js" type="text/javascript"></script>

<script type="text/javascript">
    // Pass the $chkId value safely from PHP to JavaScript
    var checkoutId = @json($chkId->id);
    var integrity = @json($chkId->integrity);
    console.log(checkoutId);
    console.log(integrity);

    // Dynamically create the script tag for the paymentWidgets.js script
    var script = document.createElement('script');
    script.src = `https://eu-prod.oppwa.com/v1/paymentWidgets.js?checkoutId=${checkoutId}`;
    script.type = 'text/javascript';
    script.integrity = `${integrity}`;
    script.crossOrigin = "anonymous";
    document.body.appendChild(script);
</script>
<script>
    var wpwlOptions = {
        locale: "en",
        iframeStyles: {
            'card-number-placeholder': {
                'color': '#ff0000',
                'font-size': '16px',
                'font-family': 'monospace'
            },
            'cvv-placeholder': {
                'color': '#0000ff',
                    'font-size': '16px',
                    'font-family': 'Arial'
            }
        }
    }
</script>

@endsection
