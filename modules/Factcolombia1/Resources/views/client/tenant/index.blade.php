@extends('tenant.layouts.app')

@section('content')
    <tenant-client-client route="{{route('tenant.co-clients.clients')}}"></tenant-client-client>
@endsection
