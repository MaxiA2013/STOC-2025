<div class="row align-items-center pb-5">
  <div class="col-lg-4 rounded mx-auto px-3 py-3" style="background-color: rgb(79, 100, 139); margin-top: 5%;">
    <h3 style="color: white;">Recuperar contraseña</h3>
    <form class="needs-validation" novalidate method="POST" action="controladores/recuperar.controlador.php">
      <input type="hidden" name="action" value="solicitar_recuperacion" />
      
      <div class="mb-3 mt-3">
        <label for="email_recuperar" class="form-label" style="color: white;">Ingresá tu correo electrónico</label>
        <input type="email" class="form-control" id="email_recuperar" name="email_recuperar" placeholder="tu@correo.com" required>
        <p id="id_email_recuperar_parrafo" style="color: red; display:none;">Email requerido</p>
      </div>
      
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-dark">Enviar enlace de recuperación</button>
      </div>
    </form>

    <!-- Mensajes de feedback -->
    <?php if (!empty($_SESSION['msg'])): ?>
      <div class="alert alert-info mt-3">
        <?= htmlspecialchars($_SESSION['msg']); ?>
      </div>
      <?php if (!empty($_SESSION['test_link'])): ?>
        <div class="alert alert-warning mt-2">
          Enlace de prueba (dev): 
          <a href="<?= $_SESSION['test_link']; ?>">Resetear contraseña</a>
        </div>
        <?php unset($_SESSION['test_link']); ?>
      <?php endif; ?>
      <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>
  </div>

  <div class="col col-lg-6 text-center text-lg-start p-3 mx-auto">
    <h1 class="diplay-4 fw-bold 1h-1 text-body-emphasis mb-3">¿Olvidaste tu clave?</h1>
    <p class="col-lg-10 fs-4">Ingresá tu correo y te enviaremos un enlace para que la restablezcas de forma segura.</p>
  </div>
</div>
