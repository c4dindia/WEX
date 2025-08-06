<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>C4D Payment | @yield('title')</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('ClientCss/style.css') }}">
    <!-- font awesome  -->
    <script src="https://kit.fontawesome.com/6036d46694.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('ClientCss/images/c4d.png') }}">

    @yield('css')

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap CSS -->
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}

    <!--SweetAlert2-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Search Bar Css-->
    <style>
        .suggestions-list {
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            list-style: none;
            padding: 0;
            margin: 0;
            width: 600px;
            max-height: 170px;
            overflow-y: auto;
            z-index: 10001;
            box-shadow: var(--light-box-shadow);
        }

        /* Hide scrollbar for WebKit browsers */
        .suggestions-list::-webkit-scrollbar {
            width: 0;
            /* Hide vertical scrollbar */
            background: transparent;
            /* Optional: make the background transparent */
        }

        /* Hide scrollbar for Firefox */
        .suggestions-list {
            scrollbar-width: thin;
            /* Hide scrollbar for Firefox */
            scrollbar-color: transparent transparent;
            /* Set scrollbar color to transparent */
        }

        .suggestions-list li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            /* Separator between items */
        }

        .suggestions-list li:last-child {
            border-bottom: none;
            /* Remove border for the last item */
        }

        .suggestions-list a {
            display: block;
            /* Make the entire list item clickable */
            color: inherit;
        }

        .suggestions-list li:hover {
            background: var(--main-body-bg-color);
        }
    </style>

</head>

<body>
    <header class="top-header">
        <div class="container-fluide d-flex align-items-center justify-content-md-between gap-5">
            <div class="col-6 col-md-3 col-lg-2 menu-icon d-flex gap-2 pointer das-logo">
                <!-- Hamburger Icon for Mobile -->
                <!-- <i class="fa-solid fa-bars" id="sidebar-toggle"></i> -->
                <div class="header-logo">
                    <img src="{{ asset('ClientCss/images/c4d.png') }}" alt="Logo">
                </div>
            </div>

            <div class="d-sm-none d-md-none d-lg-block col-md-5 col-lg-7 col-xl-8 justify-content-xl-start hide-for-md" style="margin-top:10px;">
                <form action="" class="search-bar-style" id="search-form">
                    @csrf
                    <div class=" d-flex align-items-center position-relative">
                        <span class="px-2" style="position: absolute; left: 5px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            <i class="fa-solid fa-magnifying-glass" style="color:#A1A1A1;"></i>
                        </span>
                        <input class="search-bar px-5" id="search-bar" type="text" placeholder="Enter Card Number or Card Name" autocomplete="off">
                    </div>
                    <ul id="suggestions" class="suggestions-list" style="display: none;"></ul>
                </form>
            </div>

            <div class="col-5 col-md-3 col-lg-2 col-xl-2 d-flex gap-3 align-items-center justify-content-xl-start" style="margin-top:10px;">
                {{-- <div>
                    <i class="fa-regular fa-circle-question"></i>
                </div> --}}
                <div class="d-flex align-items-center justify-content-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rite-wc mb-0 pointer" style="border: none;background-color: black;color: white;padding: 10px 15px; "
                            type="submit"><i class="fa-solid fa-right-from-bracket"></i>&nbsp;Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Sidebar Wrapper with Top Sidebar -->
        <div id="sidebar-wrapper">
            <div class="top-sidebar show">
                <div class="row d-flex gap-3">
                    <div class="col-md-1 ">
                        <div class="company-name">
                            @php
                            $name = \App\Models\User::where('id',Auth::user()->id)->first()['first_name'] . ' ' . \App\Models\User::where('id',Auth::user()->id)->first()['last_name'];
                            $fLetter = substr($name,0,1);
                            @endphp
                            {{ $fLetter }}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-10 d-flex align-items-center">
                        <label class="c4d-headin-txt">{{ $name }}</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-lg-12 mt-3">
                        <p class="mb-2">Available Amount</p>

                        <p class="amount-text">$0 </p>
                        <div class="top-up  pointer">
                            <a href="{{ route('showClientsTopUpHistoryPage') }}"
                                class="d-flex align-items-center gap-4 text-white">
                                <p class="mb-0 topsidebar-top-up"><i class="fa-solid fa-plus"></i>&nbsp; &nbsp;Top Up
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav id="sidebar" class="mt-1">
                <div class="p-1">
                    @if(auth()->user()->is_admin == "2")
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ $activePage == 'top up' ? 'active-sidebar' : '' }}" href="{{ route('showClientsTopUpHistoryPage') }}">
                                <i class="fas fa-history"></i>&nbsp;&nbsp;Top-up History
                            </a>
                        </li>
                        <li class="nav-item mt-1">
                            <a class="nav-link {{ $activePage == 'create a card' ? 'active-sidebar' : '' }}" href="{{ route('showClientCreateACard') }}">
                                <i class="fas fa-credit-card"></i>&nbsp;&nbsp;Create a card
                            </a>
                        </li>
                        <hr>
                        <li class="nav-item ">
                            <a class="nav-link {{ $activePage == 'dashboard' ? 'active-sidebar' : '' }}" href="{{ route('showClientDashboard') }}"><i class="fas fa-tachometer-alt"></i>
                                &nbsp;&nbsp;Dashboard</a>
                        </li>
                        <li class="nav-item my-1">
                            <a class="nav-link {{ $activePage == 'account' ? 'active-sidebar' : '' }}" href="{{ route('showClientsAccountsPage') }}"><i class="fas fa-users"></i>
                                &nbsp;&nbsp;Accounts</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link {{ $activePage == 'expense card' ? 'active-sidebar' : '' }}" href="{{ route('showCard') }}"><i class="fas fa-credit-card"></i>
                                &nbsp;&nbsp;Expense card</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activePage == 'statements' ? 'active-sidebar' : '' }}" href="{{ route('showClientStatements') }}"><i class="fas fa-file-alt"></i>
                                &nbsp;&nbsp;Statement</a>
                        </li>
                    </ul>
                    @endif
                </div>
            </nav>
        </div>

        <!-- Main Content Area -->

        <main class=" " style="height: 850px;">
            @yield('pagecontent')

        </main>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @yield('scripts')

    <!-- Custom JS -->
    {{-- <script src="{{ asset('ClientCss/javaScript.js') }}"></script> --}}
    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                @php
                session()->forget('success');
                @endphp
                location.reload(); // Reload the page
            }
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: true
        }).then((result) => {
            // Flush the session message
            @php
            session()->forget('error');
            @endphp
        });
    </script>
    @endif

    <!--Search Bar JS-->
    <script>
        $(document).ready(function() {
            $('#search-bar').on('keyup', function() {
                const query = $(this).val();

                if (query.length > 2) {
                    $.ajax({
                        url: '{{ route('searchBar') }}',
                        method: 'GET',
                        data: {
                            q: query
                        },
                        success: function(data) {
                            const suggestions = $('#suggestions');
                            suggestions.empty();

                            if (data.length) {
                                suggestions.show();
                                data.forEach(function(item) {
                                    // suggestions.append(`<li><a href="{{ url('/cards') }}/${item.card_id}">${item.card_name} ${item.masked_number} ${item.type} ${item.status}</li>`);
                                    suggestions.append(`
                                          <li>
                                             <a href="{{ url('/card') }}/${item.card_id}" style="text-decoration: none; color: inherit;">
                                                 <div style="display: flex; justify-content: space-between; align-items: center;">
                                                     <div>
                                                         <strong>${item.card_name}</strong>
                                                         <div style="color: gray;">${item.masked_card_number}</div>
                                                     </div>
                                                     <div>
                                                         <strong style=" padding: 4px 8px; border-radius: 20px; color: ${item.card_type === 'VIRTUAL' ? 'black' : '#721c24'};">
                                                              ${item.card_type.charAt(0).toUpperCase() + item.card_type.slice(1).toLowerCase()} Card
                                                         </strong>
                                                         <strong style="margin-left: 8px; background-color: ${item.card_status === 'ACTIVE' ? '#d1e7dd' : '#f8d7da'}; padding: 4px 8px; border-radius: 20px; color: ${item.status === 'Active' ? '#0f5132' : '#721c24'};">
                                                             ${item.card_status.charAt(0).toUpperCase() + item.card_status.slice(1).toLowerCase()}
                                                         </strong>
                                                     </div>
                                                 </div>
                                             </a>
                                         </li>
                                     `);
                                });
                            } else {
                                suggestions.hide();
                            }
                        },
                        error: function() {
                            console.error('Error fetching suggestions');
                        }
                    });
                } else {
                    $('#suggestions').hide();
                }
            });

            // Prevent form submission
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
            });

            // Hide suggestions when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#search-form').length) {
                    $('#suggestions').hide();
                }
            });

            // Focus on the search bar when "/" is pressed
            $(document).on('keydown', function(event) {
                if (event.key === '/') {
                    event.preventDefault(); // Prevent default action (e.g., scrolling to the address bar)
                    $('#search-bar').focus(); // Focus the search bar input
                }
            });
        });
    </script>
</body>

</html>