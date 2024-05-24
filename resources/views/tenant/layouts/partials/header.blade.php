<header class="header">
    <div class="logo-container">
        <div class="sidebar-toggle" data-toggle-class="sidebar-left-collapsed" data-target="html"
            data-fire-event="sidebar-left-toggle">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
        <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
        <div class="d-md-none ml-1 d-lg-block" style="height: inherit;">
            <a href="{{ route('tenant.co-documents.create') }}"
                title="Nueva Factura Electrónica"
                class="topbar-links"
                data-placement="bottom"
                data-toggle="tooltip">
                <i aria-hidden="true" class="fas fa-fw fa-plus"></i>
                <span>NFE</span>
            </a>
            <a href="{{ route('tenant.pos.index') }}"
                title="POS"
                class="topbar-links"
                data-placement="bottom"
                data-toggle="tooltip">
                <i aria-hidden="true" class="fas fa-fw fa-plus"></i>
                <span>POS</span>
            </a>
            <a href="{{ route('tenant.purchases.create') }}"
                title="Generar Compra"
                class="topbar-links"
                data-placement="bottom"
                data-toggle="tooltip">
                <i aria-hidden="true" class="fas fa-fw fa-plus"></i>
                <span>GC</span>
            </a>
            <a href="{{ route('tenant.items.index') }}"
                title="Nuevo Producto"
                class="topbar-links"
                data-placement="bottom"
                data-toggle="tooltip">
                <i aria-hidden="true" class="fas fa-fw fa-plus"></i>
                <span>NP</span>
            </a>
            <a href="{{ route('tenant.reports.customers.index') }}"
                title="Nuevo Cliente"
                class="topbar-links"
                data-placement="bottom"
                data-toggle="tooltip">
                <i aria-hidden="true" class="fas fa-fw fa-plus"></i>
                <span>CL</span>
            </a>
        </div>
    </div>
    <div class="header-right">
        <span class="separator"></span>
        <div id="userbox" class="userbox">
            <a href="#" data-toggle="dropdown">
                <figure class="profile-picture">
                    <div class="border rounded-circle text-center" style="width: 25px;"><i class="fas fa-user"></i></div>
                </figure>
                <div class="profile-info" data-lock-name="{{ $vc_user->email }}" data-lock-email="{{ $vc_user->email }}">
                    <span class="name">{{ $vc_user->name }}</span>
                    <span class="role">{{ $vc_user->email }}</span>
                </div>
                <i class="fa custom-caret"></i>
            </a>
            <div class="dropdown-menu">
                <ul class="list-unstyled mb-2">
                    {{-- <li class="divider"></li> --}}
                    <li>
                        {{--<a role="menuitem" href="#"><i class="fas fa-user"></i> Perfil</a>--}}
                        <a role="menuitem" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-power-off"></i> @lang('app.buttons.logout')
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
