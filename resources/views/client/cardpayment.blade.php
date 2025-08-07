@extends('layouts.clientMaster')

@section('title')
@php
$activePage ='null';
@endphp
Card Details
@endsection

@section('css')

@endsection

@section('pagecontent')

{{-- Page Content --}}
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('showClientDashboard') }}" style="text-decoration: none; color:black">Home</a></li>
        <li class="breadcrumb-item">
            <a href="{{ url('cards') }}" style="text-decoration: none; color:black">Expense Cards</a>
        </li>
        <li class="breadcrumb-item breadcrumb-text-color " aria-current="page"><a href="#" style="text-decoration: none;">Card Payments</a></li>
    </ol>
</nav>
<section>
    @php
    $cU_currency_code = '$';
    @endphp

    <div class="row">
        <div class="col-md-12">
            <h4 class="  dark-text-weight">{{ $card->masked_card_number }} Details</h4>
            <nav id="menu" class="p-0 mt-4 mb-3">
                <ul class="d-flex gap-3 p-0 m-0">
                    <li class="tab-1 card-details"><a href="{{ url('/card') }}/{{ $card->id }}" class="normal ">CARD DETAILS</a></li>
                    <li class="tab-1 payments-details"><a href="{{ url('/card') }}/{{ $card->id }}/payments" class="normal active">PAYMENTS</a>
                    </li>
                </ul>
                <hr style="padding: 0; margin: 0;">
            </nav>

            <!-- <h5>Card Statements</h5> -->
            <p><span class="fw-bold">Total number of payments:</span> {{ count($cardStatements) }} </p>

            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex gap-2">
                    <div class="month-section">
                        <p class="mb-0">Select Date:</p>
                    </div>
                    <div class="date-section">
                        <form action="{{ url('time-records-card-transaction') }}/{{ $card->id }}" method="POST">
                            @csrf
                            <div class="d-flex gap-2 align-items-center sub-date-section">
                                <input type="datetime-local" name="start_date" id="" class="acnt-statement-sel-2" value="2024-01-01T00:00:00">
                                <p class="mb-0">to</p>
                                <input type="datetime-local" name="end_date" id="" class="acnt-statement-sel-2" value="{{ now()->format('Y-m-d\TH:i') }}">
                                <button type="submit" class="btn first go-btn">Go</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div>
                    <a href="{{ url('/download-card-statement') }}/{{ $card->id }}" class="generate-btn"><i class="fa-solid fa-circle-arrow-down" aria-hidden="true"></i>&nbsp;Download Card Statement</a>
                </div>
            </div>
        </div>
        <!-- table  -->
        <div class="p-2 mt-2 ">
            <div class="table-wrapper scrollable-table table-responsive">
                <table class="table table-striped mt-3 rounded-4">
                    <thead>
                        <tr>
                            <th class="p-3 text-center" style="color: #5a5a5a; width:10% !important">Date</th>
                            <th class="p-3" style="color: #5a5a5a">Sender/Receiver</th>
                            <th class="p-3" style="color: #5a5a5a; width:25% !important">Description</th>
                            <th class="p-3" style="color: #5a5a5a; width:25%;">Transaction ID</th>
                            <th class="p-3 text-center" style="color: #5a5a5a; width:10% !important">Amount ({{ $cU_currency_code }})</th>
                            <th class="p-3 text-center" style="color: #5a5a5a; width:8% !important">Status</th>
                        </tr>
                    </thead>
                    <tbody style="max-height: 420px;">
                        @if (count($cardStatements) == 0)
                        <tr>
                            <td colspan="6" class="text-center">No Records found. </td>
                        </tr>
                        @else
                        @foreach ($cardStatements as $cardTransaction)
                        <tr>
                            <td class="text-center" style="width:10% !important">{{ \Carbon\Carbon::parse($cardTransaction->transaction_date)->format('d M Y')}}</td>
                            <td class="">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="acc-detail-table-img ">
                                        @if ($cardTransaction->icon_url != null)
                                        <img src="{{ $cardTransaction->icon_url }}" alt="Icon">
                                        @else
                                        <img src="{{ asset('ClientCss/images/NullImage.png') }}" alt="Icon">
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column align-items-start">
                                        <h6 class="company-name-table">{{ $card->cardholder_name }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td style="width:25% !important">
                                {{ $cardTransaction->merchant_description }}
                            </td>
                            <td style=" width:25%;">
                                <div>
                                    <span class="mb-0 ">{{ $cardTransaction->transaction_id }}</span>
                                </div>
                            </td>
                            <td class="text-center" style="width:10% !important">
                                <div>
                                    <h6 class="mb-0 acc-detail-gbp-h">{{ $cU_currency_code }}{{ $cardTransaction->billing_amount }}</h6>
                                </div>
                            </td>
                            <td class=" text-center" style="width:8% !important">
                                @if ($cardTransaction->is_credit == 'true')
                                <strong class="text-success">Complete</strong>
                                @else
                                <strong class="default-red-color">Pending</strong>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')

@endsection