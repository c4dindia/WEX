@extends('layouts.clientMaster')

@section('title')
@php
$activePage = 'expense card';
@endphp
Create A Card
@endsection

@section('css')
<style>
    .card {
        width: 92%;
        height: 90%;
    }

    .card-body {
        padding: 30px 20px;
    }
</style>
@endsection

@section('pagecontent')
<div class="body-content">
    <div class="body-content-header">
        <h5>Choose your bin</h5>

    </div>
    <hr>
    <div class="row g-3 pt-5">
        <!-- Card 1 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-MC-5551'">
            <div class="card text-center bin-card">
                <div class="card-body">
                    <img src="{{ asset('ClientCss/images/Mastercard-logo.png') }}" class="mb-4" width="70" />
                    <h6 class="fw-bold">MMDA TR-MC-5551</h6>
                    <p class="mb-0">MasterCard ID: 0007776</p>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-MC-555243'">
            <div class="card text-center bin-card">
                <div class="card-body">
                    <img src="{{ asset('ClientCss/images/Mastercard-logo.png') }}" class="mb-4" width="70" />
                    <h6 class="fw-bold">MMDA TR-MC-555243</h6>
                    <p class="mb-0">MasterCard ID: 0007774</p>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-MC-555244'">
            <div class="card text-center bin-card">
                <div class="card-body">
                    <img src="{{ asset('ClientCss/images/Mastercard-logo.png') }}" class="mb-4" width="70" />
                    <h6 class="fw-bold">MMDA TR-MC-555244</h6>
                    <p class="mb-0">MasterCard ID: 0007777</p>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-MC-5569'">
            <div class="card text-center bin-card">
                <div class="card-body">
                    <img src="{{ asset('ClientCss/images/Mastercard-logo.png') }}" class="mb-4" width="70" />
                    <h6 class="fw-bold">MMDA TR-MC-5569</h6>
                    <p class="mb-0">MasterCard ID: 0007775</p>
                </div>
            </div>
        </div>

        <!-- Card 5 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-V-428868'">
            <div class="card text-center bin-card">
                <div class="card-body">
                    <img src="{{ asset('ClientCss/images/visa-logo.svg') }}" class="mb-4" width="70" />
                    <h6 class="fw-bold">MMDA TR-V-428868</h6>
                    <p class="mb-0">Visa ID: 0008771</p>
                </div>
            </div>
        </div>

        <!-- Card 6 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-V-428869'">
            <div class="card text-center bin-card">
                <div class="card-body">
                    <img src="{{ asset('ClientCss/images/visa-logo.svg') }}" class="mb-4" width="70" />
                    <h6 class="fw-bold">MMDA TR-V-428869</h6>
                    <p class="mb-0">Visa ID: 0008772</p>
                </div>
            </div>
        </div>

        <!-- Card 7 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-V-428870'">
            <div class="card text-center bin-card">
                <div class="card-body">
                    <img src="{{ asset('ClientCss/images/visa-logo.svg') }}" class="mb-4" width="70" />
                    <h6 class="fw-bold">MMDA TR-V-428870</h6>
                    <p class="mb-0">Visa ID: 0008773</p>
                </div>
            </div>
        </div>

        <!-- Card 8 -->
        <div class="col-md-3" role="button" onclick="window.location.href='/cards/MMDA TR-V-4859'">
            <div class="card text-center bin-card">
                <div class="card-body">
                    <img src="{{ asset('ClientCss/images/visa-logo.svg') }}" class="mb-4" width="70" />
                    <h6 class="fw-bold">MMDA TR-V-4859</h6>
                    <p class="mb-0">Visa ID: 0007778</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection