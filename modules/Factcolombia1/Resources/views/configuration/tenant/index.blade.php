@extends('tenant.layouts.app')

@section('content')
    <tenant-companies-form></tenant-companies-form>
    <tenant-configuration-general-data route="{{route('tenant.configuration')}}"></tenant-configuration-general-data>
    <tenant-configuration-software route="{{route('tenant.configuration')}}"></tenant-configuration-software>
    <tenant-configuration-software-payroll route="{{route('tenant.configuration')}}"></tenant-configuration-software-payroll>
    <tenant-configuration-software-eqdocs route="{{route('tenant.configuration')}}"></tenant-configuration-software-eqdocs>
    <tenant-configuration-certificate route="{{route('tenant.configuration')}}"></tenant-configuration-certificate>
    <tenant-configuration-resolution route="{{route('tenant.configuration')}}"></tenant-configuration-resolution>
@endsection
