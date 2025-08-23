@extends('layouts.clientMaster')

@section('title')
@php
$activePage = 'statements';
@endphp
Statements
@endsection

@section('css')
<style>
    .month-section p {
        background-color: #fff;
        padding: 10px;
        border-radius: 10px;
        border: none;
        box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;

    }

    .date-section {
        background-color: #fff;
        padding: 5px;
        border-radius: 10px;
    }

    .sub-date-section input {
        border: none;
        background-color: #f8d7da;
        border-radius: 10px;
        padding: 5px;
        color: #7e1718;
    }

    .go-btn,
    .go-btn:hover {
        background: #7e1718 !important;
        padding: 5px 10px !important;
        color: #fff !important;

    }
</style>
@endsection

@section('pagecontent')

<div class="body-content">
    <div class="body-content-header">
        <h5>Statements</h5>
        <div class="d-flex align-items-center">
            <p>Download your card statements : <a href="{{ route('userDownloadCardStatement') }}" class="text-light">Download</a></p>
        </div>
    </div>
    <hr>

    <div class="card-iconn py-0">
        <p class="mb-2"><b>Total number of payments:</b> {{ $transactions->count() }}</p>
        <div class="row ms-1">
            <div class="col-xxl-1 col-xl-2 col-lg-2 col-md-2 col-sm-3 p-0" style="width: 15%;">
                <div class="bg-white" style="padding: 12px; border-radius: 5px; text-align: center;">
                    <label for="fromDate">Select Date:</label>
                </div>
            </div>
            <div class="col-xxl-5 col-xl-6 col-lg-10 col-md-10 col-sm-12">
                <form action="{{ route('timeRecordsAccountStatement') }}" method="POST" class="date-filter">
                    @csrf

                    <input type="datetime-local" class="form-control" name="start_date" id="fromDate" value="2024-01-01T00:00">
                    <span>to</span>
                    <input type="datetime-local" class="form-control" name="end_date" id="toDate" value="{{ now()->format('Y-m-d\TH:i') }}">
                    <button type="submit" class="btn btn-go">Go</button>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th style="width: 20%;">Sender/Receiver</th>
                        <th style="width: 20%;">Description</th>
                        <th>Transaction ID</th>
                        <th>Card Number</th>
                        <th>Amount (USD)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($transactions) == 0)
                    <tr>
                        <td colspan="7" class="text-center">No Records found. </td>
                    </tr>
                    @else

                    @foreach ($transactions as $statement)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($statement->transaction_date)->format('d  M  Y')}}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('ClientCss/images/NullImage.png') }}" class="me-2 Wallester img-fluid">
                                <h6 class="mb-0">{{ $statement->cardholder_name }}</h6>
                            </div>
                        </td>
                        <td>
                            <p><b>{{ $statement->transaction_type }}</b></p>
                            <p>{{ $statement->merchant_description }}</p>
                        </td>
                        <td>{{ $statement->transaction_id }}</td>
                        <td>{{ substr($statement->card_number, -4) }}</td>
                        <td style="color: #CD1F1F;">-{{ $statement->billing_amount }}</td>
                        @if ($statement->is_credit == 'true')
                        <td class="text-success">Complete</td>
                        @else
                        <td class="text-danger">Pending</td>
                        @endif
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@endsection