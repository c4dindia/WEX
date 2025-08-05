@extends('layouts.clientMaster')

@section('title')
@php
$activePage = 'account';
@endphp
Accounts
@endsection

@section('css')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<style>
    /* New class to handle text truncation with ellipsis */
    .truncate-text,
    .truncate-text p {
        display: -webkit-box;
        /* Use flexbox layout for truncation */
        -webkit-line-clamp: 1;
        /* Limit to 1 line */
        -webkit-box-orient: vertical;
        /* Ensure content flows vertically */
        overflow: hidden;
        /* Hide overflowed content */
        text-overflow: ellipsis;
        /* Show ellipsis when content is truncated */
        white-space: normal;
        /* Allow wrapping */
        max-width: 100%;
    }
</style>
@endsection

@section('pagecontent')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('showClientDashboard') }}" style="text-decoration: none; color:black">Home</a></li>
        <li class="breadcrumb-item breadcrumb-text-color"><a href="#" style="text-decoration: none;">Account Details</a></li>
    </ol>
</nav>
<section>
    <div class="row d-flex align-items-center justify-content-between">
        <div class="col-md-6">
            <h4 class="dark-text-weight">Account</h4>
        </div>

        <hr style="padding: 0; margin: 0;">
    </div>
    <div class="row mt-3">
        <div class="col-md-6 forDetails-section">
            <div class="details-wrapper-accountdeatil table-responsive">
                <h4 class="card-details-h4-tag">Details</h4>
                <table>
                    <tbody>
                        <tr>
                            <td class="details-data  p-1" style="color: #5a5a5a">Account</td>
                            <td class="">
                                <a href="{{ route('showClientDashboard') }}" style="color:var(--buttonBg-dark-color); text-decoration:underline !important">
                                    {{ $account->first_name }} {{ $account->last_name }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="details-data  p-1" style="color: #5a5a5a">Email</td>
                            <td class="">{{ $account->email }}</td>
                        </tr>
                        <tr>
                            <td class="details-data  p-1" style="color: #5a5a5a">Date of Birth</td>
                            <td class="">{{ \Carbon\Carbon::parse($account->dob)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="details-data  p-1" style="color: #5a5a5a">Phone</td>
                            <td class="">{{ $account->country_code }}{{ $account->phone }}</td>
                        </tr>
                        <tr>
                            <td class="details-data  p-1" style="color: #5a5a5a">Status</td>
                            @if ($account->status == '1')
                            <td class="acoountdetail-active"><a href="#" style="color: #00C008;"> Active • </a></td>
                            @else
                            <td class="text-secondary"><a href="#" style="color:rgb(220,53,69)"> Inactive • </a></td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6 forDetails-section">
            <div class="details-wrapper-accountdeatil table-responsive">
                <h4 class="card-details-h4-tag">Residential</h4>
                <table>
                    <tbody>
                        <tr>
                            <td class="details-data  p-1" style="color: #5a5a5a">Address</td>
                            <td class="">{{ $account->address }}</td>
                        </tr>
                        <tr>
                            <td class="details-data p-1" style="color: #5a5a5a">City</td>
                            <td class="">{{ $account->city }}</td>
                        </tr>
                        <tr>
                            <td class="details-data p-1" style="color: #5a5a5a">State</td>
                            <td class="">{{ $account->state }}</td>
                        </tr>
                        <tr>
                            <td class="details-data p-1" style="color: #5a5a5a">Country</td>
                            <td class="">{{ $account->country }}</td>
                        </tr>
                        <tr>
                            <td class="details-data p-1" style="color: #5a5a5a">Postal Code</td>
                            <td class="">{{ $account->postal_code }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
@endsection