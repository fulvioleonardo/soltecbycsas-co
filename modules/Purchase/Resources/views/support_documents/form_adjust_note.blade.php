@extends('tenant.layouts.app')

@section('content')

    <tenant-support-documents-form-adjust-note :support-document-id="{{ json_encode($support_document_id) }}"></tenant-support-documents-form-adjust-note>

@endsection