@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <h1 class="mb-4 text-primary">Contact UniSPA</h1>

        <p class="lead">We'd love to hear from you! Please reach out with any questions, feedback, or booking inquiries.</p>

        <div class="row mt-4">
            <div class="col-md-6">
                <h2 class="mb-3">Get in Touch</h2>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt me-2 text-primary"></i> 123 Spa Avenue, Relaxation City, 12345 MY</li>
                    <li><i class="fas fa-phone me-2 text-primary"></i> +60 12-345 6789</li>
                    <li><i class="fas fa-envelope me-2 text-primary"></i> info@unispa.com</li>
                    <li><i class="fas fa-clock me-2 text-primary"></i> Mon - Fri: 9:00 AM - 8:00 PM</li>
                    <li><i class="fas fa-clock me-2 text-primary"></i> Sat - Sun: 10:00 AM - 6:00 PM</li>
                </ul>

                <h2 class="mt-4 mb-3">Follow Us</h2>
                <div class="social-icons">
                    <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="linkedin"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="mb-3">Send Us a Message</h2>
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" class="form-control" id="email" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" placeholder="Subject of your inquiry">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Your Message</label>
                        <textarea class="form-control" id="message" rows="5" placeholder="Type your message here..." required></textarea>
                    </div>
                    <div class="text-center">
                    <button type="submit" class="btn btn-primary">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
