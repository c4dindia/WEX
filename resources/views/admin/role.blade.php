@extends('layouts.master')
@section('title')
    Role
@endsection
@section('pagecontent')
    <div class="container-fluid py-4">
      {{-- <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-icons opacity-10">weekend</i>
              </div>
              <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">Today's Money</p>
                <h4 class="mb-0">$53k</h4>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
              <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than last week</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-icons opacity-10">person</i>
              </div>
              <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">Today's Users</p>
                <h4 class="mb-0">2,300</h4>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
              <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than last month</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-icons opacity-10">person</i>
              </div>
              <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">New Clients</p>
                <h4 class="mb-0">3,462</h4>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
              <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-icons opacity-10">weekend</i>
              </div>
              <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">Sales</p>
                <h4 class="mb-0">$103,430</h4>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
              <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than yesterday</p>
            </div>
          </div>
        </div>
      </div> --}}
      {{-- <div class="row mt-4">
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card z-index-2 ">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                <div class="chart">
                  <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
            </div>
            <div class="card-body">
              <h6 class="mb-0 ">Website Views</h6>
              <p class="text-sm ">Last Campaign Performance</p>
              <hr class="dark horizontal">
              <div class="d-flex ">
                <i class="material-icons text-sm my-auto me-1">schedule</i>
                <p class="mb-0 text-sm"> campaign sent 2 days ago </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card z-index-2  ">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
                <div class="chart">
                  <canvas id="chart-line" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
            </div>
            <div class="card-body">
              <h6 class="mb-0 "> Daily Sales </h6>
              <p class="text-sm "> (<span class="font-weight-bolder">+15%</span>) increase in today sales. </p>
              <hr class="dark horizontal">
              <div class="d-flex ">
                <i class="material-icons text-sm my-auto me-1">schedule</i>
                <p class="mb-0 text-sm"> updated 4 min ago </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 mt-4 mb-3">
          <div class="card z-index-2 ">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                <div class="chart">
                  <canvas id="chart-line-tasks" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
            </div>
            <div class="card-body">
              <h6 class="mb-0 ">Completed Tasks</h6>
              <p class="text-sm ">Last Campaign Performance</p>
              <hr class="dark horizontal">
              <div class="d-flex ">
                <i class="material-icons text-sm my-auto me-1">schedule</i>
                <p class="mb-0 text-sm">just updated</p>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
      <div class="row mb-4">
        <div class="col-lg-11 col-md-6 mb-md-0 mb-4">
          <div class="card">

            <div class="card-body px-0 pb-2">
              {{--  --}}
              <div class="row justify-content-center">
                <div class="col-lg-11">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Role List</h4>
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
                                            <th scope="col">Status</th>
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php($i=1)
                                        @foreach ($roles as $role)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$role->name}}</td>
                                            <td>{{$role->status}}</td>
                                            <td class="text-center">
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item">
                                                        <a href="{{url('/edit/role')}}/{{$role->id}}" class="px-2 text-primary" title="Edit"><i class="bx bx-pencil font-size-18"></i></a>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <a href="{{url('/delete/role')}}/{{$role->id}}" onclick="return confirm('Are you sure you want to Delete ?')" class="px-2 text-danger" title="Delete"><i class="bx bx-trash-alt font-size-18"></i></a>
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


              <div class="modal fade add-new" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered w-25">
                    <div class="modal-content">
                        <div class="modal-header px-4">
                            <h5 class="modal-title" id="myExtraLargeModalLabel">Add New</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4">
                            <form action="{{ route('add_role') }}" method="post" enctype="multipart/form-data" id="addRoleForm">
                                @csrf
                                <div class="row flex-column mb-3">
                                    <div>
                                        <div class="mb-4">
                                            <label class="form-label" for="name">Name</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter name" value="{{ old('name') }}" oninput=" this.value = this.value.replace(/[0-9]/g,'')" required>
                                            @error('name')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label" for="name">Status</label>
                                        <div class="d-flex">
                                            <div class="form-check" style="margin-right: 20px;">
                                                <input class="form-check-input @error('status') is-invalid @enderror" type="radio" name="status" value="Active" required>
                                                <label class="form-label" for="status">
                                                    Active
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input @error('status') is-invalid @enderror" type="radio" name="status" value="Inactive" required>
                                                <label class="form-label" for="status">
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
                            <h4 class="card-title mb-0">Assign Module</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('save_assign_module') }}" method="post">
                                @csrf

                                <div class="mb-4">
                                    <label for="role" class="form-label">Role</label>
                                    <select name="role" class="form-select @error('role') is-invalid @enderror w-25" id="role">
                                        <option value="" selected>select role</option>
                                        @foreach ($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="table-responsive mb-3">
                                    <label class="form-label">Module List</label>
                                    <table class="table table-nowrap align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="ps-4" style="width: 50px;">
                                                    <div class="form-check font-size-16">
                                                        <input type="checkbox" class="form-check-input" id="module_check">
                                                        <label class="form-check-label" for="doctor_check"></label>
                                                    </div>
                                                </th>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php($i=1)
                                            @foreach ($modules as $module)
                                            <tr>
                                                <th scope="row" class="ps-4">
                                                    <div class="form-check font-size-16">
                                                        <input type="checkbox" class="form-check-input @error('module') is-invalid @enderror module-checkbox" name="module[]" value="{{$module->id}}">
                                                        <label class="form-check-label" for="module"></label>
                                                    </div>
                                                </th>
                                                <td>{{$i++}}</td>
                                                <td>{{$module->name}}</td>
                                                <td>
                                                    @if($module->is_admin == 1)
                                                    Active
                                                    @else
                                                    Inactive
                                                    @endif
                                                </td>
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
              {{--  --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
@section('scripts')
  <!--   Core JS Files   -->
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>

  <script src="{{ asset('assets/js/material-dashboard.min.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const mainCheckbox = document.getElementById('module_check');
            const moduleCheckboxes = document.querySelectorAll('.module-checkbox');

            mainCheckbox.addEventListener('change', function() {
                const isChecked = mainCheckbox.checked;
                moduleCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = isChecked;
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#role').change(function() {
                var roleId = $(this).val();
                $.ajax({
                    url: '{{ route("check_assigned_module") }}',
                    type: 'GET',
                    data: {
                        role_id: roleId
                    },
                    success: function(response) {
                        if (response.length > 0) {
                            $('.module-checkbox').prop('checked', false);
                            response.forEach(function(moduleId) {
                                $('input[type=checkbox][value=' + moduleId + ']').prop('checked', true);
                            });
                            $('#assign_update_btn').text('Update');
                            $('#assign_update_btn').prop('disabled', false);
                        } else {
                            $('.module-checkbox').prop('checked', false);
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
            const managerSelect = $('#role');
            const doctorCheckboxes = $('.module-checkbox');
            const assignBtn = $('#assign_update_btn');

            function enableAssignButton() {
                let managerSelected = managerSelect.val() !== "";
                let atLeastOneDoctorSelected = doctorCheckboxes.filter(':checked').length > 0;
                assignBtn.prop('disabled', !(managerSelected && atLeastOneDoctorSelected));
            }

            managerSelect.change(enableAssignButton);
            doctorCheckboxes.change(enableAssignButton);

            $('#module_check').change(function() {
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

