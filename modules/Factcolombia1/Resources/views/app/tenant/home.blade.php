@extends('layouts.app')

@section('content')
    <main class="content">
        <div class="container-fluid">
        	<div class="content-header">
                <h1>Dashboard</h1>
            </div>
			<div class="row" style="min-height: 100vh;">
					<div class="col-md-6 col-lg-6">
						<div class="info-box">
							<div class="info-box-content">
								<div class="info-box-text">
									<span class="info-box-number">
										{{ $allclients }} <small class="text-sm text-success"><i class="fas fa-chevron-up"></i>{{ $clientstoday }}</small>
									</span>
									Total Clientes
								</div>
								<div class="info-box-icon bg-primary">
									<i class="i-user"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-6">
						<div class="info-box">
							<div class="info-box-content">
								<div class="info-box-text">
									<span class="info-box-number">
										{{ $alldocuments }} <small class="text-sm text-success"><i class="fas fa-chevron-up"></i>{{ $documentstoday }}</small>
									</span>
									Total Documentos
								</div>
								<div class="info-box-icon bg-info">
									<i class="fe fe-folder"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
        </div>
    </main>
@endsection
