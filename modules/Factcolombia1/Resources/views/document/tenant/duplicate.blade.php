@extends('tenant.layouts.app')

@section('content')
    {{-- <tenant-document-form route="{{route('tenant.document.form')}}"></tenant-document-form> --}}
    <tenant-document-form :invoice="{{ json_encode($invoice) }}"></tenant-document-form>
@endsection
