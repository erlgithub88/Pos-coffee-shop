<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>AdminLTE 3 @hasSection('title') | @yield('title') @endif</title>

    @include('partials.styles')
    @stack('styles')

    <style>
        /* Background putih untuk body dan konten utama */
        body,
        .content-wrapper,
        .main-footer,
        .control-sidebar,
        .card,
        .table,
        .modal-content {
            background-color: #ffffff !important;
            color: #5C2A1D !important;
        }

        /* Navbar dan sidebar tetap beige */
        .main-sidebar,
        .main-header {
            background-color: #F5E9DA !important;
            color: #5C2A1D !important;
        }

        /* Warna teks */
        .brand-link,
        .sidebar .nav-link,
        .user-panel a,
        .sidebar .nav-icon,
        .breadcrumb-item a,
        .breadcrumb-item.active,
        .content-header h1,
        .nav-link,
        .card-title,
        .table th,
        .table td {
            color: #5C2A1D !important;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-item:hover>.nav-link {
            background-color: #EAD8C0 !important;
            color: #5C2A1D !important;
        }

        .card-header,
        .card-footer {
            background-color: #ECD8C5 !important;
            color: #5C2A1D !important;
        }

        .brand-link img {
            width: 120px !important;
            height: 120px !important;
            object-fit: cover !important;
            border-radius: 50% !important;
            opacity: 1 !important;
        }

        .brand-link .brand-text {
            display: none !important;
        }

        /* Optional: border table agar lebih soft */
        .table th,
        .table td {
            border-color: #e2d4c0 !important;
        }

        /* Preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #f5e9da;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1050;
            flex-direction: column;
        }

        .preloader img {
            height: 250px;
            width: 250px;
            object-fit: cover;
            border-radius: 50%;
            animation: shake 3s infinite ease-in-out, scalePulse 3s infinite ease-in-out;
        }

        .preloader-text {
            margin-top: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 600;
            color: #6f4e37;
            font-size: 24px;
            letter-spacing: 1.5px;
            animation: fadeInOut 4s infinite;
        }

        @keyframes shake {
            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-7px) rotate(-4deg);
            }

            50% {
                transform: translateX(7px) rotate(4deg);
            }

            75% {
                transform: translateX(-7px) rotate(-4deg);
            }
        }

        @keyframes scalePulse {
            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.07);
            }
        }

        @keyframes fadeInOut {
            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 1;
            }
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader
        <div class="preloader">
            <img src="{{ asset('storage/images/logo-coffee.png') }}" alt="CoffeeShop Logo" />
            <div class="preloader-text">Loading CoffeeShop...</div>
        </div>

        <script>
            window.addEventListener('load', () => {
                const preloader = document.querySelector('.preloader');
                if (preloader) {
                    preloader.style.transition = 'opacity 0.8s ease';
                    preloader.style.opacity = '0';
                    setTimeout(() => preloader.style.display = 'none', 800);
                }
            });
        </script> -->

        <!-- Navbar -->
        @include('partials.navbar')

        <!-- Main Sidebar Container -->
        @include('partials.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @hasSection('title')
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('title')</h1>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        @include('partials.footer')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>

    @include('partials.scripts')
    @stack('scripts')
</body>

</html>
