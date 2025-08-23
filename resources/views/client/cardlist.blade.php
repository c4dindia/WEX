@extends('layouts.clientMaster')

@section('title')
@php
$activePage = 'expense card';
@endphp
Expense Card
@endsection

@section('pagecontent')
<div class="body-content">
    <div class="body-content-header">
        <h5>Expense Card </h5>
        <div id="add-card-div">
            <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#importModal">
                Import Cards
            </button>

            <div class="modal" id="importModal">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Import Cards</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body expenseCard-modal-bg">
                            <form action="{{ route('importCards') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="orgId" value="{{ $orgId }}">
                                <input type="hidden" name="orgName" value="{{ $bin }}">
                                <div>
                                    <label for="name" class="form-label">File (Required .xlsx)</label>
                                    <input type="file" class="form-control mb-3" id="file" name="file" required accept=".xlsx">
                                    <span style="color: var(--theem-text-color); ">All Columns are required to add:<br>
                                        <small class="text-secondary">
                                            First name => <span class="text-black">string</span> <br>
                                            Last Name => <span class="text-black">string </span><br>
                                            Amount (USD) => <span class="text-black">Integer</span><br>
                                        </small>
                                    </span>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <div style="overflow:auto;">
                                <div style="float:right;">
                                    <button type="button" class="prevBtn me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" id="importBtn" class="nextBtn">Import</button>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Create Card
            </button>

            <div class="modal" id="exampleModal">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Create a Card </h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body create-card-body">
                            <div class="form-step step-1" style="display: block;">
                                <form id="add-card-form" action="{{ route('saveCards') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="orgId" value="{{ $orgId }}">
                                    <input type="hidden" name="orgName" value="{{ $bin }}">
                                    <div class="row">
                                        <div class="col-md-12 d-flex gap-3">
                                            <div class="form-group" style="width: 100%;">
                                                <label for="firstName" class="createCard-label">First Name</label>
                                                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" required>
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <label for="lastName" class="createCard-label">Last Name</label><br>
                                                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-12 d-flex gap-3">
                                            <div class="form-group mt-3" style="width: 100%;">
                                                <label for="amount" class="createCard-label">Amount (USD)</label>
                                                <input type="number" class="form-control" min="1" id="amount" name="amount" placeholder="Enter amount" required>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div style="overflow:auto;">
                                <div style="float:right;">
                                    <button type="button" class="prevBtn me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="submit-step nextBtn" id="submit-btn" disabled>Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <hr>
    <div class="card">
        <div class="table-responsive mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>CARD NUMBER</th>
                        <th>ORGANISATION</th>
                        <th>NAME ON CARD</th>
                        <th>EXPIRY DATE</th>
                        <th>TYPE</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($cards) == 0)
                    <tr>
                        <td colspan="6" class="text-center">NO RECORDS AVAILABLE</td>
                    </tr>
                    @else

                    @foreach ($cards as $card)
                    @php
                    $maskedCard = substr($card->card_number, 0, 4)
                    . 'XXXXXXXX'
                    . substr($card->card_number, -4);
                    @endphp
                    <tr onclick="window.location.href='/card/{{ $card->id }}'" role="button">
                        <td class="text-light">{{ $maskedCard }}</td>
                        <td>{{ $card->org_name }}</td>
                        <td>{{ $card->cardholder_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($card->expiry_date)->format('m/y')}}</td>
                        <td>{{ $card->card_type }}</td>
                        @if ($card->status == '1')
                        <td class="text-success">Active •</td>
                        @else
                        <td class="text-secondary">Close •</td>
                        @endif
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('submit-btn');
        const form = document.getElementById('add-card-form');
        const ids = ['firstName', 'lastName', 'amount'];

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