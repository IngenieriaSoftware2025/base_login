<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Glassmorphism Style</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- MDB CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.css" rel="stylesheet">
</head>
<body>

<!-- Section: Design Block -->
<section class="text-center vh-100">
  <!-- Background image -->
  <div class="p-5 bg-image" style="
        background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg');
        height: 400px;
        "></div>
  <!-- Background image -->
  
  <div class="card mx-4 mx-md-5 shadow-5-strong bg-body-tertiary" style="
        margin-top: -150px;
        backdrop-filter: blur(30px);
        ">
    <div class="card-body py-5 px-md-5">
      <div class="row d-flex justify-content-center">
        <div class="col-lg-6 col-md-8">
          
          <form id="FormLogin">
            <div class="mb-md-5 mt-md-4 pb-5">
              
              <h2 class="fw-bold mb-2 text-uppercase">INICIO DE SESION</h2>
              <p class="text-muted mb-5">INGRESA USUARIO Y CONTRASEÑA</p>

              <!-- DPI input -->
              <div data-mdb-input-init class="form-outline mb-4">
                <input type="text" name="usu_codigo" id="usu_codigo" class="form-control form-control-lg" />
                <label class="form-label" for="usu_codigo">DPI</label>
              </div>

              <!-- Password input -->
              <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" name="usu_password" id="usu_password" class="form-control form-control-lg" />
                <label class="form-label" for="usu_password">Password</label>
              </div>

              <!-- Submit button -->
              <button type="submit" id="BtnIniciar" class="btn btn-primary btn-lg px-5 mb-4">
                Login
              </button>

  

            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.js"></script>

<script src="<?= asset('build/js/login/login.js') ?>"></script>

<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    
    body {
        min-height: 100vh;
        background: #f8f9fa;
    }
    
    .bg-image {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    

    .card {
        background: rgba(255, 255, 255, 0.9) !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }

    
    .btn-floating:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
   
    .form-control:focus {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        background: linear-gradient(45deg, #764ba2 0%, #667eea 100%);
    }
</style>

</body>
</html>