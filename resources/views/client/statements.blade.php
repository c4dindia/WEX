@extends('layouts.clientMaster')

@section('title')
@php
$activePage = 'statements';
@endphp
Statements
@endsection

@section('css')
{{-- statements page css --}}
{{-- <style>
        .c4d-table-data th {
            font-weight: 400;
        }
        .statement-th th {
            font-weight: 300;
        }
        .content i {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 40px;
            border-radius: 5px;
            margin-right: 10px;
            background-color: #FEFBF6;
        }

        .acnt-statement-p-1 p {
            padding: 5px 10px;
            border: none;
            text-align: center;
            border-radius: 21px;
            background-color: #F3F2FF;
            box-shadow: var(--light-box-shadow);
            outline: none;
        }
    </style> --}}
@endsection

@php
$cU_currency_code = '€';
@endphp


@section('pagecontent')
<!-- start content -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('showClientDashboard') }}" style="text-decoration: none; color:black">Home</a></li>
        <li class="breadcrumb-item breadcrumb-text-color"><a href="#" style="text-decoration: none;">Account Statements</a></li>
    </ol>
</nav>
<section>
    <div class="row d-flex align-items-center justify-content-between mb-2">
        <div class="col-md-6">
            <h4 class="dark-text-weight">Statements</h4>
        </div>
        <hr style="padding: 0; margin: 0;">
    </div>

    <div class="row">
        <div class="col-md-12">

            <p class=""><span class="fw-bold">Total number of payments: </span> {{ count($transactions) }}</p>

            <div class="row d-flex align-items-center justify-content-between gap-2">
                <div class="col-md-6 col-lg-7 col-xl-6 d-flex gap-2">
                    <div class="month-section">
                        <p class="mb-0">Select Date:</p>
                    </div>
                    <div class="date-section d-flex align-items-center">
                        <form action="{{ route('timeRecordsAccountStatement') }}" method="POST">
                            @csrf
                            <div class="d-flex gap-2 align-items-center sub-date-section">
                                <input type="datetime-local" name="start_date" id="" class="acnt-statement-sel-2" value="2024-01-01T00:00:00">
                                <p class="mb-0">to</p>
                                <input type="datetime-local" name="end_date" id="" class="acnt-statement-sel-2" value="{{ now()->format('Y-m-d\TH:i') }}">
                                <button type="submit" class="btn go-btn text-uppercase">Go</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <div class="col-md-6 col-lg-5 col-xl-5 d-flex justify-content-lg-start justify-content-xl-end justify-content-md-start">
                    <a href="{{ url('/client-statements/getAccountStatements') }}/1" class="generate-btn text-uppercase">Generate Account Statement</a>
                </div> -->
            </div>
        </div>

        <!-- table -->
        <div class="p-2 mt-2 ">
            <div class="table-responsive scrollable-table"> {{-- table-wrapper scrollable-table --}}
                <table class="table table-striped mt-2 rounded-4">
                    <thead>
                        <tr>
                            <th class="py-3 text-center th-1-w" style="color: #5a5a5a;">Date</th>
                            <th class="py-3" style="color: #5a5a5a; width: 21%;">Sender/Receiver</th>
                            <th class="py-3" style="color: #5a5a5a; width: 35%;">Description</th>
                            <th class="py-3" style="color: #5a5a5a;">Transaction ID</th>
                            <th class="py-3 text-center" style="color: #5a5a5a; width: 10%;">Amount ({{ $cU_currency_code }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($transactions) == 0)
                        <tr>
                            <td colspan="6" class="text-center">No Records </td>
                        </tr>
                        @else
                        @foreach ($transactions as $statement)
                        <tr>
                            <td class="text-center th-1-w"> {{ \Carbon\Carbon::parse($statement->date)->format('d M Y')}}</td>
                            <td class="" style="width: 21%;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="acc-detail-table-img ">
                                        @if ($statement->icon_url != null)
                                        @if ($statement->icon_url == 'https://wallester-production-client-files.s3.eu-west-1.amazonaws.com/wallester-icon64x64.png')
                                        <img src="{{ asset('ClientCss/images/NullImage.png') }}" alt="Mrchnt_Img">
                                        @else
                                        <img src="{{ $statement->icon_url }}" alt="merch Icon">
                                        @endif
                                        @else
                                        <img src="{{ asset('ClientCss/images/NullImage.png') }}" alt="img">
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column align-items-start">
                                        <h6 class="company-name-table truncate-text" title="{{ $statement->source_destination }}">
                                            {{ $statement->source_destination }}
                                        </h6>
                                        <p class="acc-detail-table-p mb-0 truncate-text"><small>{{ Str::title(strtolower(str_replace('_', ' ', $statement->type))) }}</small></p>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 35%;">
                                <div>
                                    <h6 class="acc-table-description-hed">{{ Str::title(strtolower(str_replace('_', ' ', $statement->type))) }}</h6>
                                    <p class="acc-table-description-p me-2">{{ $statement->description }}</p>
                                </div>
                            </td>
                            <td class="" style="">
                                <div>
                                    <span class="mb-0">{{ $statement->transaction_id }}</span>
                                </div>
                            </td>
                            <td style="width: 10%;" class="text-center">
                                <div>
                                    @if ($statement->credit != null)
                                    <h6 class="mb-0 acc-detail-gbp-h-green">{{ $cU_currency_code }} {{ $statement->credit }}</h6>
                                    @else
                                    <h6 class="mb-0 acc-detail-gbp-h">- {{ $cU_currency_code }} {{ $statement->debit }}</h6>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif

                    </tbody>

                    @if (count($transactions) != 0)
                    <tfoot>
                        <tr>
                            <td></td>
                            <td class=""></td>
                            <td class=""></td>
                            <td class="text-center">Debit Turnover</td>
                            <td>
                                <h6 class="mb-0 acc-detail-gbp-h">- {{ $cU_currency_code }} {{ $totalDebit }} </h6>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class=""></td>
                            <td class=""></td>
                            <td class="text-center">Credit Turnover</td>
                            <td>
                                <h6 class="mb-0 acc-detail-gbp-h-green"> {{ $cU_currency_code }} {{ $totalCredit }} </h6>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $.ajax({
            url: "/kyc-status",
            type: "GET",
            success: function(data) {
                if (data.status !== 'APPROVED') {
                    Swal.fire({
                        title: "KYC Verification",
                        html: "<p>Complete your KYC verification to unlock card creation and management features.</p><p>Click <a href='/kyc-verify' class='text-primary'>here</a> to complete your KYC.</p>",
                        icon: "info",
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        backdrop: true
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching dashboard data:", error);
            }
        });
    });
</script>
@endsection