</main>
<footer class="bg-dark text-light py-5 mt-auto">
  <div class="container">
    <div class="row">
            
      <div class="col-md-4 mb-4 mb-md-0">
        <h5 class="text-uppercase mb-3">Kontakt</h5>
        <address class="mb-0">
          <strong>Super Sklep Sp. z o.o.</strong><br>
          ul. Przykładowa 12/3<br>
          00-001 Warszawa<br><br>
          Telefon: <a href="tel:+48123456789" class="text-light text-decoration-none">123 456 789</a><br>
          Email: <a href="mailto:kontakt@supersklep.pl" class="text-light text-decoration-none">kontakt@supersklep.pl</a>
        </address>
      </div>

      <div class="col-md-4 mb-4 mb-md-0 text-md-center">
        <h5 class="text-uppercase mb-3">Śledź nas</h5>
        <ul class="list-unstyled d-flex justify-content-md-center gap-3">
          <li><a href="#!" class="text-light text-decoration-none hover-white">Facebook</a></li>
          <li><a href="#!" class="text-light text-decoration-none hover-white">Instagram</a></li>
          <li><a href="#!" class="text-light text-decoration-none hover-white">TikTok</a></li>
        </ul>
      </div>

      <div class="col-md-4 text-md-end">
        <h5 class="text-uppercase mb-3">O sklepie</h5>
        <p class="mb-1">
          Sklep z odzieżą, projekt Zaliczeniowy TAB
        </p>

        <small class="text-white-50">
          &copy; <?= date('Y') ?> projekt studencki sklepu z odzieżą.<br> Wszelkie prawa zastrzeżone.
        </small>
      </div>

    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script> </body>

<?php if ($page === 'product'): ?>
    <script src="js/product.js"></script>
<?php endif; ?>
<?php if ($page === 'cart'): ?>
    <script src="js/cart.js"></script>
<?php endif; ?>

</html>
