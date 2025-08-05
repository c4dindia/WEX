@extends('layouts.master')
@section('title')
Role
@endsection
@section('css')
<!-- jsvectormap css -->
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
Role
@endsection

@section('body')

<body>
    @endsection
    @section('content')
    <div class="row justify-content-center">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Role</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('update_role') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="id" value="@if(!empty($role)){{$role->id}}@endif">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                placeholder="Enter name" value="@if(!empty($role)){{$role->name}}@endif" oninput=" this.value = this.value.replace(/[0-9]/g,'')" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label mb-3" for="name">Status</label>
                            <div class="d-flex">
                                <div class="form-check">
                                    <input class="form-check-input @error('status') is-invalid @enderror" type="radio"
                                        name="status" value="Active" required {{ $role->status == 'Active' ? 'checked' : ''
                                    }}>
                                    <label class="form-check-label" for="status">
                                        Active
                                    </label>
                                </div>
                                <div class="w-25"></div>
                                <div class="form-check">
                                    <input class="form-check-input @error('status') is-invalid @enderror" type="radio"
                                        name="status" value="Inactive" required {{ $role->status == 'Inactive' ? 'checked' : ''
                                    }}>
                                    <label class="form-check-label" for="status">
                                        Inactive
                                    </label>
                                </div>
                            </div>
                            @error('status')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="/role" class="btn btn-danger me-1 px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary w-md">Update</button>
                        </div>
                    </form>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
    </div>
    @endsection
