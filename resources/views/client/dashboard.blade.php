@extends('layouts.clientMaster')

@section('title')
@php
$activePage = 'dashboard';
@endphp
Dashboard
@endsection

@section('css')
@endsection

@section('pagecontent')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('showClientDashboard') }}" style="text-decoration: none; color:black">Home</a></li>
        <li class="breadcrumb-item breadcrumb-text-color"><a href="#" style="text-decoration: none;">Dashboard</a></li>
    </ol>
</nav>

<section>
    <div class="row d-flex align-items-center justify-content-between mb-2">
        <div class="col-md-6">
            <h4 class="dark-text-weight">Dashboard</h4>
            <h4 class="">Summary Statement</h4>
        </div>

        <hr style="padding: 0; margin: 0;">
    </div>

    <div class="row">
        <div class="p-2 ">
            <div class="table-wrapper scrollable-table" style="max-height: 500px;overflow-x: auto;">
                <table class="table table-striped mt-4 rounded-4" style="width: 1523px;">
                    <thead>
                        <tr>
                            <th class="text-center" style="color: #5a5a5a">ACCOUNT</th>
                            <th class="text-center" style="color: #5a5a5a">NUMBER OF CARDS</th>
                            <th class="text-end " style="color: #5a5a5a">BALANCE ($)</th>
                            <th class=" text-end" style="color: #5a5a5a">PENDING ($)</th>
                            <th class="text-end px-4" style="color: #5a5a5a">AVAILABLE ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><a href="{{ route('showClientsAccountsPage') }}" style="color: var(--buttonBg-dark-color)">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</a></td>
                            <td class="text-center"><a href="{{ route('showCard') }}" style="color: var(--buttonBg-dark-color)">{{ $cardsCount }}</a></td>
                            <td class=" text-end"><a href="{{ route('showClientStatements') }}" class="px-4" style="color: black">-</a></td>
                            <td class=" text-end"><a href="{{ route('showClientStatements') }}" class="px-4" style="color: black">
                                    -
                                </a>
                            </td>
                            <td class=" text-end"><a href="{{ route('showClientStatements') }}" class="px-4" style="color: black">-</a></td>
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