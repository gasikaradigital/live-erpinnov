<!-- Navbar -->
<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-xxl">
        <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
            <a href="{{ route('espaceClient') }}" class="app-brand-link gap-2">
                <img src="{{ asset('assets/img/logo/logo.png') }}"
                alt="Logo ERP INNOV"
                style="width: 40px; height: 40px;"
                class="img-fluid">
                <span class="app-brand-text demo menu-text fw-bold">ERP INNOV</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                <i class="ti ti-x ti-sm align-middle"></i>
            </a>
        </div>

        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="ti ti-menu-2 ti-sm"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Notification -->
                {{-- <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" aria-expanded="false">
                        <i class="ti ti-bell ti-md"></i>
                        <span class="badge bg-danger rounded-pill badge-notifications">5</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-0">
                        <li class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h5 class="text-body mb-0 me-auto">Notification</h5>
                                <a href="javascript:void(0)" class="dropdown-notifications-all text-body"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read"><i
                                        class="ti ti-mail-opened fs-4"></i></a>
                            </div>
                        </li>
                        <li class="dropdown-notifications-list scrollable-container">
                            <ul class="list-group list-group-flush">
                                <li
                                    class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar">
                                                <span class="avatar-initial rounded-circle bg-label-warning"><i
                                                        class="ti ti-alert-triangle"></i></span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Aucun nofitification</h6>
                                            <p class="mb-0">CPU Utilization Percent is currently at 88.63%,</p>
                                            <small class="text-muted">5 days ago</small>
                                        </div>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                                    class="badge badge-dot"></span></a>
                                            <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                                    class="ti ti-x"></span></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown-menu-footer border-top">
                            <a href="javascript:void(0);"
                                class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                                View all notifications
                            </a>
                        </li>
                    </ul>
                </li> --}}
                <!--/ Notification -->

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <div class="fw-bold rounded-circle d-flex align-items-center justify-content-center text-white"
                                 style="width: 40px; height: 40px; background-color: var(--bs-primary);">
                                {{ auth()->user()->initials }}
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <div class="fw-bold rounded-circle d-flex align-items-center justify-content-center text-white"
                                                style="width: 40px; height: 40px; background-color: var(--bs-primary);">
                                                {{ auth()->user()->initials }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-medium d-block">{{ Auth()->user()->name }}</span>
                                        <small class="text-muted">Client</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                         <li>
                            <a class="dropdown-item" href="{{ route('client.profile') }}">
                                <i class="ti ti-user-check me-2 ti-sm"></i>
                                <span class="align-middle">Mon compte</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="ti ti-help me-2 ti-sm"></i>
                                <span class="align-middle">Aide</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <a class="dropdown-item" href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    <i class="ti ti-logout me-2 ti-sm"></i>
                                    <span class="align-middle">Se deconnecter</span>
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>
    </div>
</nav>

<!-- / Navbar -->
