@extends('layouts.clientMaster')

@section('title')
@php
$activePage = 'kyc';
@endphp
KYC Verification
@endsection

@section('css')
<style>
    .btn {
        background: #2A7E8C !important;
        border-color: #2A7E8C !important;
        margin-top: 31px;
        width: 15%;
    }

    .btn:disabled {
        background: #198754 !important;
        border-color: #198754 !important;
    }

    input {
        width: 90% !important;
    }

    .swal-confirm {
        background: #2A7E8C !important;
        border-color: #2A7E8C !important;
    }
</style>
@endsection

@section('pagecontent')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('showClientDashboard') }}" style="text-decoration: none; color:black">Home</a></li>
        <li class="breadcrumb-item breadcrumb-text-color"><a href="#" style="text-decoration: none;">KYC Verification</a></li>
    </ol>
</nav>

<section>
    <div class="row d-flex align-items-center justify-content-between mb-2">
        <div class="col-md-6">
            <h4 class="dark-text-weight">KYC Verification</h4>
        </div>

        <hr style="padding: 0; margin: 0;">
    </div>

    <div class="row">
        <div class="col-md-12 d-flex justify-content-center align-items-center mt-5">
            <div class="col-md-3"></div>
            <div class="col-md-4">
                <label for="email" class="form-label">Step 1 : Simulate Email Verification</label>
                <input id="email" class="form-control" type="text" name="email" value="{{ $user->email }}" readonly>
                <small id="emailError" class="text-danger"></small>
            </div>
            <div class="col-md-5">
                @if($user->email_verified == 'false')
                <button type="button" class="btn btn-primary" id="verifyEmail">Verify</button>
                @else
                <button type="button" class="btn btn-success" disabled>Verified</button>
                @endif
            </div>
        </div>

        <div class="col-md-12 d-flex justify-content-center align-items-center mt-5">
            <div class="col-md-3"></div>
            <div class="col-md-4">
                <label for="phone" class="form-label">Step 2 : Simulate Mobile Verification</label>
                <input id="phone" class="form-control" type="text" name="phone" value="{{ $user->country_code }} {{ $user->phone }}" readonly>
                <small id="phoneError" class="text-danger"></small>

            </div>
            <div class="col-md-5">
                @if($user->mobile_verified == 'false')
                <button type="button" class="btn btn-primary" id="verifyMobile">Verify</button>
                @else
                <button type="button" class="btn btn-success" disabled>Verified</button>
                @endif
            </div>
        </div>

        <div class="col-md-12 d-flex justify-content-center align-items-center mt-5">
            <div class="col-md-3"></div>
            <div class="col-md-4">
                <label for="user_id" class="form-label">Step 3 : Simulate KYC Approval</label>
                <input id="user_id" class="form-control" type="text" name="user_id" value="{{ $user->striga_user_id }}" readonly>
                <small id="kycError" class="text-danger"></small>
            </div>
            <div class="col-md-5">
                @if($user->kyc_verified == 'false')
                <button type="button" class="btn btn-primary" id="verifyKYC">Verify</button>
                @else
                <button type="button" class="btn btn-success" disabled>Verified</button>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        let isEmailVerified = {
            {
                $user - > email_verified == 'true' ? 'true' : 'false'
            }
        };
        let isMobileVerified = {
            {
                $user - > mobile_verified == 'true' ? 'true' : 'false'
            }
        };

        const emailError = document.getElementById('emailError');
        const phoneError = document.getElementById('phoneError');
        const kycError = document.getElementById('kycError');

        // EMAIL VERIFY
        $('#verifyEmail').click(function() {
            emailError.classList.add('d-none');

            $.ajax({
                url: "/verify-email",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (!response.errorCode) {
                        isEmailVerified = true;

                        Swal.fire({
                            title: 'Email Verification',
                            text: 'Email verified successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'swal-confirm'
                            },
                            timer: 5000
                        });
                    } else {
                        emailError.textContent = response.message;
                        emailError.classList.remove('d-none');
                    }
                },
                error: function(xhr, status, error) {
                    emailError.textContent = "Something went wrong while verifying email.";
                    emailError.classList.remove('d-none');
                }
            });
        });

        // MOBILE VERIFY
        $('#verifyMobile').click(function() {
            phoneError.classList.add('d-none');

            if (!isEmailVerified) {
                phoneError.textContent = "Please verify your email first.";
                phoneError.classList.remove('d-none');
                return;
            }

            $.ajax({
                url: "/verify-mobile",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (!response.errorCode) {
                        isMobileVerified = true;

                        Swal.fire({
                            title: 'Mobile Verification',
                            text: 'Mobile verified successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'swal-confirm'
                            },
                            timer: 5000
                        });
                    } else {
                        phoneError.textContent = response.message;
                        phoneError.classList.remove('d-none');
                    }
                },
                error: function() {
                    phoneError.textContent = "Something went wrong while verifying mobile.";
                    phoneError.classList.remove('d-none');
                }
            });
        });

        // KYC VERIFY
        $('#verifyKYC').click(function() {
            kycError.classList.add('d-none');

            if (!isEmailVerified || !isMobileVerified) {
                kycError.textContent = "Please verify your email and mobile first.";
                kycError.classList.remove('d-none');
                return;
            }

            $.ajax({
                url: "/verify-kyc",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);
                    if (response.verificationLink) {
                        Swal.fire({
                            title: "Identity Verification",
                            html: "<p>You must verify your identity (KYC) to create or manage cards.</p><p>Click <a href='" + response.verificationLink + "' target='_blank' class='text-primary'>here</a> to complete your KYC.</p>",
                            icon: "info",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            backdrop: true
                        });
                    } else {
                        kycError.textContent = response.message;
                        kycError.classList.remove('d-none');
                    }
                },
                error: function() {
                    phoneError.textContent = "Something went wrong while verifying mobile.";
                    phoneError.classList.remove('d-none');
                }
            });
        });
    });
</script>


@endsection