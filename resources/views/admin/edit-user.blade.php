@extends('layouts.master')

@section('title')
Edit-user
@endsection

@section('css')
<!-- jsvectormap css -->
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('pagecontent')
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Edit User</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('update_user') }}" method="post" enctype="multipart/form-data" onsubmit="return validateCheckbox()">
                    @csrf

                    <input type="hidden" name="id" value="@if(!empty($user)){{$user->id}}@endif">
                    <div class="mb-3">
                        <label for="name" class="form-label">First Name</label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" placeholder="Enter first name" value="@if(!empty($user)){{$user->first_name}}@endif" oninput=" this.value = this.value.replace(/[0-9]/g,'')" required>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Last Name</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" placeholder="Enter last name" value="@if(!empty($user)){{$user->last_name}}@endif" oninput=" this.value = this.value.replace(/[0-9]/g,'')" required>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" style="background: #1A9AAB; color:#FDFDFD" name="email" placeholder="Enter email" value="@if(!empty($user)){{$user->email}}@endif" disabled required>
                    </div>

                    <div class="mb-4">
                        <div class="table-responsive mb-3">
                            <label class="form-label">Role List</label>
                            <table class="table table-nowrap align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4" style="width: 50px;">
                                            <div class="form-check font-size-16">
                                                <input type="checkbox" class="form-check-input" id="role_check">
                                                <label class="form-check-label" for="role_check"></label>
                                            </div>
                                        </th>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php($i=1)
                                    @foreach ($roles as $role)
                                    <tr>
                                        <th scope="row" class="ps-4">
                                            <div class="form-check font-size-16">
                                                <input type="checkbox" class="form-check-input @error('role') is-invalid @enderror role-checkbox" name="role[]" value="{{$role->id}}" @if(in_array($role->name, $user_role->toArray())) checked @endif>
                                                <label class="form-check-label" for="role"></label>
                                            </div>
                                        </th>
                                        <td>{{$i++}}</td>
                                        <td>{{$role->name}}</td>
                                        <td>{{$role->status}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div id="checkboxError" class="text-danger"></div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="/user" class="btn btn-danger me-1 px-4">Cancel</a>
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

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const mainCheckbox = document.getElementById('role_check');
        const roleCheckboxes = document.querySelectorAll('.role-checkbox');

        mainCheckbox.addEventListener('change', function() {
            const isChecked = mainCheckbox.checked;
            roleCheckboxes.forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
        });
    });

    function validateCheckbox() {
        var checkboxes = document.getElementsByName("role[]");
        var isChecked = false;
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                isChecked = true;
                break;
            }
        }
        if (!isChecked) {
            document.getElementById("checkboxError").innerText = "Please select at least one role.";
            return false;
        } else {
            document.getElementById("checkboxError").innerText = "";
        }
        return true;
    }
</script>
@endsection