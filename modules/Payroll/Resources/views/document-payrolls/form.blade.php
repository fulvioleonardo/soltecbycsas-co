@extends('tenant.layouts.app')

@section('content')

    <tenant-document-payrolls-form
        :affected_document_payroll_id="{{ json_encode($id ?? null) }}" 
        :type_payroll_adjust_note_id="{{ json_encode($type_payroll_adjust_note_id ?? null) }}" 
        >
    </tenant-document-payrolls-form>

@endsection
