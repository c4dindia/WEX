@extends('layouts.master')
@section('title')
Registeration Details
@endsection
@section('pagecontent')
<div class="row col-12 ">
    {{-- account details --}}
    <div class="container-fluid py-4 col-lg-6">

        <div class="row mb-4">
            <div class="col-lg-12 col-md-8 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-12 col-7 ">

                            </div>

                        </div>
                    </div>
                    <div class="row p-4">
                        <div class="card-body p-2">
                            <h5 class="px-4"><strong>Company Details:</strong></h5>
                            <div class="table-responsive mt-4 px-2 ">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <th>Category</th>
                                        <th class="text-end px-4">Details</th>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td><strong>Company Name:</strong></td>
                                            <td class="text-end">{{ $userData->company_name }}</td>
                                        </tr>
                                        <td><strong>Email:</strong></td>
                                        <td class="text-end " style="color: rgb(14, 136, 207)">{{ $userData->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date of Birth:</strong></td>
                                            <td class="text-end">
                                                {{ \Carbon\Carbon::parse($userData->dob)->format('M d, Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone Number:</strong></td>
                                            <td class="text-end ">{{$userData->country_code}}{{ $userData->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Company Type:</strong></td>
                                            <td class="text-end">{{ ucwords(str_replace('_', ' ', $userData->type_of_industry)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date of Incorporation:</strong></td>
                                            <td class="text-end">
                                                {{ \Carbon\Carbon::parse($userData->date_of_incorporation)->format('M d, Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Staff Size:</strong></td>
                                            <td class="text-end"><b>{{ $userData->number_of_employees }} Employees</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- topUp details --}}
    <div class="container-fluid py-4 col-lg-6">

        <div class="row mb-4">
            <div class="col-lg-12 col-md-8 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-12 col-7 ">

                            </div>

                        </div>
                    </div>
                    <div class="row p-4">
                        <div class="card-body  p-2">
                            <h5 class="px-4"><strong>Address Details:</strong></h5>
                            <div class="table-responsive mt-4 px-2">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <th>Category</th>
                                        <th class="text-end px-4">Details</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Address:</strong></td>
                                            <td class="text-end"> {{ $userData->address }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>City:</strong></td>
                                            <td class="text-end">{{ $userData->city }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>State:</strong></td>
                                            <td class="text-end">{{ $userData->state }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Country:</strong></td>
                                            <td class="text-end text-bold"><strong>{{ $userData->country }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Postal Code:</strong></td>
                                            <td class="text-end">{{ $userData->postal_code }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Vat Number:</strong></td>
                                            <td class="text-end">{{ $userData->vat_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Registration Number:</strong></td>
                                            <td class="text-end">{{ $userData->registration_number }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

</main>
@endsection
@section('scripts')

@endsection