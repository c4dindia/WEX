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
@php
$cU_currency_code = '$';
$maxlimits = 100;
@endphp

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
                    <!-- Import Button-->
                    <button type="button" class="btn topup-btn-bg first" data-bs-toggle="modal" data-bs-target="#importModal">
                        IMPORT
                    </button>
                    <!-- Import excel Modal-->
                    <div class="modal fade" id="importModal" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Import Cards</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body expenseCard-modal-bg">
                                    <form action="{{ route('importCards') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="name" class="form-label">File (Required .xlsx)</label>
                                            <input type="file" class="form-control mb-2" id="file" name="file" required style="background-color: aliceblue;" accept=".xlsx">
                                            <span style="color: var(--theem-text-color); ">All Columns are required to add:<br>
                                                <small class="text-secondary">
                                                    First name => <span class="text-black">string</span> <br>
                                                    Last Name => <span class="text-black">string </span><br>
                                                    Months Untill Expire => <span class="text-black">Integer (max 36 months)</span><br>
                                                    Type => <span class="text-black">Virtual or Physical</span><br>
                                                </small>
                                            </span>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="close-modalfooter-btn expensecard-create-btn animate-btn" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" id="importBtn" class="expensecard-import-btn animate-btn">Import</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
                                    <div role="button" class="close cross-button-create-modal" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </div>
                                </div>
                                <div class="modal-body create-card-body">

                                    <!-- Form Step 1 -->
                                    <div class="form-step step-1 d-block">
                                        <form id="add-card-form" action="{{ route('saveCards') }}" method="POST">
                                            @csrf

                                            <div class="row">
                                                <div class="col-md-12 d-flex gap-2">
                                                    <div class="form-group mt-3" style="width: 100%;">
                                                        <label for="firstName" class="createCard-label">First Name</label>
                                                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" required>
                                                    </div>
                                                    <div class="form-group mt-3" style="width: 100%;">
                                                        <label for="lastName" class="createCard-label">Last Name</label><br>
                                                        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 d-flex gap-2">
                                                    <div class="form-group mt-3" style="width: 100%;">
                                                        <label for="organization" class="createCard-label">Organization</label><br>
                                                        <select id="organization" class="form-select" name="organization" required>
                                                            <option value="" selected disabled>Select Organization</option>
                                                            <option value="MMDA TR-MC-5551 (0007776)">MMDA TR-MC-5551</option>
                                                            <option value="MMDA TR-MC-555243 (0007774)">MMDA TR-MC-555243</option>
                                                            <option value="MMDA TR-MC-555244 (0007777)">MMDA TR-MC-555244</option>
                                                            <option value="MMDA TR-MC-5569 (0007775)">MMDA TR-MC-5569</option>
                                                            <option value="MMDA TR-V-4859 (0007778)">MMDA TR-V-4859</option>
                                                            <option value="MMDA TR-V-428868 (0008771)">MMDA TR-V-428868</option>
                                                            <option value="MMDA TR-V-428869 (0008772)">MMDA TR-V-428869</option>
                                                            <option value="MMDA TR-V-428870 (0008773)">MMDA TR-V-428870</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mt-3" style="width: 100%;">
                                                        <label for="amount" class="createCard-label">Amount ($)</label><br>
                                                        <input type="number" class="form-control" min="1" id="amount" name="amount" placeholder="Enter amount" required>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn expensecard-import-btn submit-step animate-btn" id="submit-btn" disabled>Create</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
            <hr style="padding: 0; margin: 0;">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12 table-wrapper scrollable-table expensecard-table table-responsive">
            <table class="table table-striped mt-4 rounded-4">
                <thead>
                    <tr>
                        <th class="p-3 tablecheck-Box-Max-width"></th>
                        <th class="p-3" style="width: 20%">CARD NUMBER </th>
                        <th class="p-3">Organization</th>
                        <th class="p-3">NAME ON CARD </th>
                        <th class="p-3 text-center" style="width: 15%">EXPIRY DATE </th>
                        <th class="p-3 text-center" style="width: 15%">TYPE</th>
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
                        @php
                        $maskedCard = substr($card->card_number, 0, 4)
                        . 'XXXXXXXX'
                        . substr($card->card_number, -4);
                        @endphp
                        <td class="maskcard-number" style="width: 20%"> <a href="{{ url('/card') }}/{{ $card->id }}"> {{ $maskedCard }} </a></td>
                        <td class=""> <a href="{{ url('/card') }}/{{ $card->id }}" style="color:rgb(0, 0, 0);"> {{ $card->org_name }}</a></td>
                        <td class=""> <a href="{{ url('/card') }}/{{ $card->id }}" style="color: rgb(0, 0, 0);">{{ $card->cardholder_name }}</a> </td>
                        <td class="text-center" style="width: 15%"> <a href="{{ url('/card') }}/{{ $card->id }}" style="color: rgb(0, 0, 0);"> {{ \Carbon\Carbon::parse($card->expiry_date)->format('m/y')}} </a></td>
                        <td class="topUp-completed-color text-center " style="width: 15%"> <a href="{{ url('/card') }}/{{ $card->id }}" style="color: rgb(0, 0, 0);"> {{ $card->card_type }} </a></td>
                        @if ($card->status == '1')
                        <td class="expenseCrad-active text-center fw-bold" style="width: 10%"> Active •</td>
                        @else
                        <td class="text-secondary text-center fw-bold" style="width: 10%"> Close •</td>
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
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('submit-btn');
        const form = document.getElementById('add-card-form');
        const ids = ['firstName', 'lastName', 'amount', 'organization'];

        const check = () => {
            btn.disabled = !ids.every(id => document.getElementById(id)?.value.trim());
        };

        ids.forEach(id => {
            const el = document.getElementById(id);
            el?.addEventListener('input', check);
            el?.addEventListener('change', check);
        });

        form.addEventListener('submit', () => {
            btn.disabled = true;
            btn.textContent = 'Creating..';
        });

        check();
    });

    $(document).on('click', '.cross-button-create-modal', function() {
        $('#exampleModal').modal('hide');
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('#importModal form');
        const submitBtn = document.getElementById('importBtn');

        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerText = 'Importing...';
        });
    });
</script>


@endsection