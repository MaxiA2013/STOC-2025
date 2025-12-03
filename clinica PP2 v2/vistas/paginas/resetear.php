<div class="row align-items-center pb-5">
  <div class="col-lg-4 rounded mx-auto px-3 py-3" style="background-color: rgb(79, 100, 139); margin-top: 5%;">
    <h3 style="color: white;">Restablecer contraseña</h3>
    <form class="needs-validation" novalidate method="POST" action="controladores/recuperar.controlador.php">
      <input type="hidden" name="action" value="resetear_con_token" />
      <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>" />

      <div class="mb-3 mt-3">
        <label for="nueva" class="form-label" style="color: white;">Nueva contraseña</label>
        <input type="password" class="form-control" id="nueva" name="nueva" required>
      </div>

      <div class="mb-3 mt-3">
        <label for="confirmar" class="form-label" style="color: white;">Confirmar contraseña</label>
        <input type="password" class="form-control" id="confirmar" name="confirmar" required>
        <input type="hidden" name="email" value="<?= $_GET['email'] ?>">
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-dark">Actualizar contraseña</button>
      </div>
    </form>

    <!-- Mensajes de feedback -->
    <?php if (!empty($_SESSION['msg'])): ?>
      <div class="alert alert-info mt-3">
        <?= htmlspecialchars($_SESSION['msg']); ?>
      </div>
      <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>
  </div>

  <div class="col col-lg-6 text-center text-lg-start p-3 mx-auto">
    <h1 class="diplay-4 fw-bold 1h-1 text-body-emphasis mb-3">Restablecé tu clave</h1>
    <p class="col-lg-10 fs-4">Ingresá tu nueva contraseña y confirmala para completar el proceso.</p>
  </div>
<<<<<<< HEAD
</div>
=======
</div>
>>>>>>> origin/mi-ramita
