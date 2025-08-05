@extends('layouts.master')
@section('title')
    User
@endsection
@section('pagecontent')

    @if (session('success'))
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        swal({
            title: "Success!",
            text: "{{ session('success') }}",
            icon: "success",
            button: false,
            timer: 5000
        }).then(function() {
            location.reload();
        });
    </script>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">User List</h4>
                    <div>
                        <button data-bs-toggle="modal" data-bs-target=".add-new" class="btn btn-primary"><i class="bx bx-plus me-1"></i>Add New</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php($i=1)
                                @foreach ($users as $user)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$user->first_name}} {{$user->last_name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->role_name}}</td>
                                    <td class="text-center">
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item">
                                                <a href="{{url('/edit/user')}}/{{$user->id}}" class="px-2 text-primary" title="Edit"><i class="bx bx-pencil font-size-18"></i></a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="{{url('/delete/user')}}/{{$user->id}}" onclick="return confirm('Are you sure you want to Delete ?')" title="Delete" class="px-2 text-danger"><i class="bx bx-trash-alt font-size-18"></i></a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Add modal -->
    <div class="modal fade add-new" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered w-50">
            <div class="modal-content">
                <div class="modal-header px-4">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">Add New</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <form action="{{ route('add_user') }}" method="post" enctype="multipart/form-data" id="addUserForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">First Name</label>
                                    <input type="text" name="first_name" class="form-control" placeholder="Enter name" value="{{ old('first_name') }}" oninput=" this.value = this.value.replace(/[0-9]/g,'')" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" placeholder="Enter name" value="{{ old('last_name') }}" oninput=" this.value = this.value.replace(/[0-9]/g,'')" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email" value="{{ old('email') }}" required>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="password">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-danger me-1" data-bs-dismiss="modal"><i class="bx bx-x me-1 align-middle"></i> Cancel</button>
                                <button type="submit" class="btn btn-success"><i class="bx bx-check me-1 align-middle"></i> Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-xl-11">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Assign Role</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('save_assign_role') }}" method="post">
                        @csrf

                        <div class="mb-4">
                            <label for="user" class="form-label">User</label>
                            <select name="user" class="form-select @error('user') is-invalid @enderror w-25" id="user">
                                <option value="" selected>select user</option>
                                @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                @endforeach
                            </select>
                            @error('user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

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
                                                <input type="checkbox" class="form-check-input @error('role') is-invalid @enderror role-checkbox" name="role[]" value="{{$role->id}}">
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
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary w-md" id="assign_update_btn" disabled>Assign</button>
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
        $(document).ready(function() {
            $('#addUserForm').submit(function(event) {
                event.preventDefault();
                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('.modal').modal('hide');

                        Swal.fire({
                            title: "Success!",
                            text: "User added successfully",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 5000
                        }).then((result) => {
                            location.reload();
                        });
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            var inputField = $('[name="' + key + '"]');
                            inputField.addClass('is-invalid');
                            inputField.after('<span class="invalid-feedback" role="alert"><strong>' + value[0] + '</strong></span>');
                        });
                    }
                });
            });
        });
    </script>
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
    </script>
    <script>
        $(document).ready(function() {
            $('#user').change(function() {
                var userId = $(this).val();
                $.ajax({
                    url: '{{ route("check_assigned_role") }}',
                    type: 'GET',
                    data: {
                        user_id: userId
                    },
                    success: function(response) {
                        if (response.length > 0) {
                            $('.role-checkbox').prop('checked', false);
                            response.forEach(function(roleId) {
                                $('input[type=checkbox][value=' + roleId + ']').prop('checked', true);
                            });
                            $('#assign_update_btn').text('Update');
                            $('#assign_update_btn').prop('disabled', false);
                        } else {
                            $('.role-checkbox').prop('checked', false);
                            $('#assign_update_btn').text('Assign');
                            $('#assign_update_btn').prop('disabled', true);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // enable assign button
            const managerSelect = $('#user');
            const doctorCheckboxes = $('.role-checkbox');
            const assignBtn = $('#assign_update_btn');

            function enableAssignButton() {
                let managerSelected = managerSelect.val() !== "";
                let atLeastOneDoctorSelected = doctorCheckboxes.filter(':checked').length > 0;
                assignBtn.prop('disabled', !(managerSelected && atLeastOneDoctorSelected));
            }

            managerSelect.change(enableAssignButton);
            doctorCheckboxes.change(enableAssignButton);

            $('#role_check').change(function() {
                let isChecked = $(this).prop('checked');
                doctorCheckboxes.prop('checked', isChecked);
                enableAssignButton();
            });
        });
    </script>
    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: true
        });
    </script>
    @endif
@endsection
