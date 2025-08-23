@extends('layouts.clientMaster')

@section('title')
@php
$activePage = 'create a card';
@endphp
Create A Card
@endsection

@section('pagecontent')
<div class="body-content">
    <div class="body-content-header">
        <h5>Create a card</h5>

    </div>
    <hr>
    <div class="row gap-5">
        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-12">
            <div class="img-card">
                <img class="card-img mb-4" src="{{ asset('newUI/images/card.png') }}" alt="Card image">
                <h5 class="mb-1">Expense Card</h5>
                <span>For Employees & Directors</span>
                <p class="mt-3">Physical or Virtual VISA card for simplifying your company’s expenses.</p>
                <div class="submit-btn">
                    <button type="button" class="btn btn-primary" onclick="location.href='/bin'">Create Card</button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection