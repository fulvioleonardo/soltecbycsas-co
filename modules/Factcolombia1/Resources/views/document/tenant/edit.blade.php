@extends('tenant.layouts.app')

@section('content')
    {{-- <tenant-document-form route="{{route('tenant.document.form')}}"></tenant-document-form> --}}
    <tenant-document-form :invoice="{{ json_encode($invoice) }}" :is_edit="{{ json_encode(true) }}"></tenant-document-form>
@endsection
