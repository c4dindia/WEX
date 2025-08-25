@extends('layouts.clientMaster')

@section('title')
@php
$activePage ='expense card';
@endphp
Card Details
@endsection

@section('pagecontent')

<div class="body-content">
    <div class="body-content-header">
        @php
        $maskedCard = substr($card->card_number, 0, 4)
        . 'XXXXXXXX'
        . substr($card->card_number, -4);
        @endphp
        <h5>{{ $maskedCard }} Details</h5>
        <div class="d-flex align-items-center">
            <p>Download your card statements : <a href="{{ url('/download-card-statement') }}/{{ $card->id }}" class="text-light">Download</a></p>
        </div>
    </div>
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link" onclick="window.location.href = '/card/{{$card->id}}'">Card details</button>
            <button class="nav-link active" onclick="window.location.href = '/card/{{$card->id}}/payments'">Payments</button>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="card-iconn pb-0">
                <p class="mb-2"><b>Total number of payments:</b> {{ $cardStatements->count() }}</p>
                <div class="row ms-1">
                    <div class="col-xxl-1 col-xl-2 col-lg-2 col-md-2 col-sm-3 p-0" style="width: 15%;">
                        <div class="bg-white" style="padding: 12px; border-radius: 5px; text-align: center;">
                            <label for="fromDate">Select Date:</label>
                        </div>
                    </div>

                    <div class="col-xxl-5 col-xl-6 col-lg-10 col-md-10 col-sm-12">
                        <form action="{{ url('time-records-card-transaction') }}/{{ $card->id }}" method="POST" class="date-filter">
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
                                <th>Amount (USD)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($cardStatements) == 0)
                            <tr>
                                <td colspan="6" class="text-center">No Records found. </td>
                            </tr>
                            @else

                            @foreach ($cardStatements as $statement)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($statement->transaction_date)->format('d  M  Y')}}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('ClientCss/images/NullImage.png') }}" class="me-2 Wallester img-fluid">
                                        <h6 class="mb-0">{{ $card->cardholder_name }}</h6>
                                    </div>
                                </td>
                                <td>
                                    <p>{{ $statement->merchant_description }}</p>
                                </td>
                                <td>{{ $statement->transaction_id }}</td>
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
    </div>
    </nav>
</div>
@endsection

@section('scripts')

@endsection