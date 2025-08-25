@extends('layouts.clientMaster')

@section('title')
@php
$activePage ='expense card';
@endphp
Card Details
@endsection

@section ('css')
<style>
    .card-table td {
        padding: 12px 30px !important;
    }

    .card-icon{
        padding: 30px !important;
    }
</style>
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
    </div>
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" onclick="window.location.href = '/card/{{$card->id}}'">Card details</button>
            <button class="nav-link" onclick="window.location.href = '/card/{{$card->id}}/payments'">Payments</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="row mt-4">
                <div class="col-xxl-4 col-xl-4">
                    <div class="ec-img-card">
                        <div class="img-container">
                            @if ($card->status == "1")
                            <img class="card-img mt-3" src="{{ asset('newUI/images/visa.jpg') }}" alt="Card image" style="border-radius: 12px; object-fit: fill;position: relative;" />
                            @else
                            <img class="card-img mt-3" src="{{ asset('ClientCss/images/Card Disabled.png') }}" alt="Card image" style="border-radius: 12px; object-fit: fill;position: relative;">
                            @endif
                        </div>

                        <div class="img-card-number" id="card-number" data-real-value="{{ trim( chunk_split($card->card_number, 4, ' ')) }}">•••• •••• •••• ••••</div>
                        <div class="img-expiry" id="card-expiry">VALID THRU {{ \Carbon\Carbon::parse($card->expiry_date)->format('m/y')}}</div>
                        <div class="img-cvv" id="card-cvv" data-real-value="{{ $card->csc }}">CVV •••</div>

                        <div class="img-eye cursor-pointer" id="toggle-eye">
                            <i class="far fa-eye card-eye"></i>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-8 col-xl-8">
                    <div class="card-table">
                        <h6 style="margin:15px 30px; font-size: 24px;">Details</h6>
                        <table style="width:100%">
                            <tr>
                                <td>Card Holder</td>
                                <td>{{ $card->cardholder_name }} <i class="bi bi-pencil-square" role="button" data-bs-toggle="modal"
                                        data-bs-target="#cardedit"
                                        style="color: #7e1718; font-weight: 600; font-size: 15px; margin-left: 5px;"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>Account</td>
                                <td>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</td>
                            </tr>

                            <tr>
                                <td>Status</td>
                                @if ($card->status == '1')
                                <td class="text-success">Active</td>
                                @else
                                <td class="text-danger">Closed</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td>{{ ucwords(strtolower($card->card_type)) }}</td>
                            </tr>
                            <tr>
                                <td>Expiration Date</td>
                                <td>{{ \Carbon\Carbon::parse($card->expiry_date)->format('d M Y') }}</td>
                            </tr>

                        </table>

                    </div>
                </div>
            </div>


            <div class="d-flex align-items-center justify-content-between pt-4 mt-2 pb-2">
                <h6 class="mb-0" style="font-size: 24px;">Limits</h6>
            </div>
            <div class="row">
                <div class="col-xxl-6 col-xl-6">
                    <div class="card-icon mt-0 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <p class="m-0"><i class="fas fa-money-bill icons-btn"></i> Credit Limit</p>
                            <i class="bi bi-pencil-square"
                                data-bs-toggle="modal"
                                data-bs-target="#cardlimit"
                                style="color: #7e1718; font-weight: 600; font-size: 20px;"></i>
                        </div>
                        <div class="row">
                            <div
                                class="col-xxl-2 col-xl-2 col-lg-3 col-md-3 col-sm-12 col-12 align-content-center">
                                <h6 style="width: max-content;">USD {{ round($card->credit_limit) }}</h6>
                            </div>
                            <div
                                class="col-xxl-9 col-xl-9 col-lg-8 col-md-8 col-sm-12 col-12 align-content-center">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="50"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                        <span class="sr-only">70% Complete</span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-sm-12 col-12 align-content-center">
                                <p style="background-color: #e8eeee; padding: 4px 0; text-align: center; color: #7e1718; font-weight: 600; font-size: 12px; border-radius: 50px; margin: 0px;">
                                    0%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-6 col-xl-6">
                    <div class="card-table">
                        <h6 style="margin:15px 30px;">Sensitive Information</h6>
                        <table style="width:100%" class="sensitive-table">
                            <tr>
                                <td class="w-50">Card Number</td>
                                <td class="hidden-data" data-visible="false" data-actual="{{trim( chunk_split($card->card_number, 4, ''))  }}">•••• •••• •••• ••••
                                </td>
                                <td class="text-end"><i class="far fa-eye toggle-eye" role="button"></i></td>
                            </tr>
                            <tr>
                                <td class="w-50">CVV</td>
                                <td class="hidden-data" data-visible="false" data-actual="{{ $card->csc }}">•••
                                </td>
                                <td class="text-end"><i class="far fa-eye toggle-eye" role="button"></i></td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="cardlimit">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"> <i class="bi bi-pencil-square"></i> Edit Card Limit</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLimitForm" method="POST" action="{{ url('/credit-limit') }}/{{$card->id}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xxl-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Credit Limit</label>
                                <input type="number" inputmode="numeric" name="amount" value="{{ round($card->credit_limit) }}" min="1" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="saveLimitBtn" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="cardedit">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Edit Card Name</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ url('/change-cardholder') }}/{{ $card->id }}" method="POST">
                @csrf
                @php
                $nameParts = explode(' ', $card->cardholder_name, 2);
                @endphp
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xxl-12">
                            <div class="form-group mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" placeholder="Enter First Name" name="firstName" value="{{ $nameParts[0] }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" placeholder="Enter Last Name" name="lastName" value="{{ $nameParts[1] }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <button type="save" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<!-- Show/Hide Password-->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('passwordInput');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>

{{-- Validation scripts --}}
<script>
    function toggleCards(selection) {
        document.querySelectorAll('[id$="-cards"]').forEach(section => {
            section.style.display = 'none';
        });

        const selectedSection = document.getElementById(selection + '-cards');
        if (selectedSection) {
            selectedSection.style.display = 'block';
        }

        localStorage.setItem('selectedPeriod', selection);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const savedPeriod = localStorage.getItem('selectedPeriod');
        const dropdown = document.getElementById('time-limit');

        if (savedPeriod) {
            dropdown.value = savedPeriod;
            toggleCards(savedPeriod);
        } else {
            toggleCards('daily');
        }
    });

    //  for senstive data
    document.querySelectorAll('.toggle-eye').forEach(function(eyeIcon) {
        eyeIcon.addEventListener('click', function() {
            const hiddenData = this.closest('tr').querySelector('.hidden-data');
            const isVisible = hiddenData.getAttribute('data-visible') === 'true';

            if (isVisible) {
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
                hiddenData.textContent = hiddenData.getAttribute('data-actual').replace(/./g, '•');
                hiddenData.setAttribute('data-visible', 'false');
            } else {
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
                hiddenData.textContent = hiddenData.getAttribute('data-actual');
                hiddenData.setAttribute('data-visible', 'true');
            }
        });
    });

    // after clicking eye icon card data changes
    document.addEventListener("DOMContentLoaded", function() {
        const cardeyeIcon = document.querySelector(".card-eye");
        const cardDots = document.querySelector(".img-card-number");
        const cvvDots = document.querySelector(".img-cvv");

        cardeyeIcon.addEventListener("click", function() {
            if (cardDots.textContent.includes("•")) {
                cardDots.textContent = cardDots.dataset.realValue;
            } else {
                cardDots.textContent = "•••• •••• •••• ••••";
            }
            if (cvvDots.textContent.includes("•")) {
                cvvDots.textContent = 'CVV ' + cvvDots.dataset.realValue;
            } else {
                cvvDots.textContent = "CVV •••";
            }

            cardeyeIcon.classList.toggle("fa-eye");
            cardeyeIcon.classList.toggle("fa-eye-slash");
        });
    });
</script>

@endsection