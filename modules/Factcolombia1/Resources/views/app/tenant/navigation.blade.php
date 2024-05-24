@php
    $path = explode('/', request()->path());
    $path[1] = (array_key_exists(1, $path)> 0)?$path[1]:'';
    $path[2] = (array_key_exists(2, $path)> 0)?$path[2]:'';
    $path[0] = ($path[0] === '')?'documents':$path[0];
@endphp
<aside class="sidebar" style="display: block;">
    @php
        $company = \App\Models\Tenant\Company::firstOrFail();
    @endphp
    <tenant-logo url="/client" path_logo="{{($company->logo != null) ? "/storage/uploads/logos/{$company->logo}" : ''}}" ></tenant-logo>
    <nav>
        <ul class="sidebar-list">

            <li>
                <a class="{{ ($path[0] === 'client' && $path[1] === '')?'sidebar-list-item active':'sidebar-list-item' }}" href="{{route('client')}}">
                    <i class="fe fe-sliders"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a class="{{ ($path[0] === 'client' && $path[1] === 'documents')?'sidebar-list-item active':'sidebar-list-item' }}" href="{{route('tenant.document')}}">
                    <i class="fas fa-file-alt"></i>
                    <span>Documentos</span>
                </a>
            </li>
            <li><a class="{{ ($path[0] === 'client' && $path[1] === 'configuration' && $path[2] === 'production')?'sidebar-list-item active':'sidebar-list-item' }}" href="{{route('tenant.configuration.production')}}">Pasar a produccion</a></li>
            <li class="active">
                <a class="{{ ($path[0] === 'client' && $path[1] === 'quotations')?'sidebar-list-item active':'sidebar-list-item' }}" href="{{route('tenant.quotation')}}">
                    <i class="fas fa-calculator"></i>
                    <span>Cotizaciones</span>
                </a>
            </li>
            <li>
                <a class="{{ ($path[0] === 'client' && $path[1] === 'clients')?'sidebar-list-item active':'sidebar-list-item' }}" href="{{route('tenant.client')}}">
                    <i class="fe fe-users"></i>
                    <span>Clientes</span>
                </a>
            </li>
            <li>
                <a class="{{ ($path[0] === 'client' && $path[1] === 'items')?'sidebar-list-item active':'sidebar-list-item' }}" href="{{route('tenant.item')}}">
                    <i class="fe fe-shopping-cart"></i>
                    <span>Productos</span>
                </a>
            </li>
            <li>
                <a class="{{ ($path[0] === 'client' && $path[1] === 'taxes')?'sidebar-list-item active':'sidebar-list-item' }}" href="{{route('tenant.tax')}}">
                    <i class="fe fe-book-open"></i>
                    <span>Impuestos</span>
                </a>
            </li>
            <li>
                <a class="{{($path[0] === 'client' && $path[1] === 'report-taxes') ? 'sidebar-list-item active' : 'sidebar-list-item'}}" href="{{route('tenant.report.taxes')}}">
                    <i class="fas fa-file-invoice"></i>
                    <span>Reporte impuestos</span>
                </a>
            </li>
            <li class="{{ ($path[0] === 'client' && $path[1] === 'configuration')?'sidebar-list-item active':'sidebar-list-item' }}"><i class="fe fe-settings"></i> <span>Configuración</span> <i class="fas fa-chevron-down pull-right"></i></li>
            <li>
                <ul>
                    <li><a class="{{ ($path[0] === 'client' && $path[1] === 'configuration' && $path[2] === '')?'sidebar-list-item active':'sidebar-list-item' }}" href="{{route('tenant.configuration')}}">Empresa</a></li>
                    <li><a class="{{ ($path[0] === 'client' && $path[1] === 'configuration' && $path[2] === 'documents')?'sidebar-list-item active':'sidebar-list-item' }}" href="{{route('tenant.configuration.documents')}}">Documentos</a></li>
                    <li><a class="{{ ($path[0] === 'client' && $path[1] === 'configuration' && $path[2] === 'production')?'sidebar-list-item active':'sidebar-list-item' }}" href="{{route('tenant.configuration.production')}}">Cambiar Ambiente</a></li>

                </ul>
            </li>
        </ul>
    </nav>
</aside>
