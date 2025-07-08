<footer class="site-footer text-white py-5">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-6 mb-4">
        <h6>About UniSPA</h6>
        <p class="text-justify text-white">UniSPA is your ultimate sanctuary for relaxation and rejuvenation. We are dedicated to providing a holistic wellness experience that nurtures your body, calms your mind, and uplifts your spirit. Our expert therapists offer personalized treatments using premium products in a serene and tranquil environment, ensuring every visit leaves you feeling refreshed and revitalized. Discover peace and harmony with UniSPA.</p>
      </div>

      <div class="col-xs-6 col-md-3 mb-4">
        <h6>Our Services</h6>
        <ul class="footer-links list-unstyled">
          <li><a href="{{ route('packages.index', ['category' => 'facial']) }}">Facial Treatments</a></li>
          <li><a href="{{ route('packages.index', ['category' => 'massage']) }}">Massages & Bodywork</a></li>
          <li><a href="{{ route('packages.index', ['category' => 'hydrotherapy']) }}">Hydrotherapy</a></li>
          <li><a href="{{ route('packages.index', ['category' => 'wellness']) }}">Wellness Programs</a></li>
          <li><a href="{{ route('packages.index', ['category' => 'couples']) }}">Couples Packages</a></li>
          <li><a href="{{ route('packages.index') }}">View All Services</a></li>
        </ul>
      </div>

      <div class="col-xs-6 col-md-3 mb-4">
        <h6>Quick Links</h6>
        <ul class="footer-links list-unstyled">
          <li><a href="{{ route('about') }}">About Us</a></li>
          <li><a href="{{ route('contact') }}">Contact Us</a></li>
          <li><a href="{{ route('login') }}">Login</a></li>
          <li><a href="{{ route('register') }}">Register</a></li>
          <li><a href="{{ route('packages.index') }}">Book Now</a></li>
        </ul>
      </div>
    </div>
    <hr class="bg-light">
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-sm-6 col-xs-12">
        <p class="copyright-text mb-0">Copyright &copy; {{ date('Y') }} All Rights Reserved by
          <a href="{{ url('/') }}">UniSPA</a>.
        </p>
      </div>

      <div class="col-md-4 col-sm-6 col-xs-12 text-center text-md-end">
        <ul class="social-icons list-unstyled d-flex justify-content-center justify-content-md-end mb-0">
          <li class="ms-2"><a class="facebook" href="#"><i class="fab fa-facebook-f"></i></a></li>
          <li class="ms-2"><a class="twitter" href="#"><i class="fab fa-twitter"></i></a></li>
          <li class="ms-2"><a class="instagram" href="#"><i class="fab fa-instagram"></i></a></li>
          <li class="ms-2"><a class="whatsapp" href="#"><i class="fab fa-whatsapp"></i></a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
