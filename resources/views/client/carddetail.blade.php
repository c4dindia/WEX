@extends('layouts.clientMaster')

@section('title')
@php
$activePage ='null';
@endphp
Card Details
@endsection

@section('css')
<style>
    /* Styles for validation feedback */
    .text-danger-monthly {
        color: #dc3545 !important;
        /* Red text for error messages */
        font-size: smaller !important;
        /* Adjust font size if necessary */
    }

    .text-danger-daily {
        color: #dc3545 !important;
        /* Red text for error messages */
        font-size: smaller !important;
        /* Adjust font size if necessary */
    }

    .atm-card {
        background-image: url("{{ asset('ClientCss/images/c4d_card.png') }}") !important;
    }

    .after-freezcard {
        background-color: var(--card-freeze-greay-color);
        color: var(--white-color);
        background-image: url("{{ asset('ClientCss/images/Card Disabled.png') }}") !important;
    }

    .after-freezcard-icon {
        background: var(--icon-freezed-bg-color) !important;
        color: var(--white-color) !important;
    }
</style>
@endsection

@section('pagecontent')
@php
$cU_currency_code = '€';
@endphp
{{-- Page Content --}}


<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('showClientDashboard') }}" style="text-decoration: none; color:black">Home</a></li>
        <li class="breadcrumb-item">
            <a href="{{ url('cards') }}/{{ $card->id }}" style="text-decoration: none; color:black">Expense Cards</a>
        </li>
        <li class="breadcrumb-item breadcrumb-text-color " aria-current="page"><a href="#" style="text-decoration: none;">Card Details</a></li>
    </ol>
</nav>
<section>
    <div class="row">
        <div class="col-md-12">
            @php
            $maskedCard = substr($card->card_number, 0, 4)
            . 'XXXXXXXX'
            . substr($card->card_number, -4);
            @endphp
            <h4 class="dark-text-weight">{{ $maskedCard }} Details</h4>
            <nav id="menu" class="p-0 mt-4">
                <ul class="d-flex gap-3 p-0 m-0">
                    <li class="tab-1 card-details"><a href="{{ url('/card') }}/{{ $card->card_id }}" class="normal active">CARD DETAILS</a></li>
                    <li class="tab-1 payments-details"><a href="{{ url('/card') }}/{{ $card->id }}/payments" class="normal">PAYMENTS</a></li>
                </ul>
                <hr style="padding: 0; margin: 0;">
            </nav>

            <div class="row d-flex gap-5 gap-md-0">
                <div class="col-md-4 p-3">
                    <div class="atm-card {{ ($card->status != '1') ? 'after-freezcard' : '' }}">
                        <div class="atmCard-eye-wrapper">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div class="d-flex gap-3 card-dots">
                            <div data-real-value="{{ $card['card_number'] }}">•••• •••• •••• ••••</div>
                        </div>
                        <div class="card-valid-details">
                            <div>VALID THRU <span>{{ \Carbon\Carbon::parse($card->expiry_date)->format('m/y')}}</span></div>
                            <div>CSC <span class="cvv-dots" data-real-value="123">•••</span> </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="col-md-6 forDetails-section">
            <div class="details-wrapper">
                <h4 class="card-details-h4-tag">Details</h4>
                <table>
                    <tr>
                        <td class="details-data carddetail-headerfont">Card Holder</td>
                        <td>{{ $card->cardholder_name }} &nbsp; <i class="fa-solid fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#nameEdit" style="cursor: pointer;"></i></td>
                    </tr>

                    <div class="modal fade" id="nameEdit" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Card Name</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="background-color: var(--main-body-bg-color);">
                                    <form action="{{ url('/change-cardholder') }}/{{ $card->id }}" method="POST">
                                        @csrf
                                        @php
                                        $nameParts = explode(' ', $card->cardholder_name, 2);
                                        @endphp
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">First Name:</label>
                                            <input type="text" class="form-control" id="exampleFormControlInput1" name="firstName" value="{{ $nameParts[0] }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Last Name:</label>
                                            <input type="text" class="form-control" id="exampleFormControlInput1" name="lastName" value="{{ $nameParts[1] }}" required>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn default-borderd-btn animate-btn" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn button-bg-yes animate-btn">Save changes</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <tr>
                        <td class="details-data carddetail-headerfont">Account</td>
                        <td>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</td>
                    </tr>
                    <tr>
                        <td class="details-data carddetail-headerfont">Status</td>
                        <td class="indexPage-active">
                            @if ($card->status == '1')
                            <span class="acc-detail-gbp-h-green">Active •</span>
                            @else
                            <span class="text-secondary">Closed •</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="details-data carddetail-headerfont">Type</td>
                        <td>{{ ucwords(strtolower($card->card_type)) }}</td>
                    </tr>
                    <tr>
                        <td class="details-data carddetail-headerfont">Expiration Date</td>
                        <td>{{ \Carbon\Carbon::parse($card->expiry_date)->format('d M Y')}}</td>
                    </tr>
                </table>
            </div>

            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4 class="card-details-h4-tag">Sensitive Information</h4>
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="senstive-data-wrapper">
                                <table>
                                    <tr>
                                        <td class="sen-data-td1 carddetail-headerfont">Card Number</td>
                                        <td class="sen-data-td2">
                                            <span class="hidden-data" data-visible="false" data-actual="{{ $card['card_number'] }}">•••• •••• •••• ••••</span>
                                        </td>
                                        <td>
                                            <i class="fa-solid fa-eye toggle-eye sens-info-icon"></i>&nbsp;&nbsp;
                                        </td>
                                        <td>
                                            <i class="fa-regular fa-copy copy-data sens-info-icon"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="sen-data-td1 carddetail-headerfont">CSC</td>
                                        <td class="sen-data-td2">
                                            <span class="hidden-data" data-visible="false" data-actual="{{ $card['csc'] }}">•••</span>
                                        </td>
                                        <td>
                                            <i class="fa-solid fa-eye toggle-eye sens-info-icon"></i>&nbsp;&nbsp;
                                        </td>
                                        <td>
                                            <i class="fa-regular fa-copy copy-data sens-info-icon"></i>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6" style="padding-left: 20rem;">
            <div class="" style="width: 95%;">
                <h4 style="font-size: 18px; font-weight: 700; margin-left: 10px;">Limits</h4>
            </div>

            <!-- Daily Cards -->
            <div id="daily-cards" class="cards-container">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="icon"><i class="fas fa-credit-card"></i></div>
                        <div class="edit-icon" data-bs-toggle="modal" data-bs-target="#editLimitModal"><i class="fa-solid fa-pen-to-square"></i>
                        </div>
                    </div>
                    <div class="card-content card-horizontal-padding">
                        <p class="card-name">Credit Limit</p>
                        <h2 class="fw-bold">${{ round($card->credit_limit) }}</h2>
                    </div>
                </div>
            </div>

            <div class="modal fade modal-lg mt-5" id="editLimitModal" tabindex="-1" aria-labelledby="editLimitModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title fs-5" id="editLimitModalLabel" style="color: var(--buttonBg-dark-color);">
                                <i class="fa-solid fa-pen-to-square"></i> &nbsp;Edit Card Limit
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editLimitForm" method="POST" action="{{ url('/credit-limit') }}/{{$card->id}}">
                            @csrf
                            <div class="modal-body" style="background-color: var(--main-body-bg-color);">
                                <div class="col-md-12 my-3">
                                    <label class="mb-2">&nbsp;Credit Limit</label>
                                    <div class="d-flex gap-2 align-items-center editInputField">
                                        <p>$</p>
                                        <input type="number" name="amount" value="{{ round($card->credit_limit) }}" min="1" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn button-bg-no animate-btn" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="saveLimitBtn" class="btn button-bg-yes animate-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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
<script>
    //  for senstive data
    document.querySelectorAll('.toggle-eye').forEach(function(eyeIcon) {
        eyeIcon.addEventListener('click', function() {
            const hiddenData = this.closest('tr').querySelector('.hidden-data');
            const isVisible = hiddenData.getAttribute('data-visible') === 'true';

            if (isVisible) {
                hiddenData.textContent = hiddenData.getAttribute('data-actual').replace(/./g, '•');
                hiddenData.setAttribute('data-visible', 'false');
            } else {
                hiddenData.textContent = hiddenData.getAttribute('data-actual');
                hiddenData.setAttribute('data-visible', 'true');
            }
        });
    });

    document.querySelectorAll('.copy-data').forEach(function(copyIcon) {
        copyIcon.addEventListener('click', function() {
            const hiddenData = this.closest('tr').querySelector('.hidden-data');
            const actualData = hiddenData.getAttribute('data-actual');
            const tempInput = document.createElement('input');
            document.body.appendChild(tempInput);
            tempInput.value = actualData;
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);

            alert('Data copied: ' + actualData);
        });
    });

    // after clicking eye icon card data changes
    document.addEventListener("DOMContentLoaded", function() {
        const cardeyeIcon = document.querySelector(".atmCard-eye-wrapper i");
        const cardDots = document.querySelector(".card-dots div");
        const cvvDots = document.querySelector(".cvv-dots");

        cardeyeIcon.addEventListener("click", function() {
            if (cardDots.textContent.includes("•")) {
                cardDots.textContent = cardDots.dataset.realValue;
            } else {
                cardDots.textContent = "•••• •••• •••• ••••";
            }
            if (cvvDots.textContent.includes("•")) {
                cvvDots.textContent = cvvDots.dataset.realValue;
            } else {
                cvvDots.textContent = "•••";
            }

            cardeyeIcon.classList.toggle("fa-eye");
            cardeyeIcon.classList.toggle("fa-eye-slash");
        });
    });
</script>
@endsection