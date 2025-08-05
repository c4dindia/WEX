@extends('layouts.master')
@section('title')
    Registration-Approval
@endsection
@section('pagecontent')
    <div class="container-fluid py-4">

        <div class="row mb-4">
            <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-12 col-7 ">

                            </div>

                        </div>
                    </div>
                    <div class="card-body px-0 py-0 pb-2">
                        <h5 class="px-4"><strong>List of Registration Approvals</strong></h5>
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class=" text-secondary text-md font-weight-bold  text-center"> #</th>
                                        <th class=" text-secondary text-md font-weight-bold  ps-2 ">Company Name</th>
                                        <th class=" text-secondary text-md font-weight-bold  ps-2 "> Email</th>
                                        <th class=" text-secondary text-md font-weight-bold  ps-2 "> Phone No</th>
                                        <th class=" text-secondary text-md font-weight-bold  ps-2 "> City</th>
                                        <th class=" text-secondary text-md font-weight-bold  ps-2 "> State</th>
                                        <th class=" text-secondary text-md font-weight-bold  ps-2 "> Country</th>
                                        <th class=" text-secondary text-md font-weight-bold text-center">Request Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @if ($requests->isEmpty())
                                        <tr>
                                            <td colspan="3" class="text-center">No Registration Requests Pending...</td>
                                        </tr>
                                    @else
                                    @foreach ($requests as $account)
                                        @php
                                            $i++;
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                <h6>{{ $i }}</h6>
                                            </td>
                                            <td><a href="{{ url('/registration/details') }}/{{ $account->user_id }}">{{ $account->company_name }}</a></td>
                                            <td><a href="{{ url('/registration/details') }}/{{ $account->user_id }}">{{ $account->email }}</a></td>
                                            <td>{{ $account->country_code }}{{ $account->phone }}</td>
                                            <td>{{ $account->city }}</td>
                                            <td>{{ $account->state }}</td>
                                            <td>{{ $account->country }}</td>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <a href="{{ url('/registration-approval/save') }}/{{ $account->user_id }}" class="pr-3">
                                                    <span class=" font-weight-bold" title="Accept"><i class="fas fa-check p-3"></i></span>
                                                </a>
                                                <a href="{{ url('/delete-registration-request') }}/{{ $account->user_id }}">
                                                    <span class=" font-weight-bold" title="Delete"><i class="fas fa-times"></i></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
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
