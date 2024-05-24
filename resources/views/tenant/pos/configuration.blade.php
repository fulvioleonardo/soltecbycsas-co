@extends('tenant.layouts.app')

@section('content')
    <tenant-pos-configuration :configuration="{{ json_encode($configuration)}}">
    </tenant-pos-configuration>
@endsection
