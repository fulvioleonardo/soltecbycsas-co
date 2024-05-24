@extends('layouts.app')

@section('content')
    <tenant-quotation-form  api="{{ env('SERVICE_FACT', '') }}" route="{{route('tenant.quotation.form')}}"></tenant-quotation-form>
@endsection
