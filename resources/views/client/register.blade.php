<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('ClientCss/images/neurosyncLogo.png') }}">
    <!-- SweetAlert2-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- font awesome  -->
    {{-- <script src="https://kit.fontawesome.com/6036d46694.js" crossorigin="anonymous"></script> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

    <!-- jQuery (required for intl-tel-input) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- intl-tel-input JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <!-- external css file  -->
    <link rel="stylesheet" href="{{ asset('ClientCss/register.css') }}">
    <!-- boot starp  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<style>
    .iti {
        width: 100%;
    }
</style>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- left side  -->
            <div class="col-md-6 login-left-side-bg">
                <div class="left-side-container">
                    <div class="login-logo">
                        <img class="mb-2" src="{{ asset('ClientCss/images/c4d-White.png') }}" alt="not found">
                        <span class="text-white-color">Latest Fintech services, at your fingertips</span>
                    </div>
                    <div class="login-card" alt="">
                        <img src="{{ asset('ClientCss/images/c4d_card.png') }}" alt="" style="border-radius: 10px;">
                    </div>
                    <div class="login-pText">
                        <p class="text-white-color">Instant Card Issue . Expense Card . Virtual Card</p>
                    </div>
                </div>
            </div>
            <!-- right side  -->
            <div class="col-md-6">
                <div class="registraion-txt">
                    <h3>Registration</h3>
                    <div class="" style="border-bottom: 1px solid rgb(197, 194, 194); width: 100%;"></div>
                </div>
                <div class="right-side-container">
                    <div class="container form-container">
                        <!-- Progress Bar -->
                        <div class="progress-container">
                            <div class="progress-step active"></div>
                            <div class="progress-step"></div>
                            <div class="progress-step"></div>
                        </div>
                        <!-- Form -->
                        <!-- Form Steps -->
                        <div id="step-text" class="mb-3 step-wise-text"></div>
                        <div class="form-step active" id="step-1">
                            <form role="form" method="POST" action="{{ route('storeClientRegistration') }}">
                                @csrf
                                <div class="form-height">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Enter email" required>
                                        @error('email')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="d-flex align-items-center position-relative">
                                            <input id="password" class="form-control" type="password" id="password" placeholder="Enter password" name="password" required autocomplete="new-password">
                                            <span class="input-icon" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword()">
                                                <i id="toggleIcon" class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                        @error('password')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input id="password_confirmation" class="form-control" type="password"
                                            name="password_confirmation" required autocomplete="new-password">
                                    </div> --}}
                                </div>
                                <div class="d-flex justify-content-between align-items-center bottom-section">
                                    <div class="login-link">
                                        <p>Already have an account? <a href="{{ route('showClientLogin') }}" class="login-here">Login Here</a></p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-continue mb-0" onclick="nextStep()" id="acc-info">Continue</button>
                                    </div>
                                </div>
                        </div>
                        <!-- step -2  -->
                        <div class="form-step" id="step-2">
                            <div class="form-height">
                                <div class="row d-flex">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input id="first_name" class="form-control" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name"
                                                required autofocus autocomplete="off">
                                        </div>
                                        <div class="mb-3">
                                            <label for="telephone" class="form-label">Phone Number</label><br>
                                            <input type="number" class="form-control pl-5" id="telephone" name="telephone"
                                                placeholder="Enter phone number" value="{{ old('telephone') }}" required>
                                            <input type="hidden" id="countryCode" name="countryCode" required>
                                            <input type="hidden" id="phone" name="phone" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input id="last_name" class="form-control" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter last name"
                                                required autofocus autocomplete="off">
                                        </div>
                                        <div class="mb-3">
                                            <label for="dob" class="form-label">Date of Birth</label>
                                            <input id="dob" class="form-control px-2" type="date" name="dob" value="{{ old('dob') }}" required autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center bottom-section">
                                <div class="login-link">
                                    <p>Already have an account? <a href="{{ route('showClientLogin') }}" class="login-here">Login Here</a></p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-back mb-0" onclick="previousStep()">Back</button>
                                    <button type="button" class="btn btn-continue mb-0" onclick="nextStep()" id="co-info-btn">Continue</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-step" id="step-3">
                            <div class="form-height">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control px-2" id="address" name="address" rows="2"
                                        placeholder="Enter your full address" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input id="city" class="form-control" type="text" name="city" value="{{ old('city') }}" placeholder="Enter your city"
                                        required autofocus autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <input id="state" class="form-control" type="text" name="state" value="{{ old('state') }}" placeholder="Enter your state"
                                        required autofocus autocomplete="off">
                                </div>

                                <div class="mb-3">
                                    <label for="cvv" class="form-label">Country</label>
                                    <select class="form-control px-2" name="country" id="countries" required>
                                        <option value="" hidden>Please Select</option>
                                        <option value="AFG">Afghanistan</option>
                                        <option value="ALA">Åland Islands</option>
                                        <option value="ALB">Albania</option>
                                        <option value="DZA">Algeria</option>
                                        <option value="ASM">American Samoa</option>
                                        <option value="AND">Andorra</option>
                                        <option value="AGO">Angola</option>
                                        <option value="AIA">Anguilla</option>
                                        <option value="ATA">Antarctica</option>
                                        <option value="ATG">Antigua and Barbuda</option>
                                        <option value="ARG">Argentina</option>
                                        <option value="ARM">Armenia</option>
                                        <option value="ABW">Aruba</option>
                                        <option value="AUS">Australia</option>
                                        <option value="AUT">Austria</option>
                                        <option value="AZE">Azerbaijan</option>
                                        <option value="BHS">Bahamas</option>
                                        <option value="BHR">Bahrain</option>
                                        <option value="BGD">Bangladesh</option>
                                        <option value="BRB">Barbados</option>
                                        <option value="BLR">Belarus</option>
                                        <option value="BEL">Belgium</option>
                                        <option value="BLZ">Belize</option>
                                        <option value="BEN">Benin</option>
                                        <option value="BMU">Bermuda</option>
                                        <option value="BTN">Bhutan</option>
                                        <option value="BOL">Bolivia</option>
                                        <option value="BES">Bonaire, Sint Eustatius and Saba</option>
                                        <option value="BIH">Bosnia and Herzegovina</option>
                                        <option value="BWA">Botswana</option>
                                        <option value="BVT">Bouvet Island</option>
                                        <option value="BRA">Brazil</option>
                                        <option value="IOT">British Indian Ocean Territory</option>
                                        <option value="BRN">Brunei Darussalam</option>
                                        <option value="BGR">Bulgaria</option>
                                        <option value="BFA">Burkina Faso</option>
                                        <option value="BDI">Burundi</option>
                                        <option value="CPV">Cabo Verde</option>
                                        <option value="KHM">Cambodia</option>
                                        <option value="CMR">Cameroon</option>
                                        <option value="CAN">Canada</option>
                                        <option value="CYM">Cayman Islands</option>
                                        <option value="CAF">Central African Republic</option>
                                        <option value="TCD">Chad</option>
                                        <option value="CHL">Chile</option>
                                        <option value="CHN">China</option>
                                        <option value="CXR">Christmas Island</option>
                                        <option value="CCK">Cocos (Keeling) Islands</option>
                                        <option value="COL">Colombia</option>
                                        <option value="COM">Comoros</option>
                                        <option value="COG">Congo</option>
                                        <option value="COD">Congo (Democratic Republic)</option>
                                        <option value="COK">Cook Islands</option>
                                        <option value="CRI">Costa Rica</option>
                                        <option value="CIV">Côte d'Ivoire</option>
                                        <option value="HRV">Croatia</option>
                                        <option value="CUB">Cuba</option>
                                        <option value="CUW">Curaçao</option>
                                        <option value="CYP">Cyprus</option>
                                        <option value="CZE">Czech Republic</option>
                                        <option value="DNK">Denmark</option>
                                        <option value="DJI">Djibouti</option>
                                        <option value="DMA">Dominica</option>
                                        <option value="DOM">Dominican Republic</option>
                                        <option value="ECU">Ecuador</option>
                                        <option value="EGY">Egypt</option>
                                        <option value="SLV">El Salvador</option>
                                        <option value="GNQ">Equatorial Guinea</option>
                                        <option value="ERI">Eritrea</option>
                                        <option value="EST">Estonia</option>
                                        <option value="SWZ">Eswatini</option>
                                        <option value="ETH">Ethiopia</option>
                                        <option value="FLK">Falkland Islands</option>
                                        <option value="FRO">Faroe Islands</option>
                                        <option value="FJI">Fiji</option>
                                        <option value="FIN">Finland</option>
                                        <option value="FRA">France</option>
                                        <option value="GUF">French Guiana</option>
                                        <option value="PYF">French Polynesia</option>
                                        <option value="ATF">French Southern Territories</option>
                                        <option value="GAB">Gabon</option>
                                        <option value="GMB">Gambia</option>
                                        <option value="GEO">Georgia</option>
                                        <option value="DEU">Germany</option>
                                        <option value="GHA">Ghana</option>
                                        <option value="GIB">Gibraltar</option>
                                        <option value="GRC">Greece</option>
                                        <option value="GRL">Greenland</option>
                                        <option value="GRD">Grenada</option>
                                        <option value="GLP">Guadeloupe</option>
                                        <option value="GUM">Guam</option>
                                        <option value="GTM">Guatemala</option>
                                        <option value="GGY">Guernsey</option>
                                        <option value="GIN">Guinea</option>
                                        <option value="GNB">Guinea-Bissau</option>
                                        <option value="GUY">Guyana</option>
                                        <option value="HTI">Haiti</option>
                                        <option value="HMD">Heard Island and McDonald Islands</option>
                                        <option value="VAT">Holy See</option>
                                        <option value="HND">Honduras</option>
                                        <option value="HKG">Hong Kong</option>
                                        <option value="HUN">Hungary</option>
                                        <option value="ISL">Iceland</option>
                                        <option value="IND">India</option>
                                        <option value="IDN">Indonesia</option>
                                        <option value="IRN">Iran</option>
                                        <option value="IRQ">Iraq</option>
                                        <option value="IRL">Ireland</option>
                                        <option value="IMN">Isle of Man</option>
                                        <option value="ISR">Israel</option>
                                        <option value="ITA">Italy</option>
                                        <option value="JAM">Jamaica</option>
                                        <option value="JPN">Japan</option>
                                        <option value="JEY">Jersey</option>
                                        <option value="JOR">Jordan</option>
                                        <option value="KAZ">Kazakhstan</option>
                                        <option value="KEN">Kenya</option>
                                        <option value="KIR">Kiribati</option>
                                        <option value="PRK">Korea (North)</option>
                                        <option value="KOR">Korea (South)</option>
                                        <option value="KWT">Kuwait</option>
                                        <option value="KGZ">Kyrgyzstan</option>
                                        <option value="LAO">Lao People's Democratic Republic</option>
                                        <option value="LVA">Latvia</option>
                                        <option value="LBN">Lebanon</option>
                                        <option value="LSO">Lesotho</option>
                                        <option value="LBR">Liberia</option>
                                        <option value="LBY">Libya</option>
                                        <option value="LIE">Liechtenstein</option>
                                        <option value="LTU">Lithuania</option>
                                        <option value="LUX">Luxembourg</option>
                                        <option value="MAC">Macao</option>
                                        <option value="MDG">Madagascar</option>
                                        <option value="MWI">Malawi</option>
                                        <option value="MYS">Malaysia</option>
                                        <option value="MDV">Maldives</option>
                                        <option value="MLI">Mali</option>
                                        <option value="MLT">Malta</option>
                                        <option value="MHL">Marshall Islands</option>
                                        <option value="MTQ">Martinique</option>
                                        <option value="MRT">Mauritania</option>
                                        <option value="MUS">Mauritius</option>
                                        <option value="MYT">Mayotte</option>
                                        <option value="MEX">Mexico</option>
                                        <option value="FSM">Micronesia (Federated States)</option>
                                        <option value="MDA">Moldova</option>
                                        <option value="MCO">Monaco</option>
                                        <option value="MNG">Mongolia</option>
                                        <option value="MNE">Montenegro</option>
                                        <option value="MSR">Montserrat</option>
                                        <option value="MAR">Morocco</option>
                                        <option value="MOZ">Mozambique</option>
                                        <option value="MMR">Myanmar</option>
                                        <option value="NAM">Namibia</option>
                                        <option value="NRU">Nauru</option>
                                        <option value="NPL">Nepal</option>
                                        <option value="NLD">Netherlands</option>
                                        <option value="NCL">New Caledonia</option>
                                        <option value="NZL">New Zealand</option>
                                        <option value="NIC">Nicaragua</option>
                                        <option value="NER">Niger</option>
                                        <option value="NGA">Nigeria</option>
                                        <option value="NIU">Niue</option>
                                        <option value="NFK">Norfolk Island</option>
                                        <option value="MNP">Northern Mariana Islands</option>
                                        <option value="NOR">Norway</option>
                                        <option value="OMN">Oman</option>
                                        <option value="PAK">Pakistan</option>
                                        <option value="PLW">Palau</option>
                                        <option value="PSE">Palestine, State of</option>
                                        <option value="PAN">Panama</option>
                                        <option value="PNG">Papua New Guinea</option>
                                        <option value="PRY">Paraguay</option>
                                        <option value="PER">Peru</option>
                                        <option value="PHL">Philippines</option>
                                        <option value="PCN">Pitcairn</option>
                                        <option value="POL">Poland</option>
                                        <option value="PRT">Portugal</option>
                                        <option value="PRI">Puerto Rico</option>
                                        <option value="QAT">Qatar</option>
                                        <option value="REU">Réunion</option>
                                        <option value="ROU">Romania</option>
                                        <option value="RUS">Russia</option>
                                        <option value="RWA">Rwanda</option>
                                        <option value="BLM">Saint Barthélemy</option>
                                        <option value="SHN">Saint Helena</option>
                                        <option value="KNA">Saint Kitts and Nevis</option>
                                        <option value="LCA">Saint Lucia</option>
                                        <option value="MAF">Saint Martin (French part)</option>
                                        <option value="SPM">Saint Pierre and Miquelon</option>
                                        <option value="VCT">Saint Vincent and the Grenadines</option>
                                        <option value="WSM">Samoa</option>
                                        <option value="SMR">San Marino</option>
                                        <option value="STP">Sao Tome and Principe</option>
                                        <option value="SAU">Saudi Arabia</option>
                                        <option value="SEN">Senegal</option>
                                        <option value="SRB">Serbia</option>
                                        <option value="SYC">Seychelles</option>
                                        <option value="SLE">Sierra Leone</option>
                                        <option value="SGP">Singapore</option>
                                        <option value="SXM">Sint Maarten (Dutch part)</option>
                                        <option value="SVK">Slovakia</option>
                                        <option value="SVN">Slovenia</option>
                                        <option value="SLB">Solomon Islands</option>
                                        <option value="SOM">Somalia</option>
                                        <option value="ZAF">South Africa</option>
                                        <option value="SGS">South Georgia and the South Sandwich Islands</option>
                                        <option value="SSD">South Sudan</option>
                                        <option value="ESP">Spain</option>
                                        <option value="LKA">Sri Lanka</option>
                                        <option value="SDN">Sudan</option>
                                        <option value="SUR">Suriname</option>
                                        <option value="SJM">Svalbard and Jan Mayen</option>
                                        <option value="SWE">Sweden</option>
                                        <option value="CHE">Switzerland</option>
                                        <option value="SYR">Syrian Arab Republic</option>
                                        <option value="TWN">Taiwan</option>
                                        <option value="TJK">Tajikistan</option>
                                        <option value="TZA">Tanzania</option>
                                        <option value="THA">Thailand</option>
                                        <option value="TLS">Timor-Leste</option>
                                        <option value="TGO">Togo</option>
                                        <option value="TKL">Tokelau</option>
                                        <option value="TON">Tonga</option>
                                        <option value="TTO">Trinidad and Tobago</option>
                                        <option value="TUN">Tunisia</option>
                                        <option value="TUR">Turkey</option>
                                        <option value="TKM">Turkmenistan</option>
                                        <option value="TCA">Turks and Caicos Islands</option>
                                        <option value="TUV">Tuvalu</option>
                                        <option value="UGA">Uganda</option>
                                        <option value="UKR">Ukraine</option>
                                        <option value="ARE">United Arab Emirates</option>
                                        <option value="GBR">United Kingdom</option>
                                        <option value="USA">United States</option>
                                        <option value="URY">Uruguay</option>
                                        <option value="UZB">Uzbekistan</option>
                                        <option value="VUT">Vanuatu</option>
                                        <option value="VEN">Venezuela</option>
                                        <option value="VNM">Vietnam</option>
                                        <option value="VGB">Virgin Islands (British)</option>
                                        <option value="VIR">Virgin Islands (U.S.)</option>
                                        <option value="WLF">Wallis and Futuna</option>
                                        <option value="ESH">Western Sahara</option>
                                        <option value="YEM">Yemen</option>
                                        <option value="ZMB">Zambia</option>
                                        <option value="ZWE">Zimbabwe</option>
                                    </select>

                                </div>
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control px-2" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                                        placeholder="Enter your 6-digit postal code" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center bottom-section">
                                <div class="login-link">
                                    <p>Already have an account? <a href="{{ route('showClientLogin') }}" class="login-here">Login Here</a></p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-back mb-0" onclick="previousStep()">Back</button>
                                    <button type="submit" class="btn btn-continue mb-0" id="create-btn">Create</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>


    <!--Phone Code JS-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var input = document.querySelector("#telephone");
            var iti = window.intlTelInput(input, {
                initialCountry: "gb",
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.min.js"
            });

            function updatePhoneFields() {
                var countryCode = '+' + iti.getSelectedCountryData().dialCode;
                var fullPhone = iti.getNumber(); // e.g., +447911123456
                var localNumber = fullPhone.replace(countryCode, ''); // Remove country code

                document.querySelector("#countryCode").value = countryCode;
                document.querySelector("#phone").value = localNumber;
            }

            input.addEventListener('input', updatePhoneFields);
            iti.onCountryChange(updatePhoneFields);

            document.querySelector("#sign-up-form").addEventListener('submit', function() {
                updatePhoneFields(); // Ensure latest values
            });
        });
    </script>

    </script>

    <!-- Email -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const continueButton = document.getElementById('acc-info');

            function toggleContinueButton1() {
                // Check if the input value is a valid email
                const isValidEmail = emailInput.validity.valid;

                continueButton.disabled = !isValidEmail; // Disable if not valid

                // Add or remove the is-invalid class
                if (!isValidEmail) {
                    emailInput.classList.add('is-invalid'); // Add class for invalid input
                } else {
                    emailInput.classList.remove('is-invalid'); // Remove class for valid input
                }
            }

            // Initial check
            toggleContinueButton1();

            // Add event listener for input event
            emailInput.addEventListener('input', toggleContinueButton1);
        });
    </script>

    <!-- Show/Hide Password-->
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
    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                @php
                session()->forget('success');
                @endphp
                location.reload(); // Reload the page
            }
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: true
        }).then((result) => {
            // Flush the session message
            @php
            session()->forget('error');
            @endphp
        });
    </script>
    @endif

    <!-- SweetAlert2 Js-->

    <script>
        let currentStep = 1;

        // Step descriptions
        const stepTexts = {
            1: 'Account Information',
            2: 'Personal Information',
            3: 'Residential Information'
        };

        // Function to update step text
        function updateStepText(step) {
            const stepTextContainer = document.getElementById('step-text');
            stepTextContainer.textContent = stepTexts[step];
        }

        // Function to move to the next step
        function nextStep() {

            const currentForm = document.getElementById(`step-${currentStep}`);
            const nextForm = document.getElementById(`step-${currentStep + 1}`);

            if (nextForm) {
                currentForm.classList.remove('active');
                nextForm.classList.add('active');

                // Update progress bar
                document.querySelectorAll('.progress-step')[currentStep].classList.add('active');

                // Update step text
                currentStep++;
                updateStepText(currentStep);

                // Recheck fields in the new step
                checkFormFields();
            }
        }

        // Function to move to the previous step (back)
        function previousStep() {
            const currentForm = document.getElementById(`step-${currentStep}`);
            const previousForm = document.getElementById(`step-${currentStep - 1}`);

            if (previousForm) {
                currentForm.classList.remove('active');
                previousForm.classList.add('active');

                // Update progress bar
                document.querySelectorAll('.progress-step')[currentStep - 1].classList.remove('active');

                // Update step text
                currentStep--;
                updateStepText(currentStep);

                // Recheck fields in the previous step
                checkFormFields();
            }
        }

        // Function to check if all fields in the current step are filled
        function checkFormFields() {
            const currentInputs = document.querySelectorAll(`#step-${currentStep} input, #step-${currentStep} select`); // Include <select> elements
            let allFilled = true;

            currentInputs.forEach(input => {
                // Skip the VAT number input field from validation
                if (input.id !== 'vat_number' && !input.value) {
                    allFilled = false;
                }
            });

            const continueButton = document.querySelector(`#step-${currentStep} .btn-continue`);
            if (allFilled) {
                continueButton.classList.remove('disabled');
                continueButton.disabled = false;
            } else {
                continueButton.classList.add('disabled');
                continueButton.disabled = true;
            }
        }

        // Add event listeners for both <input> and <select> fields to trigger validation
        document.querySelectorAll('input, select').forEach(element => {
            element.addEventListener('input', checkFormFields);
        });

        // Initial text update and form check
        updateStepText(currentStep);
        checkFormFields();
    </script>

</body>

</html>