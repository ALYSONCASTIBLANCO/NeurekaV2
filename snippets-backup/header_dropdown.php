add_action('wp_footer', function() {
?>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const langBtn = document.querySelector(".language-btn");
  const langDropdown = document.querySelector(".language-dropdown");

  if (langBtn) {
    langBtn.addEventListener("click", function (e) {
      e.preventDefault();
      langDropdown.classList.toggle("open");
    });

    // Cerrar si clickea fuera
    document.addEventListener("click", function(e) {
      if (!langDropdown.contains(e.target)) {
        langDropdown.classList.remove("open");
      }
    });
  }
});
</script>
<?php
});