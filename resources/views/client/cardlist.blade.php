@extends('layouts.clientMaster')

@section('title')
@php
$activePage = 'expense card';
@endphp
Expense Card
@endsection

@section('css')
<style>
    /* Styles for validation feedback */
    .is-valid {
        border-color: #28a745;
        /* Green for valid inputs */
        background-color: #d4edda;
        /* Light green background */
    }

    .is-invalid {
        border-color: #dc3545;
        /* Red for invalid inputs */
        background-color: #f8d7da;
        /* Light red background */
    }

    .error-message {
        color: #dc3545;
        /* Red text for error messages */
        font-size: smaller;
        /* Adjust font size if necessary */
    }

    .button:disabled {
        cursor: not-allowed;
        background-color: gray;
    }
</style>
@endsection

@section('pagecontent')


{{-- page content --}}
<nav aria-label=" breadcrumb">
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="{{ route('showClientDashboard') }}" style="text-decoration: none; color:black">Home</a></li>
        <li class="breadcrumb-item breadcrumb-text-color"><a href="#" style="text-decoration: none;">Expense Cards</a></li>
    </ol>
</nav>
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between pb-2">
                <h4 class="dark-text-weight">Expense Card</h4>
                <div id="add-card-div" style="display: flex;">
                    <!-- ADD CARD BUTTON-->
                    <button type="button" class="btn  makeapaymrnt second" data-bs-toggle="modal" data-bs-target="#exampleModal" style="margin-left: 6px">
                        CREATE CARD
                    </button>
                    <!--add card Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="multiStepModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="display: flex; justify-content: space-between;">
                                    <h5 class="modal-title" id="multiStepModalLabel">Create a Card</h5>
                                    <div type="button" class="close cross-button-create-modal" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </div>
                                </div>
                                <div class="modal-body create-card-body">
                                    <!-- Progress Bar -->
                                    <div class="custom-progress-bar">
                                        <div class="progress">
                                            <div class="progress-bar" id="progressBar" style="width: 20%;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Step 1 -->
                                    <div class="form-step step-1">
                                        <h6>Step 1: Set 3DS Settings</h6>
                                        @if (auth()->user()->is_admin == 3)
                                        <form id="add-card-form" action="{{ route('saveCards') }}" method="POST">
                                        @elseif (auth()->user()->is_admin == 4)
                                        <form id="add-card-form" action="{{ url('/save-cards') }}/{{ $id }}" method="POST">
                                        @endif    
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="accountName1" class="createCard-label">Account Name</label>
                                                        <input type="text" class="form-control" id="account" value="{{ $company_name }}" readonly readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 d-flex gap-2">
                                                    <div class="form-group mt-3" style="width: 100%;">
                                                        <label for="cardName1" class="createCard-label">Card Name</label>
                                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Card Name" required>
                                                    </div>
                                                    <div class="form-group mt-3" style="width: 100%;">
                                                        <label for="cardType1" class="createCard-label">Card Type</label><br>
                                                        <select id="card_type" class="form-control" name="card_type" required>
                                                            <option value="Virtual" selected>Virtual</option>
                                                            <option value="ChipAndPin" disabled>Physical</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mt-3 position-relative">
                                                        <label for="password" class="createCard-label">3DS Password</label>
                                                        <input type="password" class="form-control" id="password" name="password" minlength="8" placeholder="Set 3DS password"
                                                            title="Allowed characters: A-Z a-z 0-9 ! \" # ; : ? & * ( ) +=/ \ , . [ ] { }"
                                                            required pattern="[A-Za-z0-9!\" #;:?&*()+=/\\,.\\[\\]{}]*">
                                                        <span class="input-icon" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword()">
                                                            <i id="toggleIcon" class="fa fa-eye"></i>
                                                        </span>
                                                        <span class="text-secondary" style="width: 60%; font-size: smaller">*Allowed Characters: A-Z a-z 0-9 ! " # ; : ? & * ( ) + = / \ , . [ ] { }.</span>
                                                        <span class="error-message" id="error-message"></span>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>

                                    <!-- Form Step 2 -->
                                    <div class="form-step step-2" style="display:none;">
                                        <h6>Step 2: Set Address</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="address" class="createCard-label">Address</label>
                                                    <input type="text" class="form-control" id="address" value="" name="address" placeholder="Enter Address" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 d-flex gap-2">
                                                <div class="form-group mt-3" style="width: 100%;">
                                                    <label for="city" class="createCard-label">City</label>
                                                    <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" required>
                                                </div>
                                                <div class="form-group mt-3" style="width: 100%;">
                                                    <label for="postal_code" class="createCard-label">Postal Code</label><br>
                                                    <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Enter Postal Code" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 d-flex gap-2">
                                                <div class="form-group mt-3" style="width: 100%;">
                                                    <label for="country" class="createCard-label">Country</label>
                                                    <select class="form-control" name="country" id="country" required>
                                                        <option value="" hidden>Please Select</option>
                                                        <option value="AF">Afghanistan</option>
                                                        <option value="AX">Åland Islands</option>
                                                        <option value="AL">Albania</option>
                                                        <option value="DZ">Algeria</option>
                                                        <option value="AS">American Samoa</option>
                                                        <option value="AD">Andorra</option>
                                                        <option value="AO">Angola</option>
                                                        <option value="AI">Anguilla</option>
                                                        <option value="AQ">Antarctica</option>
                                                        <option value="AG">Antigua and Barbuda</option>
                                                        <option value="AR">Argentina</option>
                                                        <option value="AM">Armenia</option>
                                                        <option value="AW">Aruba</option>
                                                        <option value="AU">Australia</option>
                                                        <option value="AT">Austria</option>
                                                        <option value="AZ">Azerbaijan</option>
                                                        <option value="BS">Bahamas</option>
                                                        <option value="BH">Bahrain</option>
                                                        <option value="BD">Bangladesh</option>
                                                        <option value="BB">Barbados</option>
                                                        <option value="BY">Belarus</option>
                                                        <option value="BE">Belgium</option>
                                                        <option value="BZ">Belize</option>
                                                        <option value="BJ">Benin</option>
                                                        <option value="BM">Bermuda</option>
                                                        <option value="BT">Bhutan</option>
                                                        <option value="BO">Bolivia</option>
                                                        <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                                        <option value="BA">Bosnia and Herzegovina</option>
                                                        <option value="BW">Botswana</option>
                                                        <option value="BV">Bouvet Island</option>
                                                        <option value="BR">Brazil</option>
                                                        <option value="IO">British Indian Ocean Territory</option>
                                                        <option value="BN">Brunei Darussalam</option>
                                                        <option value="BG">Bulgaria</option>
                                                        <option value="BF">Burkina Faso</option>
                                                        <option value="BI">Burundi</option>
                                                        <option value="CV">Cabo Verde</option>
                                                        <option value="KH">Cambodia</option>
                                                        <option value="CM">Cameroon</option>
                                                        <option value="CA">Canada</option>
                                                        <option value="KY">Cayman Islands</option>
                                                        <option value="CF">Central African Republic</option>
                                                        <option value="TD">Chad</option>
                                                        <option value="CL">Chile</option>
                                                        <option value="CN">China</option>
                                                        <option value="CX">Christmas Island</option>
                                                        <option value="CC">Cocos (Keeling) Islands</option>
                                                        <option value="CO">Colombia</option>
                                                        <option value="KM">Comoros</option>
                                                        <option value="CG">Congo</option>
                                                        <option value="CD">Congo (Democratic Republic)</option>
                                                        <option value="CK">Cook Islands</option>
                                                        <option value="CR">Costa Rica</option>
                                                        <option value="CI">Côte d'Ivoire</option>
                                                        <option value="HR">Croatia</option>
                                                        <option value="CU">Cuba</option>
                                                        <option value="CW">Curaçao</option>
                                                        <option value="CY">Cyprus</option>
                                                        <option value="CZ">Czech Republic</option>
                                                        <option value="DK">Denmark</option>
                                                        <option value="DJ">Djibouti</option>
                                                        <option value="DM">Dominica</option>
                                                        <option value="DO">Dominican Republic</option>
                                                        <option value="EC">Ecuador</option>
                                                        <option value="EG">Egypt</option>
                                                        <option value="SV">El Salvador</option>
                                                        <option value="GQ">Equatorial Guinea</option>
                                                        <option value="ER">Eritrea</option>
                                                        <option value="EE">Estonia</option>
                                                        <option value="SZ">Eswatini</option>
                                                        <option value="ET">Ethiopia</option>
                                                        <option value="FK">Falkland Islands</option>
                                                        <option value="FO">Faroe Islands</option>
                                                        <option value="FJ">Fiji</option>
                                                        <option value="FI">Finland</option>
                                                        <option value="FR">France</option>
                                                        <option value="GF">French Guiana</option>
                                                        <option value="PF">French Polynesia</option>
                                                        <option value="TF">French Southern Territories</option>
                                                        <option value="GA">Gabon</option>
                                                        <option value="GM">Gambia</option>
                                                        <option value="GE">Georgia</option>
                                                        <option value="DE">Germany</option>
                                                        <option value="GH">Ghana</option>
                                                        <option value="GI">Gibraltar</option>
                                                        <option value="GR">Greece</option>
                                                        <option value="GL">Greenland</option>
                                                        <option value="GD">Grenada</option>
                                                        <option value="GP">Guadeloupe</option>
                                                        <option value="GU">Guam</option>
                                                        <option value="GT">Guatemala</option>
                                                        <option value="GG">Guernsey</option>
                                                        <option value="GN">Guinea</option>
                                                        <option value="GW">Guinea-Bissau</option>
                                                        <option value="GY">Guyana</option>
                                                        <option value="HT">Haiti</option>
                                                        <option value="HM">Heard Island and McDonald Islands</option>
                                                        <option value="VA">Holy See</option>
                                                        <option value="HN">Honduras</option>
                                                        <option value="HK">Hong Kong</option>
                                                        <option value="HU">Hungary</option>
                                                        <option value="IS">Iceland</option>
                                                        <option value="IN">India</option>
                                                        <option value="ID">Indonesia</option>
                                                        <option value="IR">Iran</option>
                                                        <option value="IQ">Iraq</option>
                                                        <option value="IE">Ireland</option>
                                                        <option value="IM">Isle of Man</option>
                                                        <option value="IL">Israel</option>
                                                        <option value="IT">Italy</option>
                                                        <option value="JM">Jamaica</option>
                                                        <option value="JP">Japan</option>
                                                        <option value="JE">Jersey</option>
                                                        <option value="JO">Jordan</option>
                                                        <option value="KZ">Kazakhstan</option>
                                                        <option value="KE">Kenya</option>
                                                        <option value="KI">Kiribati</option>
                                                        <option value="KP">Korea (North)</option>
                                                        <option value="KR">Korea (South)</option>
                                                        <option value="KW">Kuwait</option>
                                                        <option value="KG">Kyrgyzstan</option>
                                                        <option value="LA">Lao People's Democratic Republic</option>
                                                        <option value="LV">Latvia</option>
                                                        <option value="LB">Lebanon</option>
                                                        <option value="LS">Lesotho</option>
                                                        <option value="LR">Liberia</option>
                                                        <option value="LY">Libya</option>
                                                        <option value="LI">Liechtenstein</option>
                                                        <option value="LT">Lithuania</option>
                                                        <option value="LU">Luxembourg</option>
                                                        <option value="MO">Macao</option>
                                                        <option value="MG">Madagascar</option>
                                                        <option value="MW">Malawi</option>
                                                        <option value="MY">Malaysia</option>
                                                        <option value="MV">Maldives</option>
                                                        <option value="ML">Mali</option>
                                                        <option value="MT">Malta</option>
                                                        <option value="MH">Marshall Islands</option>
                                                        <option value="MQ">Martinique</option>
                                                        <option value="MR">Mauritania</option>
                                                        <option value="MU">Mauritius</option>
                                                        <option value="YT">Mayotte</option>
                                                        <option value="MX">Mexico</option>
                                                        <option value="FM">Micronesia (Federated States)</option>
                                                        <option value="MD">Moldova</option>
                                                        <option value="MC">Monaco</option>
                                                        <option value="MN">Mongolia</option>
                                                        <option value="ME">Montenegro</option>
                                                        <option value="MS">Montserrat</option>
                                                        <option value="MA">Morocco</option>
                                                        <option value="MZ">Mozambique</option>
                                                        <option value="MM">Myanmar</option>
                                                        <option value="NA">Namibia</option>
                                                        <option value="NR">Nauru</option>
                                                        <option value="NP">Nepal</option>
                                                        <option value="NL">Netherlands</option>
                                                        <option value="NC">New Caledonia</option>
                                                        <option value="NZ">New Zealand</option>
                                                        <option value="NI">Nicaragua</option>
                                                        <option value="NE">Niger</option>
                                                        <option value="NG">Nigeria</option>
                                                        <option value="NU">Niue</option>
                                                        <option value="NF">Norfolk Island</option>
                                                        <option value="MP">Northern Mariana Islands</option>
                                                        <option value="NO">Norway</option>
                                                        <option value="OM">Oman</option>
                                                        <option value="PK">Pakistan</option>
                                                        <option value="PW">Palau</option>
                                                        <option value="PS">Palestine, State of</option>
                                                        <option value="PA">Panama</option>
                                                        <option value="PG">Papua New Guinea</option>
                                                        <option value="PY">Paraguay</option>
                                                        <option value="PE">Peru</option>
                                                        <option value="PH">Philippines</option>
                                                        <option value="PN">Pitcairn</option>
                                                        <option value="PL">Poland</option>
                                                        <option value="PT">Portugal</option>
                                                        <option value="PR">Puerto Rico</option>
                                                        <option value="QA">Qatar</option>
                                                        <option value="RE">Réunion</option>
                                                        <option value="RO">Romania</option>
                                                        <option value="RU">Russia</option>
                                                        <option value="RW">Rwanda</option>
                                                        <option value="BL">Saint Barthélemy</option>
                                                        <option value="SH">Saint Helena</option>
                                                        <option value="KN">Saint Kitts and Nevis</option>
                                                        <option value="LC">Saint Lucia</option>
                                                        <option value="MF">Saint Martin (French part)</option>
                                                        <option value="PM">Saint Pierre and Miquelon</option>
                                                        <option value="VC">Saint Vincent and the Grenadines</option>
                                                        <option value="WS">Samoa</option>
                                                        <option value="SM">San Marino</option>
                                                        <option value="ST">Sao Tome and Principe</option>
                                                        <option value="SA">Saudi Arabia</option>
                                                        <option value="SN">Senegal</option>
                                                        <option value="RS">Serbia</option>
                                                        <option value="SC">Seychelles</option>
                                                        <option value="SL">Sierra Leone</option>
                                                        <option value="SG">Singapore</option>
                                                        <option value="SX">Sint Maarten (Dutch part)</option>
                                                        <option value="SK">Slovakia</option>
                                                        <option value="SI">Slovenia</option>
                                                        <option value="SB">Solomon Islands</option>
                                                        <option value="SO">Somalia</option>
                                                        <option value="ZA">South Africa</option>
                                                        <option value="GS">South Georgia and the South Sandwich Islands</option>
                                                        <option value="SS">South Sudan</option>
                                                        <option value="ES">Spain</option>
                                                        <option value="LK">Sri Lanka</option>
                                                        <option value="SD">Sudan</option>
                                                        <option value="SR">Suriname</option>
                                                        <option value="SJ">Svalbard and Jan Mayen</option>
                                                        <option value="SE">Sweden</option>
                                                        <option value="CH">Switzerland</option>
                                                        <option value="SY">Syrian Arab Republic</option>
                                                        <option value="TW">Taiwan</option>
                                                        <option value="TJ">Tajikistan</option>
                                                        <option value="TZ">Tanzania</option>
                                                        <option value="TH">Thailand</option>
                                                        <option value="TL">Timor-Leste</option>
                                                        <option value="TG">Togo</option>
                                                        <option value="TK">Tokelau</option>
                                                        <option value="TO">Tonga</option>
                                                        <option value="TT">Trinidad and Tobago</option>
                                                        <option value="TN">Tunisia</option>
                                                        <option value="TR">Turkey</option>
                                                        <option value="TM">Turkmenistan</option>
                                                        <option value="TC">Turks and Caicos Islands</option>
                                                        <option value="TV">Tuvalu</option>
                                                        <option value="UG">Uganda</option>
                                                        <option value="UA">Ukraine</option>
                                                        <option value="AE">United Arab Emirates</option>
                                                        <option value="GB">United Kingdom</option>
                                                        <option value="US">United States</option>
                                                        <option value="UY">Uruguay</option>
                                                        <option value="UZ">Uzbekistan</option>
                                                        <option value="VU">Vanuatu</option>
                                                        <option value="VE">Venezuela</option>
                                                        <option value="VN">Vietnam</option>
                                                        <option value="VG">Virgin Islands (British)</option>
                                                        <option value="VI">Virgin Islands (U.S.)</option>
                                                        <option value="WF">Wallis and Futuna</option>
                                                        <option value="EH">Western Sahara</option>
                                                        <option value="YE">Yemen</option>
                                                        <option value="ZM">Zambia</option>
                                                        <option value="ZW">Zimbabwe</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn expensecard-create-btn  prev-step animate-btn" style="display: none;">Back</button>
                                    <button type="button" class="btn expensecard-import-btn next-step animate-btn" id="adding-the-card-buttonz">Next</button>
                                    <button type="submit" class="btn expensecard-import-btn submit-step animate-btn" style="display: none;" id="submit-the-form-btn">Create</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12 table-wrapper scrollable-table expensecard-table table-responsive">
            <table class="table table-striped mt-4 rounded-4">
                <thead>
                    <tr>
                        <th class="p-3 tablecheck-Box-Max-width"></th>
                        <th class="p-3" style="width: 15%">CARD NUMBER </th>
                        <th class="p-3">NAME ON CARD </th>
                        <th class="p-3 text-center" style="width: 10%">EXPIRY DATE </th>
                        <th class="p-3 text-center" style="width: 10%">TYPE</th>
                        <th class="p-3">ACCOUNT</th>
                        <th class="p-3 text-center" style="width: 10%">STATUS</th>
                    </tr>
                </thead>
                <tbody style="max-height: 600px;">
                    @if (count($cards) == 0)
                    <tr>
                        <td colspan="7" class="text-center">NO RECORDS AVAILABLE</td>
                    </tr>
                    @else

                    @foreach ($cards as $card)
                    <tr>
                        <td class="tablecheck-Box-Max-width text-center">
                            <input class="form-check-input rowCheck expenseCard-checkBox flexCheckChecked flexCheckChecked" type="checkbox" value="" id="flexCheckChecked">
                        </td>
                        <td class="maskcard-number" style="width: 15%"> <a href="{{ url('/card') }}/{{ $card->card_id }}"> {{ $card->masked_card_number }} </a></td>
                        <td class=""> <a href="{{ url('/card') }}/{{ $card->card_id }}" style="color: rgb(0, 0, 0);">{{ $card->card_name }}</a> </td>
                        <td class="text-center" style="width: 10%"> <a href="{{ url('/card') }}/{{ $card->card_id }}" style="color: rgb(0, 0, 0);"> {{ \Carbon\Carbon::parse($card->expiry_date)->format('m/y')}} </a></td>
                        <td class="topUp-completed-color text-center " style="width: 10%"> <a href="{{ url('/card') }}/{{ $card->card_id }}" style="color: rgb(0, 0, 0);"> {{ Str::ucfirst(Str::lower($card->card_type)) }} </a></td>
                        <td class=""> <a href="{{ url('/card') }}/{{ $card->card_id }}" style="color:rgb(0, 0, 0);"> {{ $card->company_name }} </a></td>
                        @if ($card->card_status == 'ACTIVE')
                        <td class="expenseCrad-active text-center fw-bold" style="width: 10%"> {{ Str::ucfirst(Str::lower($card->card_status)) }} •</td>
                        @elseif ($card->card_status == 'BLOCKED')
                        <td class="expenseCrad-block text-center fw-bold" style="width: 10%"> {{ Str::ucfirst(Str::lower($card->card_status)) }} •</td>
                        @elseif ($card->card_status == 'CLOSED')
                        <td class="text-secondary text-center fw-bold" style="width: 10%"> {{ Str::ucfirst(Str::lower($card->card_status)) }} •</td>
                        @else
                        <td class="text-center fw-bold" style="width: 10%"> {{ Str::ucfirst(Str::lower($card->card_status)) }} •</td>
                        @endif
                    </tr>
                    @endforeach
                    @endif

                </tbody>

            </table>
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

<script>
    document.addEventListener('DOMContentLoaded', () => {

        function updateDivVisibility() {
            const anyRowChecked = document.querySelector('.rowCheck:checked') !== null;
            document.getElementById('action-card-div').style.display = anyRowChecked ? 'flex' : 'none';
            document.getElementById('add-card-div').style.display = anyRowChecked ? 'none' : 'flex';
        }

        document.querySelectorAll('.rowCheck').forEach(checkbox => {
            checkbox.addEventListener('change', updateDivVisibility);
        });

        updateDivVisibility();
        document.querySelector('.checkAll').addEventListener('change', updateDivVisibility);
    });
</script>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
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

<!--password restriction and limits validation -->
<script>
    const passwordInput = document.getElementById("password");
    const errorMessage = document.getElementById("error-message");
    const form = document.getElementById("add-card-form");
    const regex = /^[A-Za-z0-9!"#*;,:?&()+=\/\\.\[\]{}]+$/;

    function validatePassword() {
        const passwordValue = passwordInput.value;
        if (regex.test(passwordValue)) {
            errorMessage.textContent = "";
            passwordInput.classList.add("valid");
            passwordInput.classList.remove("invalid");
            return true;
        } else {
            errorMessage.textContent = "Password contains invalid characters.";
            passwordInput.classList.add("invalid");
            passwordInput.classList.remove("valid");
            return false;
        }
    }

    // Corrected listener
    passwordInput.addEventListener("input", validatePassword);
</script>

<!-- progress bar -->
<script>
    const nextBtns = document.querySelectorAll('.next-btn');
    const submitBtn = document.querySelector('.submit-btn');
    const formSteps = document.querySelectorAll('.form-step');
    const progressBar = document.getElementById('progressBar');
    let currentStep = 0;

    nextBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            if (currentStep < formSteps.length - 1) {
                formSteps[currentStep].classList.remove('active');
                currentStep++;
                formSteps[currentStep].classList.add('active');
                updateProgressBar();
            }
        });
    });

    submitBtn.addEventListener('click', () => {
        // Here you would handle form submission, e.g., using AJAX or just showing an alert
        alert('Form submitted!');
        $('#myModal').modal('hide'); // Close modal after submission
    });

    function updateProgressBar() {
        const progress = ((currentStep + 1) / formSteps.length) * 100;
        progressBar.style.width = progress + '%';
        progressBar.setAttribute('aria-valuenow', progress);
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var currentStep = 1;
        var totalSteps = 2;

        function updateProgressBar(step) {
            var progress = (step / totalSteps) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
        }

        function showStep(step) {
            // Hide all steps
            for (var i = 1; i <= totalSteps; i++) {
                document.querySelector('.step-' + i).style.display = 'none';
            }
            // Show the current step
            document.querySelector('.step-' + step).style.display = 'block';

            // Manage Back button visibility
            document.querySelector('.prev-step').style.display = step > 1 ? 'block' : 'none';

            // Manage Submit button visibility
            document.querySelector('.submit-step').style.display = step === totalSteps ? 'block' : 'none';

            // Manage Next button visibility
            document.querySelector('.next-step').style.display = step < totalSteps ? 'block' : 'none';
        }

        // Initialize first step
        showStep(currentStep);
        updateProgressBar(currentStep);

        // Handle next step button click
        var nextButtons = document.querySelectorAll('.next-step');
        nextButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                    updateProgressBar(currentStep);
                }
            });
        });

        // Handle previous step button click
        var prevButtons = document.querySelectorAll('.prev-step');
        prevButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                    updateProgressBar(currentStep);
                }
            });
        });

        // Handle close and cancel button
        // Get the modal and buttons

        var closeButton = document.querySelector('.close');
        var cancelButton = document.querySelector('.btn-secondary[data-dismiss="modal"]');

        closeButton.addEventListener('click', function() {
            $('#exampleModal').modal('hide');
        });

        cancelButton.addEventListener('click', function() {
            $('#exampleModal').modal('hide');
        });

        var submitButton = document.querySelector('.submit-step');
        submitButton.addEventListener('click', function() {

            alert("Form submitted!");
            $('#exampleModal').modal('hide');
        });
    });
</script>

<!-- step 1 create card validation -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const nextStepBtn = document.querySelector('.next-step');
        const prevStepBtn = document.querySelector('.prev-step');
        const submitStepBtn = document.querySelector('.submit-step');
        const steps = document.querySelectorAll('.form-step');
        let currentStep = 0;

        nextStepBtn.disabled = true;

        nextStepBtn.addEventListener('click', () => {
            if (validateCurrentStep()) {
                steps[currentStep].style.display = 'none';
                currentStep++;
                steps[currentStep].style.display = 'block';
                handleButtonsState();
            }
        });

        prevStepBtn.addEventListener('click', () => {
            steps[currentStep].style.display = 'none';
            currentStep--;
            steps[currentStep].style.display = 'block';
            handleButtonsState();
        });

        function validateCurrentStep() {
            const currentInputs = steps[currentStep].querySelectorAll('input, select');
            let isValid = true;
            currentInputs.forEach(input => {
                if (!input.checkValidity()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            return isValid;
        }

        function handleNextButtonState() {
            nextStepBtn.disabled = !validateCurrentStep();
        }

        document.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('input', handleNextButtonState);
        });

        function handleButtonsState() {
            nextStepBtn.style.display = (currentStep === steps.length - 1) ? 'none' : 'inline-block';
            prevStepBtn.style.display = (currentStep === 0) ? 'none' : 'inline-block';
            submitStepBtn.style.display = (currentStep === steps.length - 1) ? 'inline-block' : 'none';
            handleNextButtonState();
        }

        handleButtonsState();
    });
</script>

@endsection