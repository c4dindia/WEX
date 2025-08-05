@extends('layouts.clientMaster')

@section('title')
@php
$activePage = 'top up';
@endphp
Top-Up History
@endsection

@section('css')
<!-- CSS for the fade-up effect -->
<style>
    @keyframes fadeUp {
        0% {
            opacity: 1;
            transform: translateY(-3px);
        }

        100% {
            opacity: 0;
            transform: translateY(-40px);
        }
    }

    .fade-up {
        animation: fadeUp 1.5s forwards;
    }
</style>
@endsection

@php
$cU_currency_code = '€';
@endphp

@section('pagecontent')
<nav aria-label=" breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('showClientDashboard') }}" style="text-decoration: none; color:black">Home</a></li>
        <li class="breadcrumb-item breadcrumb-text-color"><a href="#" style="text-decoration: none;">Top Up</a></li>
    </ol>
</nav>
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between pb-2">


                <h4 class="dark-text-weight">Top Up History</h4>
                <div class="d-flex">
                    <button class="expensecard-import-btn" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-plus"></i> &nbsp;Top Up</button>

                    <div class="modal fade" id="exampleModal" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Modal Header with animated top-up icon -->
                                <div class="modal-header topUp-modal-header text-white">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        <i class="fas fa-wallet"></i> Add Top-up
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body topUp-modal-body">

                                    <!-- Predefined Top-up Suggestions -->
                                    <div class="mb-3 text-center">
                                        <label for="topup_suggestions" class="form-label">Choose a quick
                                            top-up amount:</label>
                                        <div id="topup_suggestions">
                                            <button class="btn btn-outline-secondary  topup-suggestion"
                                                type="button" data-value="50">{{$cU_currency_code}} 50</button>
                                            <button class="btn btn-outline-secondary  topup-suggestion"
                                                type="button" data-value="100">{{$cU_currency_code}} 100</button>
                                            <button class="btn btn-outline-secondary topup-suggestion"
                                                type="button" data-value="200">{{$cU_currency_code}} 200</button>
                                        </div>
                                    </div>

                                    <!-- Form for entering custom top-up amount -->
                                    <form action="{{ route('clientTopUpRequest') }}" method="POST" id="payment-form">
                                        @csrf
                                        <input type="text" name="currency_code" value="" hidden>

                                        <div class="mb-3">
                                            <label for="topup_amount" class="form-label">Amount</label>
                                            <input type="number" name="topup_amount" id="topup_amount" class="form-control" placeholder="Enter amount" required>
                                        </div>
                                </div>

                                <!-- Modal Footer with Animated Buttons -->
                                <div class="modal-footer d-flex justify-content-end">
                                    <button type="button" class="btn expensecard-create-btn topup-btn-bg-close animate-btn" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn expensecard-import-btn  animate-btn" style="color: white">Add</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr style="padding: 0; margin: 0;">
        </div>
    </div>
    <div class="p-2 mt-2 ">
        <div class="table-wrapper scrollable-table table-responsive">
            <table class="table table-striped mt-4 rounded-4">
                <thead>
                    <tr>
                        <th class="p-3 text-center" style="color: #5a5a5a">Date</th>
                        <th class="p-3" style="color: #5a5a5a">Sender</th>
                        <th class="p-3 text-center" style="color: #5a5a5a">Amount</th>
                        <th class="p-3 text-center" style="color: #5a5a5a">Status</th>
                    </tr>
                </thead>
                <tbody style="max-height: 600px;">
                    @foreach ($topUps as $topUp)
                    <tr>
                        <td class="text-center">{{ \Carbon\Carbon::parse($topUp->created_at)->format('d M Y')  }}</td>
                        <td class="fw-bold">{{ $companyName }}</td>
                        <td class="text-center">{{ $cU_currency_code}} {{ $topUp->amount }}</td>

                        @if ( $topUp->topup_status == 'COMPLETED')
                        <td class="topUp-completed-color text-center">
                            {{ Str::ucfirst(Str::lower($topUp->topup_status)) }}
                        </td>
                        @else
                        <td class="default-red-color text-center">
                            {{ Str::ucfirst(Str::lower($topUp->topup_status)) }}
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    document.querySelectorAll('.topup-suggestion').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('topup_amount').value = this.getAttribute('data-value');
        });
    });
</script>

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