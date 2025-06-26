@extends('layout.layout')

@section('content')
<div class="container mt-4">
    <div class="card p-4">
        <h1 class="mb-3">Welcome, {{ $user->name }}!</h1>
        <p>Your User ID: {{ $user->user_id }}</p>
        <p>Your Email: {{ $user->email }}</p>
        <p>Gender: {{ $user->gender ?? 'N/A' }}</p>
        <p>Phone Number: {{ $user->phone_no ?? 'N/A' }}</p>
        <p>Member Status: {{ $user->is_member ? 'Yes' : 'No' }}</p>

        <p class="mt-4">This is your personalized dashboard. You are logged in.</p>
        <p>You can now access protected content and features.</p>
    </div>
</div>
@endsection
