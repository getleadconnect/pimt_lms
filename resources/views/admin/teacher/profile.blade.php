@extends('admin.layouts.master')
@section('title','Teacher Profile')
@section('contents')
<div class="card"><div class="card-body">
    <h5 class="mb-3">My Profile</h5>
    <p class="text-muted">Name: <strong>{{ $admin->name ?? '-' }}</strong></p>
    <p class="text-muted">Email: <strong>{{ $admin->email ?? '-' }}</strong></p>
    <p class="text-muted">Mobile: <strong>{{ $admin->mobile ?? '-' }}</strong></p>
</div></div>
@endsection
