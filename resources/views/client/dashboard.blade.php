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

<div class="body-content">
    <div class="body-content-header">
        <h5>Dashboard</h5>

    </div>
    <hr>
    <div class="card">
        <div class="table-responsive mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ACCOUNT </th>
                        <th>NUMBER OF CARDS</th>
                        <th>BALANCE ($)</th>
                        <th>PENDING($)</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-light">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</td>
                        <td class="text-light">
                            <a href="{{ route('bin') }}" style="color: var(--buttonBg-dark-color); text-decoration: none">{{ $cardsCount }}</a>
                        </td>
                        <td>-</td>
                        <td>-</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if(session('showAssignExpenseCardModal'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        myModal.show();
    });
</script>
@endif
@endsection