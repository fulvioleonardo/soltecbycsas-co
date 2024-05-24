@extends('tenant.layouts.app')

@section('content')
    <tenant-configuration-documents route="{{route('tenant.configuration.documents')}}"></tenant-configuration-documents>
@endsection
