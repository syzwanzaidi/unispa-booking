@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <h1 class="mb-4 text-primary">About UniSPA</h1>

        <p class="lead" style="font-family: Arial, sans-serif; font-weight: bold;">
        Welcome to UniSPA, your sanctuary for relaxation and rejuvenation.</p>

        <p>At UniSPA, we believe in providing a holistic wellness experience that nurtures your body, calms your mind, and uplifts your spirit. Our expert therapists are dedicated to delivering personalized treatments using premium products in a serene and tranquil environment.</p>

        <h2 class="mt-5 mb-3">Our Mission</h2>
        <p>Our mission is to offer an unparalleled spa experience, promoting health, beauty, and well-being through exceptional services and a commitment to client satisfaction.</p>

        <h2 class="mt-5 mb-3">Our Values</h2>
        <ul>
            <li><strong>Excellence:</strong> We strive for the highest standards in every treatment and service.</li>
            <li><strong>Tranquility:</strong> We create a peaceful atmosphere for ultimate relaxation.</li>
            <li><strong>Personalization:</strong> Every client's needs are unique, and our services reflect that.</li>
            <li><strong>Integrity:</strong> We operate with honesty, transparency, and respect.</li>
        </ul>

        <h2 class="mt-5 mb-3">Our Team</h2>
        <p>Our team comprises highly skilled and certified professionals passionate about wellness. They bring years of experience and a gentle touch to ensure you receive the best care possible.</p>

        <div class="text-center mt-5">
            <img src="{{ asset('img/Unispa-4.jpg') }}" alt="Spa Interior" class="img-fluid rounded shadow-sm mb-3">
            <p class="text-muted">A glimpse into our tranquil spa environment.</p>
        </div>
    </div>
</div>
@endsection
