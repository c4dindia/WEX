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
            @if (auth()->user()->is_admin == 3)
            <a href="{{ route('showCard') }}" style="text-decoration: none; color:black">Expense Cards</a>
            @elseif (auth()->user()->is_admin == 4)
            <a href="{{ url('cards') }}/{{ $card->user_id }}" style="text-decoration: none; color:black">Expense Cards</a>
            @endif
        </li>
        <li class="breadcrumb-item breadcrumb-text-color " aria-current="page"><a href="#" style="text-decoration: none;">Card Details</a></li>
    </ol>
</nav>
<section>
    <div class="row">
        <div class="col-md-12">
            <h4 class="dark-text-weight">{{ $card->masked_card_number }} Details</h4>
            <nav id="menu" class="p-0 mt-4">
                <ul class="d-flex gap-3 p-0 m-0">
                    <li class="tab-1 card-details"><a href="{{ url('/card') }}/{{ $card->card_id }}" class="normal active">CARD DETAILS</a></li>
                    @if (auth()->user()->is_admin == 3)
                    <li class="tab-1 payments-details"><a href="{{ url('/card') }}/{{ $card->card_id }}/payments" class="normal">PAYMENTS</a></li>
                    @elseif (auth()->user()->is_admin == 4)
                    <li class="tab-1 payments-details"><a href="{{ url('/card') }}/{{ $card->card_id }}/payments/{{ $card->user_id }}" class="normal">PAYMENTS</a></li>
                    @endif
                </ul>
                <hr style="padding: 0; margin: 0;">
            </nav>

            <div class="row d-flex gap-5 gap-md-0">
                <div class="col-md-4 p-3">
                    <div class="atm-card {{ ($card->card_status != 'ACTIVE') ? 'after-freezcard' : '' }}">
                        <div class="atmCard-eye-wrapper">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div class="d-flex gap-3 card-dots">
                            <div data-real-value="{{ $card['masked_card_number'] }}">•••• •••• •••• ••••</div>
                        </div>
                        <div class="card-valid-details">
                            <div>VALID THRU <span>{{ \Carbon\Carbon::parse($card->expiry_date)->format('m/y')}}</span></div>
                            <div>CVV2 <span class="cvv-dots" data-real-value="123">•••</span> </div>
                        </div>
                        {{-- <div class="cardVisa-image">
                            <img src="{{ asset('ClientCss/images/c4d_card.png') }}" alt="Visa.png">
                        <div>Business</div>
                    </div> --}}
                </div>

            </div>
            <div class="col-md-12 col-lg-6 mt-5 settingType-icons">
                <div class="card-clicks d-flex gap-5 align-items-center">
                    <!--Freeze & Unfreeze Button-->
                    @if ($card->card_status == "ACTIVE")
                    <div class="card-icon d-flex flex-column justify-content-center center" data-bs-toggle="modal" data-bs-target="#freezModal">
                        <i class="fa-solid fa-cloud-showers-heavy"></i>
                        <p>Freeze Card</p>
                    </div>
                    @elseif ($card->card_status == "BLOCKED")
                    <div class="card-icon d-flex flex-column justify-content-center center" data-bs-toggle="modal" data-bs-target="#freezModal">
                        <i class="fa-solid after-freezcard-icon fa-sun" style="color: gray;"></i>
                        <p>Unfreeze Card</p>
                    </div>
                    @else
                    @endif

                    <!-- Freez card (Modal) -->
                    <div class="modal fade" id="freezModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="exampleModalLabel" style="color: red;">
                                        <i class="fa-solid fa-triangle-exclamation"></i> &nbsp;Freeze Card
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-5" style="background-color: #ffddd8;">
                                    <!-- Freeze card details go here -->
                                    @if ($card->card_status == "ACTIVE")
                                    Are you sure you want to Freeze this card?
                                    @elseif ($card->card_status == "BLOCKED")
                                    Are you sure you want to Unfreeze this card?
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    @if ($card->card_status == "ACTIVE")
                                    <button type="button" class="btn button-bg-no animate-btn" data-bs-dismiss="modal">No, Don't Freeze</button>
                                    @elseif ($card->card_status == "BLOCKED")
                                    <button type="button" class="btn button-bg-no animate-btn" data-bs-dismiss="modal">No, Don't Unfreeze</button>
                                    @endif

                                    @if ($card->card_status == "ACTIVE")
                                    <a href="{{url('/freeze-card') }}/{{ $card->card_id }}" class="btn button-bg-yes animate-btn">Freeze Card</a>
                                    @elseif ($card->card_status == "BLOCKED")
                                    <a href="{{ url('/unblock-card') }}/{{ $card->card_id }}" class="btn button-bg-yes animate-btn" type="button">Unfreeze Card</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3DS SEttING BUTTON--->
                    @if ($card->card_status == "CLOSED")
                    <div class="card-icon" data-bs-toggle="modal">
                        <i class="fa-solid fa-pen-to-square" style="background-color: gray;color: black"></i>
                        <p>3DS Settings</p>
                    </div>
                    @else
                    <div class="card-icon" data-bs-toggle="modal" data-bs-target="#settingModal">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <p>3DS Settings</p>
                    </div>
                    @endif

                    <!--3DS Setting (Modal-2) -->
                    <div class="modal fade modal-lg" id="settingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="exampleModalLabel" style="color: var( --buttonBg-dark-color);">3DS Settings</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body px-5 py-4" style="background-color: var(--main-body-bg-color);">
                                    <form action="{{ url('/update-3ds-settings') }}/{{ $card->card_id }}" method="POST" id="3dsForm">
                                        @csrf
                                        
                                        <div class="mb-3 mt-3 position-relative">
                                            <label for="passwordInput" class="form-label">3DS Password</label>
                                            <input type="password" class="form-control" id="passwordInput" name="password" value="{{ $card['3ds_password'] }}" required>
                                            <span class="input-icon" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword()">
                                                <i id="toggleIcon" class="fa fa-eye"></i>
                                            </span>
                                            <span style="font-size: 12px; color: grey;">&nbsp;Allowed Characters: A-Z a-z 0-9 ! " # ; : ? & * ( ) + = / \ , . [ ] { }</span>
                                            <span id="passwordError" style="color: red !important; font-size: 12px; display: none;">
                                                <i class="fa-solid fa-triangle-exclamation"></i> &nbsp;Invalid Password
                                            </span>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn button-bg-no animate-btn" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" id="submit-button" class="btn button-bg-yes animate-btn">Save changes</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Close Button -->
                    @if ($card->card_status == "CLOSED")
                    <div class="card-icon-close" data-bs-toggle="modal">
                        <i class="fa-solid fa-xmark" style="background-color: gray"></i>
                        <p>Card Closed</p>
                    </div>
                    @else
                    <div class="card-icon-close" data-bs-toggle="modal" data-bs-target="#closeCardModal">
                        <i class="fa-solid fa-xmark"></i>
                        <p>Close card</p>
                    </div>

                    <!-- close card (model-3) -->
                    <div class="modal fade" id="closeCardModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="exampleModalLabel" style="color: red;">
                                        <i class="fa-solid fa-triangle-exclamation"></i> &nbsp;Close Card
                                    </h4>
                                    <button type=" button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-5" style="background-color: #ffddd8;">
                                    Are you sure , you want to close your card ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn button-bg-no animate-btn" data-bs-dismiss="modal">No, Don't Close</button>
                                    <a href="{{ url('/close-card') }}/{{ $card->card_id }}" class="btn button-bg-yes animate-btn">Yes, Close Card</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

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
                        <td>{{ $card->card_name }}</td>
                    </tr>

                    <tr>
                        <td class="details-data carddetail-headerfont">Account</td>
                        <td>{{ ucwords(strtolower($card->company_name)) }}</td>
                    </tr>
                    <tr>
                        <td class="details-data carddetail-headerfont">Status</td>
                        <td class="indexPage-active">
                            @if ($card->card_status == 'ACTIVE')
                            <span class="acc-detail-gbp-h-green">{{ ucwords(strtolower($card->card_status)) }} •</span>
                            @elseif ($card->card_status == 'BLOCKED')
                            <span class="acc-detail-gbp-h">{{ ucwords(strtolower($card->card_status)) }} •</span>
                            @elseif ($card->card_status == 'CLOSED')
                            <span class="text-secondary">{{ ucwords(strtolower($card->card_status)) }} •</span>
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
                                            <span class="hidden-data" data-visible="false" data-actual="{{ $card['masked_card_number'] }}">•••• •••• •••• ••••</span>
                                        </td>
                                        <td>
                                            <i class="fa-solid fa-eye toggle-eye sens-info-icon"></i>&nbsp;&nbsp;
                                        </td>
                                        <td>
                                            <i class="fa-regular fa-copy copy-data sens-info-icon"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="sen-data-td1 carddetail-headerfont">3DS Password</td>
                                        <td class="sen-data-td2">
                                            <span class="hidden-data" data-visible="false" data-actual="{{ $card['3ds_password'] }}">••••••••</span>
                                        </td>
                                        <td>
                                            <i class="fa-solid fa-eye toggle-eye sens-info-icon"></i>&nbsp;&nbsp;
                                        </td>
                                        <td>
                                            <i class="fa-regular fa-copy copy-data sens-info-icon"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="sen-data-td1 carddetail-headerfont">CVV2</td>
                                        <td class="sen-data-td2">
                                            <span class="hidden-data" data-visible="false" data-actual="123">•••</span>
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
        <div class="col-md-6">
            <div class="d-flex align-items-center justify-content-between" style="width: 95%;">
                <div>
                    <h4 style="font-size: 18px; font-weight: 700;margin-left: 10px;">Limits</h4>
                </div>
                <div class="toggle-container">
                    <button class="toggle-button active" onclick="toggleCards('daily')">Daily</button>
                    <button class="toggle-button" onclick="toggleCards('monthly')">Monthly</button>
                </div>
            </div>

            <!-- Daily Cards -->
            <div id="daily-cards" class="cards-container">
                <div class="d-flex">
                    <!-- daily edit-card-model button-1 (model-1) -->
                    <div class="card">
                        <div class="card-header">
                            <div class="icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="edit-icon" data-bs-toggle="modal" data-bs-target="#editCardLimitDaily-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </div>
                        </div>
                        <div class="card-content card-horizontal-padding">
                            <p class="card-name">Daily Withdrawal</p>
                            <h2 class="fw-bold">{{ $cU_currency_code }} {{ $limits->dailyWithdrawal }}</h2>
                        </div>
                        <div class="progress-bar progress-bar-card card-horizontal-padding">
                            <div class="progress card-progress" style="width: {{ ( ($limits->dailyWithdrawalAvailable/$limits->dailyWithdrawal)*100 <= 100) ? ($limits->dailyWithdrawalAvailable/$limits->dailyWithdrawal)*100 : 100 }}% !important"></div>
                        </div>
                        <div class="progress-text"><span>(Amount Used: {{ $cU_currency_code }} {{ $limits->dailyWithdrawalUsed }})</span> {{ round(($limits->dailyWithdrawalAvailable/$limits->dailyWithdrawal)*100,2) }}%</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="edit-icon" data-bs-toggle="modal" data-bs-target="#editCardLimitDaily-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </div>
                        </div>
                        <div class="card-content card-horizontal-padding">
                            <p class="card-name">Daily Purchase</p>
                            <h2 class="fw-bold">{{ $cU_currency_code }} {{ $limits->dailyPurchase }}</h2>
                        </div>
                        <div class="progress-bar progress-bar-card card-horizontal-padding">
                            <div class="progress card-progress" style="width: {{ ($limits->dailyPurchaseAvailable/$limits->dailyPurchase)*100 }}% !important;"></div>
                        </div>
                        <div class="progress-text"><span>(Amount Used: {{ $cU_currency_code }} {{ $limits->dailyPurchaseUsed }})</span> {{ round(($limits->dailyPurchaseAvailable/$limits->dailyPurchase)*100, 2) }}%</div>
                    </div>
                    <!-- daily edit-card-model (model-1) -->
                    <div class="modal fade modal-lg mt-5" id="editCardLimitDaily-1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="exampleModalLabel" style="color: var( --buttonBg-dark-color);">
                                        <i class="fa-solid fa-pen-to-square"></i> &nbsp;Edit Daily Card Limits
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="background-color: var(--main-body-bg-color);">
                                    <form method="POST" action="{{ url('/cards')}}/{{ $card->card_id }}/daily-limits">
                                        @csrf
                                        <div class="row d-flex align-items-center ">
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Daily Withdrawal</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="number" id="dw" inputmode="numeric" name="limits_daily_withdrawal" value="{{ $limits->dailyWithdrawal }}" max="350" min="0" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Monthly Withdrawal</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="text" id="mw" value="{{ $limits->monthlyWithdrawal }}" readonly>
                                                </div>
                                            </div>
                                            <span id="error-dw" class="text-danger-daily py-2" style="display: none;">*Daily Withdrawal cannot exceed the allowed limit of €350.</span>
                                        </div>
                                        <div class="row d-flex align-items-center ">
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Daily Purchase</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="number" id="dp" inputmode="numeric" name="limits_daily_purchase" value="{{ $limits->dailyPurchase }}" min="0" max="10000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Monthly Purchase</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="text" id="mp" value="{{ $limits->monthlyPurchase }}" readonly>
                                                </div>
                                            </div>
                                            <span id="error-dp" class="text-danger-daily py-2" style="display: none;">*Daily Purchase cannot exceed the allowed limit of €10000.</span>
                                        </div>
                                        <div class="row d-flex align-items-center ">
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Daily Internet Purchase</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="number" id="dip" inputmode="numeric" name="limits_daily_internet_purchase" value="{{ $limits->dailyInternetPurchase }}" min="0" max="10000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Monthly Internet Purchase</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="text" id="mip" value="{{ $limits->monthlyInternetPurchase }}" readonly>
                                                </div>
                                            </div>
                                            <span id="error-dip" class="text-danger-daily py-2" style="display: none;">*Daily Internet Purchase cannot exceed the allowed limit of €10000.</span>
                                        </div>
                                        <div class="row d-flex align-items-center ">
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Daily Contactless Purchase</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="number" id="dcp" inputmode="numeric" name="limits_daily_contactless_purchase" value="{{ $limits->dailyContactlessPurchase }}" min="0" max="10000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Monthly Contactless Purchase</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="text" id="mcp" value="{{ $limits->monthlyContactlessPurchase }}" readonly>
                                                </div>
                                            </div>
                                            <span id="error-dcp" class="text-danger-daily py-2" style="display: none;">*Daily Contactless Purchase cannot exceed the allowed limit of €10000.</span>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn button-bg-no animate-btn" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" id="saveChanges-daily" class="btn button-bg-yes animate-btn disabled">Save</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="card">
                        <div class="card-header">
                            <div class="icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="edit-icon" data-bs-toggle="modal"
                                data-bs-target="#editCardLimitDaily-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </div>
                        </div>
                        <div class="card-content card-horizontal-padding">
                            <p class="card-name">Daily Internet Purchase</p>
                            <h2 class="fw-bold">{{ $cU_currency_code }} {{ $limits->dailyInternetPurchase }}</h2>
                        </div>
                        <div class="progress-bar progress-bar-card card-horizontal-padding">
                            <div class="progress card-progress" style="width: {{ ($limits->dailyInternetPurchaseAvailable/$limits->dailyInternetPurchase)*100 }}% !important;"></div>
                        </div>
                        <div class="progress-text "><span>(Amount Used: {{ $cU_currency_code }} {{ $limits->dailyInternetPurchaseUsed }})</span> {{ round(($limits->dailyInternetPurchaseAvailable/$limits->dailyInternetPurchase)*100, 2)   }}%</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="icon">
                                <i class="fas fa-barcode"></i>
                            </div>
                            <div class="edit-icon" data-bs-toggle="modal" data-bs-target="#editCardLimitDaily-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </div>
                        </div>
                        <div class="card-content card-horizontal-padding">
                            <p class="card-name">Daily Contactless Purchase</p>
                            <h2 class="fw-bold">{{ $cU_currency_code }} {{ $limits->dailyContactlessPurchase }}</h2>
                        </div>
                        <div class="progress-bar progress-bar-card card-horizontal-padding">
                            <div class="progress card-progress" style="width: {{ ($limits->dailyContactlessPurchaseAvailable/$limits->dailyContactlessPurchase)*100 }}% !important;"></div>
                        </div>
                        <div class="progress-text "><span>(Amount Used: {{ $cU_currency_code }} {{ $limits->dailyContactlessPurchaseUsed }})</span> {{ round(($limits->dailyContactlessPurchaseAvailable/$limits->dailyContactlessPurchase)*100, 2) }}% </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Cards -->
            <div id="monthly-cards" class="cards-container" style="display: none;">
                <div class="d-flex">
                    <div class="card">
                        <div class="card-header">
                            <div class="icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="edit-icon" data-bs-toggle="modal" data-bs-target="#editCardLimitMonthly-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </div>
                        </div>
                        <div class="card-content card-horizontal-padding">
                            <p class="card-name">Monthly Withdrawal</p>
                            <h2 class="fw-bold">{{ $cU_currency_code }} {{ $limits->monthlyWithdrawal }}</h2>
                        </div>
                        <div class="progress-bar progress-bar-card card-horizontal-padding">
                            <div class="progress  card-progress" style="width: {{ ($limits->monthlyWithdrawalAvailable/$limits->monthlyWithdrawal)*100 }}% !important;"></div>
                        </div>
                        <div class="progress-text"><span>(Amount Used: {{ $cU_currency_code }} {{ $limits->monthlyWithdrawalUsed }})</span> {{ round(($limits->monthlyWithdrawalAvailable/$limits->monthlyWithdrawal)*100, 2) }}%</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="edit-icon" data-bs-toggle="modal" data-bs-target="#editCardLimitMonthly-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </div>
                        </div>
                        <div class="card-content card-horizontal-padding">
                            <p class="card-name">Monthly Purchase</p>
                            <h2 class="fw-bold">{{ $cU_currency_code }} {{ $limits->monthlyPurchase }}</h2>
                        </div>
                        <div class="progress-bar progress-bar-card card-horizontal-padding">
                            <div class="progress card-progress" style="width:  {{ ($limits->monthlyPurchaseAvailable/$limits->monthlyPurchase)*100 }}% !important;"></div>
                        </div>
                        <div class="progress-text "><span>(Amount Used: {{ $cU_currency_code }} {{ $limits->monthlyPurchaseUsed }})</span> {{ round(($limits->monthlyPurchaseAvailable/$limits->monthlyPurchase)*100, 2) }}%</div>
                    </div>
                    <!-- Monthly edit-card-model (model-2) -->
                    <div class="modal fade modal-lg mt-5" id="editCardLimitMonthly-2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="exampleModalLabel" style="color: var(--buttonBg-dark-color);">
                                        <i class="fa-solid fa-pen-to-square"></i> &nbsp;Edit Monthly Card Limits
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="background-color: var(--main-body-bg-color);">
                                    <form method="POST" action="{{ url('/cards') }}/{{$card->card_id}}/monthy-limits">
                                        @csrf

                                        {{-- Monthly Withdrawal --}}
                                        <div class="row d-flex align-items-center">
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Monthly Withdrawal</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="number" id="mw2" inputmode="numeric" name="limits_monthly_withdrawal"
                                                        value="{{ $limits->monthlyWithdrawal }}"
                                                        max="3000"
                                                        min="350" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Daily Withdrawal</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="number" id="dw2" value="{{ $limits->dailyWithdrawal }}" readonly>
                                                </div>
                                            </div>
                                            <span id="error-dw2" class="text-danger-monthly py-2" style="display: none;"></span>
                                        </div>

                                        {{-- Monthly Purchase --}}
                                        <div class="row d-flex align-items-center">
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Monthly Purchase</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="number" id="mp2" inputmode="numeric" name="limits_monthly_purchase"
                                                        value="{{ $limits->monthlyPurchase }}"
                                                        max="15000"
                                                        min="10000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Daily Purchase</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="number" id="dp2" value="{{ $limits->dailyPurchase }}" readonly>
                                                </div>
                                            </div>
                                            <span id="error-dp2" class="text-danger-monthly py-2" style="display: none;"></span>
                                        </div>

                                        {{-- Monthly Internet Purchase --}}
                                        <div class="row d-flex align-items-center">
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Monthly Internet Purchase</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="number" id="mip2" inputmode="numeric" name="limits_monthly_internet_purchase"
                                                        value="{{ $limits->monthlyInternetPurchase }}"
                                                        max="15000"
                                                        min="10000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Daily Internet Purchase</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="number" id="dip2" value="{{ $limits->dailyInternetPurchase }}" readonly>
                                                </div>
                                            </div>
                                            <span id="error-dip2" class="text-danger-monthly py-2" style="display: none;"></span>
                                        </div>

                                        {{-- Monthly Contactless Purchase --}}
                                        <div class="row d-flex align-items-center">
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Monthly Contactless Purchase</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="number" id="mcp2" inputmode="numeric" name="limits_monthly_contactless_purchase"
                                                        value="{{ $limits->monthlyContactlessPurchase }}"
                                                        max="15000"
                                                        min="10000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>&nbsp;Daily Contactless Purchase</label>
                                                <div class="d-flex gap-2 align-items-center editInputField">
                                                    <p>{{ $cU_currency_code }}</p>
                                                    <input type="number" id="dcp2" value="{{ $limits->dailyContactlessPurchase }}" readonly>
                                                </div>
                                            </div>
                                            <span id="error-dcp2" class="text-danger-monthly py-2" style="display: none;"></span>
                                        </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn button-bg-no animate-btn" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" id="saveChanges-monthly" class="btn button-bg-yes animate-btn disabled">Save</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="card">
                        <div class="card-header">
                            <div class="icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="edit-icon" data-bs-toggle="modal" data-bs-target="#editCardLimitMonthly-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </div>
                        </div>
                        <div class="card-content card-horizontal-padding">
                            <p class="card-name">Monthly Internet Purchase</p>
                            <h2 class="fw-bold">{{ $cU_currency_code }} {{ $limits->monthlyInternetPurchase }}</h2>
                        </div>
                        <div class="progress-bar progress-bar-card card-horizontal-padding">
                            <div class="progress card-progress" style="width: {{ ($limits->monthlyInternetPurchaseAvailable/$limits->monthlyInternetPurchase)*100 }}% !important;"></div>
                        </div>
                        <div class="progress-text"><span>(Amount Used: {{ $cU_currency_code }} {{ $limits->monthlyInternetPurchaseUsed }})</span> {{ round(($limits->monthlyInternetPurchaseAvailable/$limits->monthlyInternetPurchase)*100, 2) }}%</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="icon">
                                <i class="fas fa-barcode"></i>
                            </div>
                            <div class="edit-icon" data-bs-toggle="modal" data-bs-target="#editCardLimitMonthly-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </div>
                        </div>
                        <div class="card-content card-horizontal-padding">
                            <p class="card-name">Monthly Contactless Purchase</p>
                            <h2 class="fw-bold">{{ $cU_currency_code }} {{ $limits->monthlyContactlessPurchase }}</h2>
                        </div>
                        <div class="progress-bar progress-bar-card card-horizontal-padding">
                            <div class="progress card-progress" style="width: {{ ($limits->monthlyContactlessPurchaseAvailable/$limits->monthlyContactlessPurchase)*100 }}% !important;"></div>
                        </div>
                        <div class="progress-text"><span>(Amount Used: {{ $cU_currency_code }} {{ $limits->monthlyContactlessPurchaseUsed }})</span> {{ round(($limits->monthlyContactlessPurchaseAvailable/$limits->monthlyContactlessPurchase)*100, 2) }}%</div>
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

{{-- Validation scripts --}}
<script>
    // toggel daily monthly card
    function toggleCards(selection) {
        const dailyCards = document.getElementById('daily-cards');
        const monthlyCards = document.getElementById('monthly-cards');
        const buttons = document.querySelectorAll('.toggle-button');

        buttons.forEach(button => button.classList.remove('active'));

        if (selection === 'daily') {
            dailyCards.style.display = 'block';
            monthlyCards.style.display = 'none';
            buttons[0].classList.add('active');
        } else if (selection === 'monthly') {
            dailyCards.style.display = 'none';
            monthlyCards.style.display = 'block';
            buttons[1].classList.add('active');
        }
    }

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

    // daily edit card input field
    function getNumericValue(id) {
        var value = parseFloat(document.getElementById(id).value);
        return isNaN(value) ? 0 : value;
    }

    function validateField(inputId, maxId, errorId) {
        var inputVal = getNumericValue(inputId);
        var inputElement = document.getElementById(inputId);
        var maxVal = parseFloat(inputElement.max);
        var errorElement = document.getElementById(errorId);

        if (inputVal > maxVal) {
            errorElement.style.display = "inline";
        } else {
            errorElement.style.display = "none";
        }

        checkForErrors();
    }

    function checkForErrors() {
        var saveChangeDaily = document.getElementById("saveChanges-daily");
        var errors = document.querySelectorAll('.text-danger-daily[style="display: inline;"]');
        if (errors.length > 0) {
            saveChangeDaily.classList.add("disabled");
            saveChangeDaily.disabled = true;
            saveChangeDaily.style.backgroundColor = "gray";
        } else {
            saveChangeDaily.classList.remove("disabled");
            saveChangeDaily.disabled = false;
            saveChangeDaily.style.backgroundColor = "var( --buttonBg-dark-color)";
        }
    }

    function setupEventListeners() {
        document.getElementById("dw").addEventListener("input", function() {
            validateField("dw", "dw", "error-dw");
        });
        document.getElementById("dp").addEventListener("input", function() {
            validateField("dp", "dp", "error-dp");
        });
        document.getElementById("dip").addEventListener("input", function() {
            validateField("dip", "dip", "error-dip");
        });
        document.getElementById("dcp").addEventListener("input", function() {
            validateField("dcp", "dcp", "error-dcp");
        });
    }

    document.addEventListener("DOMContentLoaded", setupEventListeners);


    // for monthly edit card

    function getNumericValue(id) {
        var value = parseFloat(document.getElementById(id).value);
        return isNaN(value) ? 0 : value;
    }

    function validateFieldModal2(inputId, maxId, errorId) {
        const inputElement = document.getElementById(inputId);
        const inputVal = parseFloat(inputElement.value);
        const maxVal = parseFloat(inputElement.max);
        const minVal = parseFloat(inputElement.min);
        const errorElement = document.getElementById(errorId);

        const labelMap = {
            mw2: "Monthly Withdrawal",
            mp2: "Monthly Purchase",
            mip2: "Monthly Internet Purchase",
            mcp2: "Monthly Contactless Purchase"
        };
        const label = labelMap[inputId] || "This value";

        if (inputVal < minVal) {
            errorElement.textContent = `*${label} must not be less than allowed limit of €${minVal}.`;
            errorElement.style.display = "inline";
        } else if (inputVal > maxVal) {
            errorElement.textContent = `*${label} cannot exceed the allowed limit of €${maxVal}.`;
            errorElement.style.display = "inline";
        } else {
            errorElement.style.display = "none";
            errorElement.textContent = "";
        }

        checkForErrorsModal2();
    }

    function checkForErrorsModal2() {
        const saveBtn = document.getElementById("saveChanges-monthly");
        const errors = document.querySelectorAll('.text-danger-monthly[style="display: inline;"]');

        if (errors.length > 0) {
            saveBtn.classList.add("disabled");
            saveBtn.disabled = true;
            saveBtn.style.backgroundColor = "gray";
        } else {
            saveBtn.classList.remove("disabled");
            saveBtn.disabled = false;
            saveBtn.style.backgroundColor = "var(--buttonBg-dark-color)";
        }
    }

    function setupEventListenersModal2() {
        document.getElementById("mw2").addEventListener("input", () => {
            validateFieldModal2("mw2", "mw2", "error-dw2");
        });
        document.getElementById("mp2").addEventListener("input", () => {
            validateFieldModal2("mp2", "mp2", "error-dp2");
        });
        document.getElementById("mip2").addEventListener("input", () => {
            validateFieldModal2("mip2", "mip2", "error-dip2");
        });
        document.getElementById("mcp2").addEventListener("input", () => {
            validateFieldModal2("mcp2", "mcp2", "error-dcp2");
        });
    }

    document.addEventListener("DOMContentLoaded", setupEventListenersModal2);
</script>

<script>
    // Toggle for Email section
    document.getElementById("emailToggle").addEventListener("change", function() {
        const emailInput = document.getElementById("emailInput");
        const smsToggle = document.getElementById("smsToggle"); // Get the SMS toggle
        const smsInput = document.getElementById("smsInput"); // Get the SMS input

        // Hide or show email input
        emailInput.hidden = !this.checked;
        emailInput.value = this.checked ? emailInput.value : '';

        if (this.checked) {
            // Uncheck SMS toggle and hide the SMS input if Email is checked
            smsToggle.checked = false;
            smsInput.hidden = true;
            smsInput.value = '';
        }

        checkSubmitButton();
    });

    // Toggle for SMS section
    document.getElementById("smsToggle").addEventListener("change", function() {
        const smsInput = document.getElementById("smsInput");
        const emailToggle = document.getElementById("emailToggle"); // Get the Email toggle
        const emailInput = document.getElementById("emailInput"); // Get the Email input

        // Hide or show SMS input
        smsInput.hidden = !this.checked;
        smsInput.value = this.checked ? smsInput.value : '';

        if (this.checked) {
            // Uncheck Email toggle and hide the Email input if SMS is checked
            emailToggle.checked = false;
            emailInput.hidden = true;
            emailInput.value = '';
        }

        checkSubmitButton();
    });
</script>

<script>
    function checkSubmitButton() {
        const emailInput = document.getElementById("emailInput").value.trim();
        const smsInput = document.getElementById("smsInput").value.trim();
        const passwordInput = document.getElementById("passwordInput");
        const errorMessage = document.getElementById("passwordError");
        const submitButton = document.getElementById("submit-button");

        // Check if the password is valid
        const regex = /^[A-Za-z0-9!"#*;,:?&()+=\/\\.\[\]{}]+$/;
        const isPasswordValid = regex.test(passwordInput.value);

        // Enable the button if either input has a value and the password is valid
        submitButton.disabled = !(emailInput || smsInput) || !isPasswordValid;

        // Handle password validation error message
        if (!isPasswordValid) {
            errorMessage.textContent = "Password contains invalid characters.";
            passwordInput.style.hidden;
        } else {
            errorMessage.textContent = ""; // Clear error message
            passwordInput.style.display;
        }
    }

    // Add event listener for password input
    passwordInput.addEventListener("input", checkSubmitButton);
</script>
@endsection