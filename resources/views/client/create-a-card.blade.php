@extends('layouts.clientMaster')

@section('title')
@php
$activePage = 'create a card';
@endphp
Create A Card
@endsection

@section('css')

@endsection

@section('pagecontent')
<!-- start content -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('showClientDashboard') }}" style="text-decoration: none; color:black">Home</a></li>
        <li class="breadcrumb-item breadcrumb-text-color"><a href="#" style="text-decoration: none">Create a card</a></li>
    </ol>
</nav>
<section>
    <div class="row">
        <div class="col-md-12">
            <h4 class="dark-text-weight">Create a card</h4>
            <hr style="padding: 0; margin: 0;">
            <p class="mt-3">Please choose what kind of card you want to create</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex gap-3 justify-content-center">
                <div class="card text-center" style="width: 18rem;">
                    <img src="{{ asset('ClientCss/images/card-img.png') }}" class="card-img-top" alt="Expense Card">
                    <div class="card-body">
                        <h5 class="card-title createCard-heading">Expense Card</h5>
                        <p class="createcard-p1">For Employees & Directors</p>
                        <p class="card-text createCard-para">Physical or Virtual VISA card for simplifying
                            your company’s
                            expenses.</p>
                        <a href="{{ route('bin') }}" class="btn button-bg-yes text-uppercase" style="width: 100%;">Create Card</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
@endsection