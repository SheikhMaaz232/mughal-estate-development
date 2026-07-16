<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ur' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--
        Available classes for <html> element:

        'dark'                  Enable dark mode - Default dark mode preference can be set in app.js file (always saved and retrieved in localStorage afterwards):
                                window.One = new App({ darkMode: "system" }); // "on" or "off" or "system"
        'dark-custom-defined'   Dark mode is always set based on the preference in app.js file (no localStorage is used)
    -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Mughal Estate Developers</title>

    <meta name="description" content="Mughal Estate Developers">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="index, follow">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">

    <!-- Modules -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-keyboard@latest/build/css/index.css" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&display=swap" rel="stylesheet">

    <style>
        #keyboard {
            display: none;
            z-index: 1000;
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .simple-keyboard .hg-button.hg-red {
            background: rgba(255, 0, 0, 0.1);
            color: #d14545;
        }

        .simple-keyboard .hg-button.hg-highlight {
            background: #ff9800;
            color: white;
        }

        .input-urdu {
            font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', serif;
            font-size: 16px;
            direction: rtl;
            text-align: right;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table th {
            font-weight: 500;
            color: #6c757d;
        }

        .text-muted {
            color: #6c757d !important;
        }

        /* RTL Layout Fixes */
        [dir="rtl"] {
            direction: rtl;
            text-align: right;
        }

        [dir="rtl"] body {
            font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', sans-serif;
        }

        /* Fix for sidebar positioning */
        [dir="rtl"] #sidebar {
            right: 0;
            left: auto;
            transform: translateX(100%);
        }

        [dir="rtl"] #sidebar.sidebar-o {
            transform: translateX(0);
        }

        /* Main content adjustments */
        {{--  [dir="rtl"] #main-container {
        margin-right: 250px;
        margin-left: 0;
    }  --}}

        /* Form elements RTL support */
        [dir="rtl"] .form-control,
        [dir="rtl"] .form-select,
        [dir="rtl"] .input-group-text {
            text-align: right;
            direction: rtl;
        }

        /* Dropdown menu alignment */
        [dir="rtl"] .dropdown-menu {
            text-align: right;
            right: 0;
            left: auto;
        }

        /* Navbar items alignment */
        [dir="rtl"] .nav-main-link {
            padding-right: 1rem;
            padding-left: 0.5rem;
        }

        /* Table cell alignment */
        {{--  [dir="rtl"] table td,
    [dir="rtl"] table th {
        text-align: right;
    }  --}}

        /* Fix for language switcher spacing */
        .language-switcher {
            margin-left: 15px;
            margin-right: 15px;
            padding: 0 10px;
        }

        /* Profile dropdown spacing */
        #page-header-user-dropdown {
            margin-left: 10px;
        }

        /* Keyboard UI improvements */
        .simple-keyboard.urdu-layout {
            font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', sans-serif;
            direction: rtl;
        }

        .simple-keyboard.urdu-layout .hg-button {
            font-size: 16px;
            {{--  padding: 10px 5px;  --}}
        }

        [dir="rtl"] ul,
        [dir="rtl"] ol {
            padding-right: 1.5rem;
            padding-left: 0;
            width: 100%;
        }

        [dir="rtl"] li {
            text-align: right;
            margin-right: 0;
            margin-left: 1rem;
            width: 100%;
            padding: 0.25rem 0;
        }

        /* Fix for nested lists */
        [dir="rtl"] ul ul,
        [dir="rtl"] ol ol {
            padding-right: 1rem;
            margin-right: 1rem;
        }

        /* Specific fix for the list in your image */
        [dir="rtl"] .list-group {
            direction: rtl;
            text-align: right;
        }

        [dir="rtl"] .list-group-item {
            padding: 0.75rem 1.25rem 0.75rem 0.75rem;
            border-right: 1px solid rgba(0, 0, 0, 0.125);
            border-left: none;
        }

        /* Button fixes for RTL */
        [dir="rtl"] .btn {
            /* Size control */
            {{--  min-width: 80px; /* Set minimum width that fits both languages */  --}} padding: 8px 16px;
            /* Consistent padding */
            height: 38px;
            /* Fixed height matching English buttons */

            /* Text alignment */
            display: inline-flex;
            align-items: center;
            justify-content: center;

            /* Font control */
            font-size: 14px;
            /* Match English font size */
            line-height: 1.42857;
            /* Standard line height */
            letter-spacing: normal;

            /* Urdu-specific adjustments */
            font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', sans-serif;
            white-space: nowrap;
            /* Prevent text wrapping */
        }

        /* Specific button types */
        [dir="rtl"] .btn-sm {
            padding: 5px 10px;
            height: 32px;
            font-size: 12px;
        }

        [dir="rtl"] .btn i {
            margin-right: 0;
            margin-left: 0.25rem;
        }

        [dir="rtl"] .btn-group>.btn:not(:first-child),
        [dir="rtl"] .btn-group>.btn-group:not(:first-child)>.btn {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        [dir="rtl"] .btn-group>.btn:not(:last-child):not(.dropdown-toggle),
        [dir="rtl"] .btn-group>.btn-group:not(:last-child)>.btn {
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        /* Specific fix for dropdown buttons */
        [dir="rtl"] .dropdown-toggle::after {
            margin-right: 0.255em;
            margin-left: 0;
        }

        /* Fix for button groups */
        [dir="rtl"] .btn-group {
            direction: rtl;
        }

        [dir="rtl"] .btn-group>.btn+.btn,
        [dir="rtl"] .btn-group>.btn+.btn-group,
        [dir="rtl"] .btn-group>.btn-group+.btn,
        [dir="rtl"] .btn-group>.btn-group+.btn-group {
            margin-right: -1px;
            margin-left: 0;
        }

        /* Container fixes */
        [dir="rtl"] .container,
        [dir="rtl"] .container-fluid {
            padding-right: 15px;
            padding-left: 15px;
        }

        /* Card fixes */
        [dir="rtl"] .card {
            text-align: right;
            direction: rtl;
        }

        [dir="rtl"] .card-header {
            padding: 0.75rem 1.25rem 0.75rem 0.75rem;
        }

        /* Form control fixes */
        [dir="rtl"] .form-control {
            padding: 0.375rem 0.75rem 0.375rem 1.75rem;
        }

        [dir="rtl"] .input-group>.form-control,
        [dir="rtl"] .input-group>.form-select {
            border-radius: 0 0.25rem 0.25rem 0;
        }

        [dir="rtl"] .input-group-text {
            border-radius: 0.25rem 0 0 0.25rem;
        }

        /* Label spacing fixes */
        [dir="rtl"] label {
            display: block;
            margin-bottom: 10px;
            /* Space below label */
            margin-top: 10px;
            /* Space above label */
            width: 100%;
            text-align: right;
            font-weight: 500;
        }

        /* Specific fix for Urdu name label */
        #name_ur_label {
            margin-right: 10px;
            /* Extra right margin for Urdu labels */
        }

        /* Form group spacing */
        [dir="rtl"] .form-group {
            margin-bottom: 20px;
            /* Increased space between form elements */
        }

        /* Input field spacing */
        [dir="rtl"] .form-control {
            margin-top: 5px;
            /* Space between label and input */
            margin-bottom: 15px;
            /* Space after input */
            padding: 10px 15px;
            /* Better padding inside inputs */
        }

        /* Button text balancing */
        [dir="rtl"] .btn {
            padding: 10px 20px;
            /* More balanced padding */
            {{--  min-width: 100px;   /* Minimum width for buttons */  --}} text-align: center;
            letter-spacing: normal;
            /* Fix for Urdu text spacing */
        }

        /* Specific fix for Cancel/Save buttons */
        [dir="rtl"] .btn-secondary,
        [dir="rtl"] .btn-primary {
            margin-left: 10px;
            /* Space between buttons */
            margin-right: 10px;
            padding: 10px 25px;
            /* More balanced padding */
        }

        /* Button container fix */
        [dir="rtl"] .button-group {
            display: flex;
            justify-content: flex-end;
            /* Align buttons to right */
            gap: 15px;
            /* Space between buttons */
            margin-top: 20px;
            /* Space above button group */
        }

        /* Urdu font optimization */
        [dir="rtl"] .btn,
        [dir="rtl"] label {
            font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', sans-serif;
            font-size: 16px;
            line-height: 1.5;
            /* Better line spacing */
        }

        [dir="rtl"] .btn-sm {
            padding: 5px 10px;
            height: 32px;
            font-size: 12px;
        }

        [dir="rtl"] .btn-lg {
            padding: 10px 20px;
            height: 46px;
            font-size: 16px;
        }

        /* Button group consistency */
        [dir="rtl"] .btn-group .btn {
            margin-right: -1px;
            /* Fix button group borders */
        }

        /* Ensure equal width for common action buttons */
        [dir="rtl"] .btn-cancel,
        [dir="rtl"] .btn-save {
            width: 90px;
            /* Fixed width for common actions */
        }

        /* For form submit/cancel button pairs */
        [dir="rtl"] .form-actions .btn {
            min-width: 90px;
            width: auto;
        }

        .img-thumbnail {
            object-fit: cover;
            /* Ensures the image fills the container */
            width: 200px;
            height: 200px;
            border-radius: 4px;
            background-color: #f8f9fa;
            /* Light gray if image fails */
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/keyboard.css') }}">

    <link rel="stylesheet" href="{{ asset('js/plugins/dropzone/min/dropzone.min.css') }}">
    @yield('css')
    @vite(['resources/sass/main.scss', 'resources/js/oneui/app.js'])

    <!-- Alternatively, you can also include a specific color theme after the main stylesheet to alter the default color theme of the template -->
    {{-- @vite(['resources/sass/main.scss', 'resources/sass/oneui/themes/amethyst.scss', 'resources/js/oneui/app.js']) --}}

    <!-- Load and set dark mode preference (blocking script to prevent flashing) -->
    <script src="{{ asset('js/setTheme.js') }}"></script>
    <script src="{{ asset('js/keyboard.js') }}"></script>
    <script src="{{ asset('js/plugins/dropzone/min/dropzone.min.js') }}"></script>


    {{--  <script src="https://unpkg.com/simple-keyboard/build/index.js"></script>  --}}
    {{--  <script src="https://cdn.jsdelivr.net/npm/simple-keyboard@latest/build/index.js"></script>  --}}
    @yield('js')
</head>

<body>
    <!-- Page Container -->
    <!--
        Available classes for #page-container:

        SIDEBAR and SIDE OVERLAY

        'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
        'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
        'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
        'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
        'sidebar-dark'                              Dark themed sidebar

        'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
        'side-overlay-o'                            Visible Side Overlay by default

        'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

        'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

        HEADER

        ''                                          Static Header if no class is added
        'page-header-fixed'                         Fixed Header

        HEADER STYLE

        ''                                          Light themed Header
        'page-header-dark'                          Dark themed Header

        MAIN CONTENT LAYOUT

        ''                                          Full width Main Content if no class is added
        'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
        'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)
    -->
    <div id="page-container"
        class="sidebar-o enable-page-overlay sidebar-dark side-scroll page-header-fixed main-content-narrow">
        <!-- Side Overlay-->
        <aside id="side-overlay" class="fs-sm">
            <!-- Side Header -->
            <div class="content-header border-bottom">
                <!-- User Avatar -->
                <a class="img-link me-1" href="javascript:void(0)">
                    <img class="img-avatar img-avatar32" src="{{ asset('media/avatars/avatar10.jpg') }}"
                        alt="">
                </a>
                <!-- END User Avatar -->

                <!-- User Info -->
                <div class="ms-2">
                    <a class="text-dark fw-semibold fs-sm" href="javascript:void(0)">John Smith</a>
                </div>
                <!-- END User Info -->

                <!-- Close Side Overlay -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <a class="ms-auto btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="layout"
                    data-action="side_overlay_close">
                    <i class="fa fa-fw fa-times"></i>
                </a>
                <!-- END Close Side Overlay -->
            </div>

            <!-- END Side Header -->

            <!-- Side Content -->
            <div class="content-side">
                <p>
                    Content..
                </p>
            </div>
            <!-- END Side Content -->
        </aside>
        <!-- END Side Overlay -->

        <!-- Sidebar -->
        <!--
            Sidebar Mini Mode - Display Helper classes

            Adding 'smini-hide' class to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
            Adding 'smini-show' class to an element will make it visible (opacity: 1) when the sidebar is in mini mode
                If you would like to disable the transition animation, make sure to also add the 'no-transition' class to your element

            Adding 'smini-hidden' to an element will hide it when the sidebar is in mini mode
            Adding 'smini-visible' to an element will show it (display: inline-block) only when the sidebar is in mini mode
            Adding 'smini-visible-block' to an element will show it (display: block) only when the sidebar is in mini mode
        -->

        @if (isset($selectedCompany))
            <nav id="sidebar" aria-label="Main Navigation">
                <!-- Side Header -->
                <div class="content-header">
                    <!-- Logo -->
                    <a class="font-semibold text-dual" href="/">
                        <span class="smini-visible">
                            <i class="fa fa-circle-notch text-primary"></i>
                        </span>
                        <span class="smini-hide fs-5 tracking-wider">@lang('menu.payroll')</span>
                    </a>
                    <!-- END Logo -->

                    <!-- Dark Mode Toggle -->
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-alt-secondary" id="sidebar-dark-mode-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-fw fa-moon" data-dark-mode-icon></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end smini-hide border-0"
                            aria-labelledby="sidebar-dark-mode-dropdown">
                            <button type="button" class="dropdown-item d-flex align-items-center gap-2"
                                data-toggle="layout" data-action="dark_mode_off" data-dark-mode="off">
                                <i class="far fa-sun fa-fw opacity-50"></i>
                                <span class="fs-sm fw-medium">Light</span>
                            </button>
                            <button type="button" class="dropdown-item d-flex align-items-center gap-2"
                                data-toggle="layout" data-action="dark_mode_on" data-dark-mode="on">
                                <i class="far fa-moon fa-fw opacity-50"></i>
                                <span class="fs-sm fw-medium">Dark</span>
                            </button>
                        </div>
                    </div>
                    <!-- END Dark Mode Toggle -->
                </div>
                <!-- END Side Header -->

                <!-- Side Navigation -->
                <div class="content-side">
                    <ul class="nav-main">

                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('dashboard') ? ' active' : '' }}"
                                href="/dashboard">
                                <i class="nav-main-link-icon si si-cursor"></i>
                                <span class="nav-main-link-name">@lang('menu.dashboard')</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('payroll/dashboard') ? ' active' : '' }}"
                                href="{{ route('payroll.dashboard') }}">
                                <i class="nav-main-link-icon si si-bar-chart"></i>
                                <span class="nav-main-link-name">@lang('payroll::menu.dashboard')</span>
                            </a>
                        </li>

                        <li class="nav-main-item{{ request()->is('payroll/setups*') ? ' open' : '' }}">
                            <a class="nav-main-link nav-main-link-submenu" data-toggle="layout"
                                data-action="submenu_toggle" href="javascript:void(0)">
                                <i class="nav-main-link-icon si si-users"></i>
                                <span class="nav-main-link-name">@lang('menu.registration')</span>
                                <span class="nav-main-link-angle"></span>
                            </a>
                            <ul
                                class="nav-main-submenu"{{ request()->is('payroll/setups*') ? ' style="display: block;"' : '' }}>
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('payroll/setups/qualifications') ? ' active' : '' }}"
                                        href="{{ route('payroll.qualifications') }}">
                                        <span class="nav-main-link-name">@lang('payroll::menu.qualifications')</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('payroll/setups/grades') ? ' active' : '' }}"
                                        href="{{ route('payroll.grades.index') }}">
                                        <span class="nav-main-link-name">@lang('payroll::menu.grades')</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('payroll/setups/payroll-types') ? ' active' : '' }}"
                                        href="{{ route('payroll.payroll-types.index') }}">
                                        <span class="nav-main-link-name">@lang('payroll::menu.payroll-types')</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('payroll/setups/designations') ? ' active' : '' }}"
                                        href="{{ route('payroll.designations.index') }}">
                                        <span class="nav-main-link-name">@lang('payroll::menu.designations')</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('payroll/setups/devices') ? ' active' : '' }}"
                                        href="{{ route('payroll.devices.index') }}">
                                        <span class="nav-main-link-name">@lang('payroll::menu.devices')</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('payroll/leave-types') ? ' active' : '' }}"
                                        href="{{ route('payroll.leave-types.index') }}">
                                        <span class="nav-main-link-name">@lang('payroll::menu.leave-types')</span>
                                    </a>
                                </li>

                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('payroll/holiday-types') ? ' active' : '' }}"
                                        href="{{ route('payroll.holiday-types.index') }}">
                                        <span class="nav-main-link-name">@lang('payroll::menu.holiday-types')</span>
                                    </a>
                                </li>

                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('payroll/setups/shifts') ? ' active' : '' }}"
                                        href="{{ route('payroll.shifts.index') }}">
                                        <span class="nav-main-link-name">@lang('payroll::menu.shifts')</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('payroll/employees') ? ' active' : '' }}"
                                        href="{{ route('payroll.employees.index') }}">
                                        <span class="nav-main-link-name">@lang('payroll::menu.employees')</span>
                                    </a>
                            </ul>
                        </li>

                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('payroll/leave-requests') ? ' active' : '' }}"
                                href="{{ route('payroll.leave-requests.index') }}">
                                <i class="nav-main-link-icon si si-calendar"></i>
                                <span class="nav-main-link-name">@lang('payroll::menu.leave-requests')</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('payroll/holidays') ? ' active' : '' }}"
                                href="{{ route('payroll.holidays.index') }}">
                                <i class="nav-main-link-icon si si-present"></i>
                                <span class="nav-main-link-name">@lang('payroll::menu.holidays')</span>
                            </a>
                        </li>


                        {{-- <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('payroll/deductions') ? ' active' : '' }}"
                                href="{{ route('payroll.deductions.index') }}">
                                <span class="nav-main-link-name">@lang('payroll::menu.deductions')</span>
                            </a>
                        </li> --}}
                        </li>
                        {{-- <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('payroll/allowances') ? ' active' : '' }}"
                                href="{{ route('payroll.allowances.index') }}">
                                <span class="nav-main-link-name">@lang('payroll::menu.allowances')</span>
                            </a>
                        </li> --}}
                        </li>


                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('payroll/attendance') ? ' active' : '' }}"
                                href="{{ route('payroll.attendance.index') }}">
                                <i class="nav-main-link-icon si si-clock"></i>
                                <span class="nav-main-link-name">@lang('payroll::menu.attendance')</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('payroll/payrolls') ? ' active' : '' }}"
                                href="{{ route('payroll.payrolls.index') }}">
                                <i class="nav-main-link-icon si si-wallet"></i>
                                <span class="nav-main-link-name">@lang('payroll::menu.payrolls')</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- END Side Navigation -->
            </nav>
            <!-- END Sidebar -->
        @endif
        <!-- Header -->
        <header id="page-header">
            <!-- Header Content -->
            <div class="content-header">
                <!-- Left Section -->
                <div class="d-flex align-items-center">
                    <!-- Toggle Sidebar -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                    <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none" data-toggle="layout"
                        data-action="sidebar_toggle">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                    <!-- END Toggle Sidebar -->

                    <!-- Open Search Section (visible on smaller screens) -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <button type="button" class="btn btn-sm btn-alt-secondary d-md-none" data-toggle="layout"
                        data-action="header_search_on">
                        <i class="fa fa-fw fa-search"></i>
                    </button>
                    <!-- END Open Search Section -->

                    <!-- Search Form (visible on larger screens) -->
                    <form class="d-none d-md-inline-block" action="/dashboard" method="POST">
                        @csrf

                        <div class="d-flex align-items-center px-2 text-dark fw-bold">
                            @if (isset($selectedCompany))
                                <img src="{{ $selectedCompany->logo ? asset('storage/' . $selectedCompany->logo) : asset('media/avatars/avatar-default.jpg') }}"
                                    alt="Logo" style="height: 30px; width: auto; margin-right: 10px;" />

                                <span>{{ $selectedCompany->{'name_' . app()->getLocale()} }}</span>
                            @else
                                <i class="fa fa-building me-2 text-primary"></i>
                            @endif
                        </div>

                    </form>
                    <!-- END Search Form -->
                </div>
                <!-- END Left Section -->
                {{--  @php
                dd(Auth::user()->avatar);
            @endphp  --}}
                <!-- Right Section -->
                <div class="d-flex align-items-center">
                    <!-- User Dropdown -->
                    <div class="dropdown d-inline-block ms-2">
                        <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center"
                            id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <img class="rounded-circle" src="{{ asset('storage/' . \Auth::user()->avatar) }}"
                                alt="Header Avatar" style="width: 21px;">
                            <span
                                class="d-none d-sm-inline-block ms-2">{{ \Auth::user()->{'name_' . app()->getLocale()} }}</span>
                            <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ms-1 mt-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0"
                            aria-labelledby="page-header-user-dropdown">
                            <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                                <img class="img-avatar img-avatar48 img-avatar-thumb"
                                    src="{{ asset('storage/' . \Auth::user()->avatar) }}" alt="">
                                <p class="mt-2 mb-0 fw-medium">{{ \Auth::user()->{'name_' . app()->getLocale()} }}</p>
                                {{--  <p class="mb-0 text-muted fs-sm fw-medium">Web Developer</p>  --}}
                            </div>
                            {{--  <div class="p-2">
                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                    <span class="fs-sm fw-medium">Inbox</span>
                    <span class="badge rounded-pill bg-primary ms-2">3</span>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                    <span class="fs-sm fw-medium">Profile</span>
                    <span class="badge rounded-pill bg-primary ms-2">1</span>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                    <span class="fs-sm fw-medium">Settings</span>
                    </a>
                </div>  --}}

                            <div role="separator" class="dropdown-divider m-0"></div>
                            <div class="p-2">
                                {{--  <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                    <span class="fs-sm fw-medium">Lock Account</span>
                    </a>  --}}
                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                    href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <span class="fs-sm fw-medium">@lang('messages.logout')</span>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Language Selector -->
                    <br>

                    <body>
                        <div class="language-switcher">
                            <a href="{{ route('language.switch', 'en') }}">English</a> |
                            <a href="{{ route('language.switch', 'ur') }}">اردو</a>
                        </div>
                        {{--  <!-- Session/Locale Debug - Remove after testing -->
                <form action="{{ route('locale.change') }}" method="POST">
        @csrf
        <select name="locale" onchange="this.form.submit()">
            <option value="en"{{ app()->getLocale() == 'en' ? ' selected' : '' }}>English</option>
            <option value="es"{{ app()->getLocale() == 'es' ? ' selected' : '' }}>Urdu</option>
            <!-- Additional language options -->
        </select>
    </form>  --}}

                        <!-- Page Container -->
                        <!--
                Available classes for #page-container:

            <!-- END User Dropdown -->

                        <!-- Notifications Dropdown -->
                        <div class="dropdown d-inline-block ms-2">
                            {{--  <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-fw fa-bell"></i>
                <span class="text-primary">•</span>
                </button>  --}}
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0 fs-sm"
                                aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-2 bg-body-light border-bottom text-center rounded-top">
                                    <h5 class="dropdown-header text-uppercase">Notifications</h5>
                                </div>
                                <ul class="nav-items mb-0">
                                    <li>
                                        <a class="text-dark d-flex py-2" href="javascript:void(0)">
                                            <div class="flex-shrink-0 me-2 ms-3">
                                                <i class="fa fa-fw fa-check-circle text-success"></i>
                                            </div>
                                            <div class="flex-grow-1 pe-2">
                                                <div class="fw-semibold">You have a new follower</div>
                                                <span class="fw-medium text-muted">15 min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark d-flex py-2" href="javascript:void(0)">
                                            <div class="flex-shrink-0 me-2 ms-3">
                                                <i class="fa fa-fw fa-plus-circle text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1 pe-2">
                                                <div class="fw-semibold">1 new sale, keep it up</div>
                                                <span class="fw-medium text-muted">22 min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark d-flex py-2" href="javascript:void(0)">
                                            <div class="flex-shrink-0 me-2 ms-3">
                                                <i class="fa fa-fw fa-times-circle text-danger"></i>
                                            </div>
                                            <div class="flex-grow-1 pe-2">
                                                <div class="fw-semibold">Update failed, restart server</div>
                                                <span class="fw-medium text-muted">26 min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark d-flex py-2" href="javascript:void(0)">
                                            <div class="flex-shrink-0 me-2 ms-3">
                                                <i class="fa fa-fw fa-plus-circle text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1 pe-2">
                                                <div class="fw-semibold">2 new sales, keep it up</div>
                                                <span class="fw-medium text-muted">33 min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark d-flex py-2" href="javascript:void(0)">
                                            <div class="flex-shrink-0 me-2 ms-3">
                                                <i class="fa fa-fw fa-user-plus text-success"></i>
                                            </div>
                                            <div class="flex-grow-1 pe-2">
                                                <div class="fw-semibold">You have a new subscriber</div>
                                                <span class="fw-medium text-muted">41 min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark d-flex py-2" href="javascript:void(0)">
                                            <div class="flex-shrink-0 me-2 ms-3">
                                                <i class="fa fa-fw fa-check-circle text-success"></i>
                                            </div>
                                            <div class="flex-grow-1 pe-2">
                                                <div class="fw-semibold">You have a new follower</div>
                                                <span class="fw-medium text-muted">42 min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <div class="p-2 border-top text-center">
                                    <a class="d-inline-block fw-medium" href="javascript:void(0)">
                                        <i class="fa fa-fw fa-arrow-down me-1 opacity-50"></i> Load More..
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- END Notifications Dropdown -->

                        <!-- Toggle Side Overlay -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        {{--  <button type="button" class="btn btn-sm btn-alt-secondary ms-2" data-toggle="layout" data-action="side_overlay_toggle">
                <i class="fa fa-fw fa-list-ul fa-flip-horizontal"></i>
            </button>  --}}
                        <!-- END Toggle Side Overlay -->
                </div>
                <!-- END Right Section -->
            </div>
            <!-- END Header Content -->

            <!-- Header Search -->
            <div id="page-header-search" class="overlay-header bg-body-extra-light">
                <div class="content-header">
                    <form class="w-100" action="/dashboard" method="POST">
                        @csrf
                        <div class="input-group">
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <button type="button" class="btn btn-alt-danger" data-toggle="layout"
                                data-action="header_search_off">
                                <i class="fa fa-fw fa-times-circle"></i>
                            </button>
                            <input type="text" class="form-control" placeholder="Search or hit ESC.."
                                id="page-header-search-input" name="page-header-search-input">
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Header Search -->

            <!-- Header Loader -->
            <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
            <div id="page-header-loader" class="overlay-header bg-body-extra-light">
                <div class="content-header">
                    <div class="w-100 text-center">
                        <i class="fa fa-fw fa-circle-notch fa-spin"></i>
                    </div>
                </div>
            </div>
            <!-- END Header Loader -->
        </header>
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container">
            @yield('content')
        </main>
        <!-- END Main Container -->

        <!-- Footer -->
        {{--  <footer id="page-footer" class="bg-body-light">
        <div class="content py-3">
            <div class="row fs-sm">
            <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-end">
                Crafted with <i class="fa fa-heart text-danger"></i> by <a class="fw-semibold" href="https://pixelcave.com" target="_blank">pixelcave</a>
            </div>
            <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
                <a class="fw-semibold" href="https://pixelcave.com/products/oneui" target="_blank">OneUI</a> &copy; <span data-toggle="year-copy"></span>
            </div>
            </div>
        </div>
        </footer>  --}}
        <!-- END Footer -->
    </div>
    <!-- END Page Container -->
</body>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm modal-dialog-popin">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                <h5 class="modal-title" id="confirmDeleteLabel"> @lang('payroll::messages.deletion-confirm') </h5>
            </div>
            <div class="modal-body text-center">
                <p> @lang('payroll::messages.sure-to-delete')</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-sm btn-secondary"
                    data-bs-dismiss="modal">@lang('payroll::messages.cancel')</button>
                <button type="button" class="btn btn-sm btn-danger"
                    id="confirmDeleteBtn">@lang('payroll::messages.yes-delete')</button>
            </div>
        </div>
    </div>
</div>

</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let formToSubmit;

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            formToSubmit = this.closest('form');
        });
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
    });

    jQuery('#tehsil-loading').show();
    $.ajax({
        // ... existing options ...
        complete: function() {
            $('#tehsil-loading').hide();
        }
    });

    //Get list of tehsils on city selection
    document.addEventListener('DOMContentLoaded', function() {
        const citySelect = document.getElementById('city_id');
        const tehsilSelect = document.getElementById('tehsil_id');
        const loadingIndicator = document.getElementById('tehsil-loading');

        citySelect.addEventListener('change', function() {
            const cityId = this.value;
            tehsilSelect.innerHTML = '<option value="">Select City First</option>';
            tehsilSelect.disabled = true;

            if (!cityId) return;

            loadingIndicator.style.display = 'block';

            fetch(`/get-tehsils/${cityId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    tehsilSelect.innerHTML = '';

                    if (data.length > 0) {
                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = 'Select Tehsil';
                        tehsilSelect.appendChild(defaultOption);

                        data.forEach(tehsil => {
                            const option = document.createElement('option');
                            option.value = tehsil.id;
                            option.textContent = tehsil.name_en;
                            tehsilSelect.appendChild(option);
                        });
                    } else {
                        const noDataOption = document.createElement('option');
                        noDataOption.value = '';
                        noDataOption.textContent = 'No tehsils available';
                        tehsilSelect.appendChild(noDataOption);
                    }

                    tehsilSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    tehsilSelect.innerHTML = '<option value="">Error loading tehsils</option>';
                })
                .finally(() => {
                    loadingIndicator.style.display = 'none';
                });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Set initial RTL state
        if (document.documentElement.getAttribute('dir') === 'rtl') {
            document.body.classList.add('rtl');
            document.getElementById('page-container').classList.add('sidebar-r');
        }

        // Watch for language changes
        document.querySelectorAll('.language-switcher a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const isRtl = this.href.includes('ur');

                fetch(this.href)
                    .then(() => {
                        document.documentElement.setAttribute('dir', isRtl ? 'rtl' : 'ltr');
                        document.body.classList.toggle('rtl', isRtl);
                        document.getElementById('page-container').classList.toggle(
                            'sidebar-r', isRtl);
                        window.location.reload();
                    });
            });
        });

        // Toggle sidebar visibility
        document.querySelectorAll('[data-action="sidebar_toggle"]').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('sidebar-o');
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Toggle sidebar
        document.querySelectorAll('[data-action="sidebar_toggle"]').forEach(button => {
            button.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('sidebar-o');

                // Adjust main content width
                const mainContainer = document.getElementById('main-container');
                if (sidebar.classList.contains('sidebar-o')) {
                    mainContainer.style.marginRight = '250px';
                } else {
                    mainContainer.style.marginRight = '0';
                }
            });
        });

        document.querySelectorAll('.nav-main-link-submenu').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const parentItem = this.closest('.nav-main-item');
                const submenu = this.nextElementSibling;

                // Toggle the 'open' class on the parent item
                parentItem.classList.toggle('open');

                // Toggle the submenu display
                if (submenu) {
                    submenu.style.display = submenu.style.display === 'block' ? 'none' :
                        'block';
                }

                // Close other open submenus at the same level
                const siblings = parentItem.parentElement.querySelectorAll('.nav-main-item');
                siblings.forEach(sibling => {
                    if (sibling !== parentItem) {
                        sibling.classList.remove('open');
                        const siblingSubmenu = sibling.querySelector(
                            '.nav-main-submenu');
                        if (siblingSubmenu) {
                            siblingSubmenu.style.display = 'none';
                        }
                    }
                });
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize RTL state
        function setRtlLayout(isRtl) {
            document.documentElement.setAttribute('dir', isRtl ? 'rtl' : 'ltr');
            document.documentElement.setAttribute('lang', isRtl ? 'ur' : 'en');

            // Adjust sidebar position
            const sidebar = document.getElementById('sidebar');
            const pageContainer = document.getElementById('page-container');

            if (isRtl) {
                sidebar.classList.add('sidebar-r');
                pageContainer.classList.add('sidebar-r');
            } else {
                sidebar.classList.remove('sidebar-r');
                pageContainer.classList.remove('sidebar-r');
            }

            // Update keyboard if exists
            if (typeof updateKeyboardLayout === 'function') {
                updateKeyboardLayout();
            }
        }

        // Set initial state
        setRtlLayout(document.documentElement.getAttribute('lang') === 'ur');

        // Handle language switch
        document.querySelectorAll('[data-language-switch]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const lang = this.getAttribute('data-language-switch');

                fetch(this.href, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            setRtlLayout(lang === 'ur');
                            window.location.reload();
                        }
                    });
            });
        });
    });

    function previewImage(input) {
        const preview = document.getElementById('previewImg');
        const previewDiv = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result; // Set image source
                previewDiv.style.display = 'block'; // Show the preview
            }

            reader.readAsDataURL(input.files[0]); // Convert image to Data URL
        } else {
            previewDiv.style.display = 'none'; // Hide if no file selected
        }
    }
</script>
