<?php require_once('../config.php'); ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
 <?php require_once('inc/header.php') ?>
<body class="hold-transition login-page">
  <script>
    start_loader()
  </script>
  <h2 class="text-center mb-4 pb-4"><?php echo $_settings->info('name') ?></h2>
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-body">
      <p class="login-box-msg">Please customer enter you credentials</p>

      <form id="login-client" action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="login" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <div class="col-6">
          <a href="registration.php">Signup</a>
          <!-- /.col -->
        </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- /.social-auth-links -->

      <!-- <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p> -->
      
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->
<?php
if(isset($_POST['login']))
{
  extract($_POST);
  $settings=new SystemSettings();
$conn= new mysqli("localhost","root","","water_refilling_db");
    $qry = $conn->query("SELECT * from users where username = '$username' and password = '$password'");
    if($qry->num_rows > 0){
      foreach($qry->fetch_array() as $k => $v){
        if(!is_numeric($k) && $k != 'password'){
          $settings->set_userdata($k,$v);
               }

      }
      $settings->set_userdata('login_type',1);
   ?>
<script type="text/javascript">
  setTimeout(function () {
   window.location.href= 'index.php'; // the redirect goes here

},3000);
</script>
   <?php
      echo "Success";
    return json_encode(array('status'=>'success'));
    }else{
    return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username' and password = '$password'"));
    }
  

}

?>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
  $(document).ready(function(){
    end_loader();
  })
</script>
</body>
</html>