@extends('tenant.layouts.app')

@section('content')
    {{-- <tenant-document-form route="{{route('tenant.document.form')}}"></tenant-document-form> --}}
    <tenant-document-form :is_health="{{ json_encode(true) }}"></tenant-document-form>
@endsection
