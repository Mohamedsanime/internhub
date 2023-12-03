<?php
  $con = mysqli_connect("localhost", "root", "","itec404");
  $sql = "SELECT id,role_name From roles where id > 1";
  $result=mysqli_query($con,$sql);
  $nat = mysqli_query($con, 'SELECT num_code, nationality From countries order by nationality')
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>InternHub | Registration Page (v2)</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="assets/dist/css/style2.css">

  <script>
        // JavaScript
        function roleChanged(role) {
            var uniSupervisorFields = document.getElementById('uniSupervisorFields');
            var compSupervisorFields = document.getElementById('compSupervisorFields');
            var studentFields = document.getElementById('studentFields');

            uniSupervisorFields.style.display = role === '2' ? 'block' : 'none';
            compSupervisorFields.style.display = role === '3' ? 'block' : 'none';
            studentFields.style.display = role === '4' ? 'block' : 'none';
        }
   </script>
</head>

<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../../index2.html" class="h2"><b>Sign Up a new account</b></a>
    </div>
    <div class="card-body">
      <form action="register_valid.php" method="post">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="label" for="name">First Name </label>
            <input type="text" class="form-control" placeholder="First name">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="label" for="name">Last Name </label>
            <input type="text" class="form-control" placeholder="Last name">
          </div>
        </div>
        <div class="col-md-6"> 
          <div class="form-group">
            <label class="label" for="email">Email Address</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email">
          </div>
        </div>
        
        <div class="col-md-6"> 
          <div class="form-group">
            <label for="role">Account Type</label>

            <select name= "roleid" id= "roleid" class="form-control" required onchange="roleChanged(this.value)">
            <option value="">Select Role</option>
                <?php
                    while($row=mysqli_fetch_array($result))
                    {
                ?>
                      <option value="<?php echo $row['id']; ?>"> <?php echo $row['role_name'];?> </option>; 
                     <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="label" for="Password1">Password</label>
            <input type="password" class="form-control" name="password" id="Password" placeholder="Password">
          </div>
        </div>

       <!--
         <div class="col-6">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
              </div>
              <input name="password" type="password" value="" class="input form-control" id="password" placeholder="password" required="true" aria-label="password" aria-describedby="basic-addon1" />
              <div class="input-group-append">
                <span class="input-group-text" onclick="password_show_hide();">
                  <i class="fas fa-eye" id="show_eye"></i>
                  <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                </span>
              </div>
            </div>
        </div>
        -->

        <div class="col-md-6">
          <div class="form-group">
            <label class="label" for="Password2">Retype Password</label>
            <input type="password" class="form-control" name="password2" id="Password2" placeholder="Retype Password">
          </div>
        </div>

        				<!-- Additional fields for Student -->
		<div id="studentFields" style="display: none;">
    <div class="row">
			<div class="col-md-3">
        <div class="form-group">
				  <label for="studentid" class="form-label">Student ID:</label> 
				  <input type="text" class="form-control" name="studentid" id="studentid">
			  </div>
      </div>

			<div class="col-6">
				<label for="inputqualif" class="form-label">University: </label> 
				<input type="text" class="form-control" id="university" name="university">
			</div>

			<div class="col-md-3">
        <div class="form-group">
				  <label for="inputgender" class="form-label d-block">Gender: </label>
				  <input type="radio" name="gender" value="male"> Male 
				  <input type="radio" name="gender" value="female"> Female
			  </div>
      </div>

			<div class="col-6">
        <div class="form-group">
				  <label for="inputqualif" class="form-label">Phone No: </label> 
				  <input type="text" class="form-control" id="phone" name="phone" placeholder="(5xx)-(xxx)-(xx)-(xx)">
			  </div>
      </div>

      <div class="col-md-6"> 
          <div class="form-group">
            <label for="role">Nationality</label>

            <select name= "cnyid" id= "cnyid" class="form-control" required>
            <option value="">Select Nationality</option>
                <?php
                    while($row=mysqli_fetch_array($nat))
                    {
                ?>
                      <option value="<?php echo $row['num_code']; ?>"> <?php echo $row['nationality'];?> </option>; 
                     <?php } ?>
            </select>
          </div>
        </div>

			<div class="col-12 mb-3">
				<label for="inputState" class="form-label d-block">Your Social
					Security</label> <select id="inputState" name="insurance"
					class="form-select form-control">
                    <?php foreach ($socials as $social): ?>
                       <option value="<?= $social["id"] ?>"><?= $social["ad"]?></option>
                    <?php endforeach; ?>
              </select>
			</div>

			<div class="col-12">
				<label for="inputAddress" class="form-label">Address:</label>
				<textarea class="form-control" name="address" id="" rows="3"
					placeholder="Residence Address..."></textarea>
			</div>
                    </div>
		</div>
		
		<!-- Additional fields for University Supervisor -->

	<div id="uniSupervisorFields" style="display: none;">
    <div class="row">
			<div class="col-3">
				<label for="inputgender" class="form-label d-block">Gender </label>
				<input type="radio" name="gender" value="male"> Male <input
					type="radio" name="gender" value="female"> Female
			</div>

			<div class="col-6">
				<label for="inputphone" class="form-label">Qualification </label> <input
					type="text" class="form-control" id="qualification"
					name="qualification">
			</div>

			<div class="col-3">
				<label for="inputqualif" class="form-label">Telephone No </label> <input
					type="text" class="form-control" id="phone" name="phone"
					placeholder="(5xx)-(xxx)-(xx)-(xx)">
			</div>

			<div class="col-12">
				<label for="inputnotes" class="form-label">Notes:</label>
				<textarea class="form-control" name="notes" id="notes" size="40"
					rows="4"></textarea>
			</div>
		</div>
  </div>

     		<!-- Additional fields for Company Supervisor -->               
	<div id="compSupervisorFields" style="display: none;">
    <div class="row">
			<div class="col-3">
				<label for="inputgender" class="form-label d-block">Gender </label>
				<input type="radio" name="gender" value="male"> Male <input
					type="radio" name="gender" value="female"> Female
			</div>

			<div class="col-6">
				<label for="inputphone" class="form-label">Qualification </label> <input
					type="text" class="form-control" id="qualification"
					name="qualification">
			</div>

			<div class="col-3">
				<label for="inputqualif" class="form-label">Telephone No </label> <input
					type="text" class="form-control" id="phone" name="phone"
					placeholder="(5xx)-(xxx)-(xx)-(xx)">
			</div>

			<div class="col-12">
				<label for="inputnotes" class="form-label">Notes:</label>
				<textarea class="form-control" name="notes" id="notes" size="40"
					rows="4"></textarea>
			</div>
		</div>
	</div>
</div>


</div>
      </form>

      <div class="row">
          <div class="col-3">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-3">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      <a href="../index.php" class="text-center">I already have an account</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>
</body>
</html>
