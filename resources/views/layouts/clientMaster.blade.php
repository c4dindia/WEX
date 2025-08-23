<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trinity Payment - @yield('title')</title>

    <!-- App favicon -->
    <link rel="icon" href="{{ asset('newUI/images/favicon.png') }}">

    <link href="{{ asset('newUI/css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('newUI/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('newUI/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('newUI/css/main.css') }}" rel="stylesheet">

    @yield('css')

    <!-- Bootstrap CSS -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

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
    

    <label for="menu-toggle" class="toggle-btn">☰ Menu</label>
    <input type="checkbox" id="menu-toggle">

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Close Button (only visible on mobile) -->
        <label for="menu-toggle" class="close-btn">&times;</label>

        <img src="{{ asset('newUI/images/logo.png') }}" alt="Logo" class="logo">
        <nav>
            <a class="nav-link {{ $activePage == 'top up' ? 'active' : '' }}" href="{{ route('showClientsTopUpHistoryPage') }}"><i class="bi bi-clock"></i> Top-up History</a>
            <a class="nav-link {{ $activePage == 'create a card' ? 'active' : '' }}" href="{{ route('showClientCreateACard') }}"><i class="bi bi-credit-card"></i> Create a Card</a>
            <hr>
            <a class="nav-link {{ $activePage == 'dashboard' ? 'active' : '' }}" href="{{ route('showClientDashboard') }}"><i class="bi bi-grid"></i> Dashboard</a>
            <!-- <a class="nav-link {{ $activePage == 'account' ? 'active' : '' }}" href="{{ route('showClientsAccountsPage') }}"><i class="fa-regular fa-circle-user"></i> Accounts</a> -->
            <a class="nav-link {{ $activePage == 'expense card' ? 'active' : '' }}" href="{{ route('bin') }}"><i class="bi bi-credit-card-2-back"></i> Expense card</a>
            <a class="nav-link {{ $activePage == 'statements' ? 'active' : '' }}" href="{{ route('showClientStatements') }}"><i class="bi bi-arrow-left-right"></i> Transactions</a>

        </nav>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="top-header">
            <div class="input-group w-25">
                <form action="" class="w-100" id="search-form">
                    @csrf
                    
                    <input class="search-bar px-5" id="search-bar" type="text" placeholder="Enter Card Number or Card Name" autocomplete="off">   
                    <ul id="suggestions" class="suggestions-list" style="display: none;"></ul>
                </form>
            </div>
            

            <div class="d-flex align-items-center gap-4 flex-wrap">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="icon-btn btn-primary" title="Logout" type="submit" data-bs-toggle="modal"
                        data-bs-target="#logout"><i class="fa-solid fa-right-from-bracket me-2"></i>
                        Logout 
                    </button>
                </form>
            </div>

        </div>
        @yield('pagecontent')
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
            showConfirmButton: true,
            confirmButtonColor: '#7e1718'
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
            showConfirmButton: true,
            confirmButtonColor: '#7e1718'
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
                            console.log(data);
                            const suggestions = $('#suggestions');
                            suggestions.empty();

                            if (data.length) {
                                suggestions.show();
                                data.forEach(function(item) {
                                    // suggestions.append(`<li><a href="{{ url('/cards') }}/${item.card_id}">${item.card_name} ${item.masked_number} ${item.type} ${item.status}</li>`);
                                    suggestions.append(`
                                          <li>
                                             <a href="{{ url('/cards') }}/${item.id}" style="text-decoration: none; color: inherit;">
                                                 <div style="display: flex; justify-content: space-between; align-items: center;">
                                                     <div>
                                                         <strong>${item.cardholder_name}</strong>
                                                         <div style="color: gray;">${item.mask_card_number}</div>
                                                     </div>
                                                     <div>
                                                         <strong style=" padding: 4px 8px; border-radius: 20px; color: ${item.card_type === 'VIRTUAL' ? 'black' : '#721c24'};">
                                                         ${item.card_type} Card
                                                         </strong>
                                                         <strong style="margin-left: 8px; background-color: ${item.status == '1' ? '#d1e7dd' : '#f8d7da'}; padding: 4px 8px; border-radius: 20px; color: ${item.status == '1' ? '#0f5132' : '#721c24'};">
                                                            ${item.status == '1' ? 'Active' : 'Closed'}
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