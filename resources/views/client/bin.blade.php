@extends('layouts.clientMaster')

@section('title')
@php
$activePage = 'expense card';
@endphp
Expense Card
@endsection

@section('pagecontent')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('showClientDashboard') }}" style="text-decoration: none; color:black">Home</a></li>
        <li class="breadcrumb-item breadcrumb-text-color"><a href="#" style="text-decoration: none;">Expense Card</a></li>
    </ol>
</nav>

<section>
    <div class="row d-flex align-items-center justify-content-between mb-2">
        <div class="col-md-6">
            <h4 class="dark-text-weight">Choose your bin</h4>
        </div>

        <hr style="padding: 0; margin: 0;">
    </div>


    <div class="row g-3 pt-5">
        <!-- Card 1 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-MC-5551'">
            <div class="card text-center bin-card h-100">
                <div class="card-body">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" class="mb-3" width="60" />
                    <h6 class="fw-bold">MMDA TR-MC-5551</h6>
                    <p class="mb-0">MasterCard ID: 0007776</p>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-MC-555243'">
            <div class="card text-center bin-card h-100">
                <div class="card-body">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" class="mb-3" width="60" />
                    <h6 class="fw-bold">MMDA TR-MC-555243</h6>
                    <p class="mb-0">MasterCard ID: 0007774</p>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-MC-555244'">
            <div class="card text-center bin-card h-100">
                <div class="card-body">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" class="mb-3" width="60" />
                    <h6 class="fw-bold">MMDA TR-MC-555244</h6>
                    <p class="mb-0">MasterCard ID: 0007777</p>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-MC-5569'">
            <div class="card text-center bin-card h-100">
                <div class="card-body">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" class="mb-3" width="60" />
                    <h6 class="fw-bold">MMDA TR-MC-5569</h6>
                    <p class="mb-0">MasterCard ID: 0007775</p>
                </div>
            </div>
        </div>

        <!-- Card 5 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-V-428868'">
            <div class="card text-center bin-card h-100">
                <div class="card-body">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="mb-3" width="60" />
                    <h6 class="fw-bold">MMDA TR-V-428868</h6>
                    <p class="mb-0">Visa ID: 0008771</p>
                </div>
            </div>
        </div>

        <!-- Card 6 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-V-428869'">
            <div class="card text-center bin-card h-100">
                <div class="card-body">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="mb-3" width="60" />
                    <h6 class="fw-bold">MMDA TR-V-428869</h6>
                    <p class="mb-0">Visa ID: 0008772</p>
                </div>
            </div>
        </div>

        <!-- Card 7 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-V-428870'">
            <div class="card text-center bin-card h-100">
                <div class="card-body">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="mb-3" width="60" />
                    <h6 class="fw-bold">MMDA TR-V-428870</h6>
                    <p class="mb-0">Visa ID: 0008773</p>
                </div>
            </div>
        </div>

        <!-- Card 8 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-V-4859'">
            <div class="card text-center bin-card h-100">
                <div class="card-body">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="mb-3" width="60" />
                    <h6 class="fw-bold">MMDA TR-V-4859</h6>
                    <p class="mb-0">Visa ID: 0007778</p>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection

@section('scripts')

@endsection