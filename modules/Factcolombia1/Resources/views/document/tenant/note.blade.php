@extends('tenant.layouts.app')

@section('content')
    {{-- <tenant-document-form route="{{route('tenant.document.form')}}"></tenant-document-form> --}}
    <tenant-note-form :note="{{ json_encode($note) }}"></tenant-note-form>
@endsection
