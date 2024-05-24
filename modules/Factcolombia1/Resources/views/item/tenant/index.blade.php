@extends('tenant.layouts.app')

@section('content')
    <tenant-item-item route="{{route('tenant.co-items.items')}}"></tenant-item-item>
    {{-- <tenant-item-item route="{{route('tenant.item')}}"></tenant-item-item> --}}
@endsection
