<style>
  @media (max-width: 768px) {
    .custom-template {
      display: none !important;
    }
  }
</style>


</div> <!-- Menutup div yang sebelumnya terbuka -->
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- Custom template untuk pengaturan tampilan -->
<div class="custom-template">
  <div class="title">Settings</div> <!-- Judul panel pengaturan -->
  <div class="custom-content">
    <div class="switcher">

      <!-- Bagian untuk mengubah warna Navbar Header -->
      <div class="switch-block">
        <h4>Navbar Header</h4> <!-- Judul bagian -->
        <div class="btnSwitch">
          <!-- Tombol untuk mengubah warna Navbar menjadi dark, blue, atau white -->
          <button type="button" class="changeTopBarColor" data-color="dark"></button> <!-- Warna Gelap -->
          <button type="button" class="selected changeTopBarColor" data-color="white"></button> <!-- Warna Putih -->
        </div>
      </div>

      <!-- Bagian untuk mengubah warna Sidebar -->
      <div class="switch-block">
        <h4>Sidebar</h4> <!-- Judul bagian -->
        <div class="btnSwitch">
          <!-- Tombol untuk mengubah warna Sidebar menjadi dark atau white -->
          <button type="button" class="changeSideBarColor" data-color="white"></button> <!-- Warna Putih -->
          <button type="button" class="changeSideBarColor" data-color="dark"></button> <!-- Warna Gelap -->
        </div>
      </div>

    </div>
  </div>

  <!-- Tombol untuk membuka panel pengaturan -->
  <div class="custom-toggle">
    <i class="icon-settings"></i>
  </div>
</div>
<!-- End Custom template -->

</div>

<!-- Script untuk menyembunyikan logo jika layar lebih kecil dari 768px -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    if (window.innerWidth <= 768) {
      let logo = document.querySelector(".logo-header .logo img");
      if (logo) {
        logo.style.display = "none"; // Menyembunyikan logo pada tampilan mobile
      }
    }
  });
</script>

<!--   Core JS Files   -->
<script src="assets/js/core/jquery-3.7.1.min.js"></script> <!-- Library jQuery -->
<script src="assets/js/core/popper.min.js"></script> <!-- Library Popper.js untuk Bootstrap -->
<script src="assets/js/core/bootstrap.min.js"></script> <!-- Library Bootstrap -->

<!-- jQuery Scrollbar -->
<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script> <!-- Plugin scrollbar -->

<!-- Chart JS untuk visualisasi data -->
<script src="assets/js/plugin/chart.js/chart.min.js"></script>

<!-- jQuery Sparkline untuk grafik kecil -->
<script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- Chart Circle untuk diagram lingkaran -->
<script src="assets/js/plugin/chart-circle/circles.min.js"></script>

<!-- Datatables untuk menampilkan tabel interaktif -->
<script src="assets/js/plugin/datatables/datatables.min.js"></script>

<!-- Sweet Alert untuk notifikasi pop-up -->
<script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- Kaiadmin JS untuk fitur admin -->
<script src="assets/js/kaiadmin.min.js"></script>

<!-- Kaiadmin DEMO methods, jangan sertakan dalam proyek produksi -->
<script src="assets/js/setting-demo.js"></script>
<script src="assets/js/demo.js"></script>



</body>

</html>