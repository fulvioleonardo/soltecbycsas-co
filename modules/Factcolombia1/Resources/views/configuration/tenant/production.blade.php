@extends('layouts.app')

@section('content')
    <tenant-configuration-production route="{{route('tenant.configuration.production')}}"></tenant-configuration-production>
@endsection
