<?php
  #error_reporting(E_ALL);
  $conn = mysqli_connect("localhost", "root", "","internship");
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $sql = "SELECT id,role_name From roles where id > 1";
  $result=mysqli_query($conn,$sql);
  $nat = mysqli_query($conn, 'SELECT num_code, nationality From countries order by nationality');
  $org = mysqli_query($conn, 'SELECT id, name From companies order by name');

  // Function to sanitize user input
  function sanitize_input($data)
  {
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    return $data;
  }

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 // print_r($_POST);
  // Sanitize and validate user input
  $name = sanitize_input($_POST["name"]);
  $surname = sanitize_input($_POST["surname"]);
  $email = sanitize_input($_POST["email"]);
  $password = sanitize_input($_POST["password"]);
  $rol_id = sanitize_input($_POST["rol_id"]);

  $student_id = sanitize_input($_POST["student_id"]);
  $gender = sanitize_input($_POST["gender"]);
  $mobile = sanitize_input($_POST["mobile"]);
  $qualification = sanitize_input($_POST["qualification"]);
  $address = sanitize_input($_POST["address"]);
  $cny = intval($_POST['cny']);

  $cqualification = sanitize_input($_POST["cqualification"]);
  $cphone = sanitize_input($_POST["cphone"]);
 // $cgender = sanitize_input($_POST["cgender"]);
  $caddress = sanitize_input($_POST["caddress"]);
  $cnotes = sanitize_input($_POST["cnotes"]);

  $squalification = sanitize_input($_POST["squalification"]);
  $sphone = sanitize_input($_POST["sphone"]);
 // $sgender = sanitize_input($_POST["sgender"]);
  $saddress = sanitize_input($_POST["saddress"]);
  $snotes = sanitize_input($_POST["snotes"]);

  #$phone = sanitize_input($_POST["phone"]);
  #$notes = sanitize_input($_POST["notes"]);
  $cny = intval($_POST['cny']);
  $org_id = intval($_POST['org_id']);
  $active = 0;

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo "Invalid email format";
      exit();
  }

  // Validate gender input (M or F)
  if ($gender != "M" && $gender != "F") {
      echo "Invalid gender format";
      exit();
  }

  // Validate mobile number format (optional)
  // ... (Implement regular expression or other validation for mobile number)

  // Hash the password before storing it
  $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute SQL queries with prepared statements
        $sql_user = "INSERT INTO users (name, surname, email, passwordHash, rol_id)
        VALUES (?, ?, ?, ?, ?)";
  $stmt_user = $conn->prepare($sql_user);
  $stmt_user->bind_param("ssssi", $name, $surname, $email, $passwordHash, $rol_id);
  $stmt_user->execute();

  if ($stmt_user->affected_rows > 0) {
      $user_id = $conn->insert_id;
      switch ($rol_id) {
        case 4: // Students
            $sql_student = "INSERT INTO students (student_id, gender, mobile, qualification, active, address, user_id, cny)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_student);
            $stmt->bind_param("ssssisii", $student_id, $gender, $mobile, $qualification, $active, $address, $user_id, $cny);
            break;
        case 2: // Coordinator
            $sql_coordinator = "INSERT INTO coordinator (gender, phone, qualification, active, address, notes, user_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_coordinator);
            $stmt->bind_param("sssissi", $gender, $cphone, $cqualification, $active, $caddress, $cnotes, $user_id);
            break;
        case 3: // Supervisor
            $sql_supervisor = "INSERT INTO supervisor (gender, phone, qualification, active, address, notes, user_id, org_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_supervisor);
            $stmt->bind_param("sssissii", $gender, $sphone, $squalification, $active, $saddress, $snotes, $user_id, $org_id);
            break;
        }
        $stmt->execute();



  $stmt_user->close();
  $stmt->close();
 }
}
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
        <a  class="h2"><b>Sign Up a new account</b></a>
      </div>
      <div class="card-body">
        <form action="register.php" method="post" novalidate>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="label" for="name">First Name </label>
                <input type="text" class="form-control" name="name" id="name" placeholder="First name">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="label" for="surname">Last Name </label>
                <input type="text" class="form-control" name="surname" id="surname" placeholder="Last name">
              </div>
            </div>
            <div class="col-md-4"> 
              <div class="form-group">
                <label class="label" for="email">Email Address</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
              </div>
            </div> 
            <div class="col-md-3">
                <div class="form-group">
				            <label for="gender" class="form-label d-block">Gender: </label>
				            <input type="radio" name="gender" value="M"> Male 
				            <input type="radio" name="gender" value="F"> Female
			          </div>
            </div>

            <div class="col-md-6"> 
              <div class="form-group">
                <label for="role">Account Type</label>
                <select name= "rol_id" id= "rol_id" class="form-control" required onchange="roleChanged(this.value)">
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
                <label class="label" for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
              </div>
            </div>
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
				            <input type="text" class="form-control" name="student_id" id="student_id">
			            </div>
                </div>
			          <div class="col-6">
				          <label for="qualification" class="form-label">Qualification: </label> 
				          <input type="text" class="form-control" id="qualification" name="qualification">
			          </div> 

			          <div class="col-6">
                  <div class="form-group">
				            <label for="mobile" class="form-label">Phone No: </label> 
				            <input type="text" class="form-control" id="mobile" name="mobile" pattern="[0-9]{10,13}" placeholder="(5xx)-(xxx)-(xx)-(xx)">
			            </div>
                </div>
                <div class="col-md-6"> 
                  <div class="form-group">
                    <label for="role">Nationality</label>
                    <select name= "cny" id= "cny" class="form-control" required>
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
			          <div class="col-12">
				          <label for="address" class="form-label">Address:</label>
				          <textarea class="form-control" name="address" id="address" rows="3"
					          placeholder="Student Residence Address..."></textarea>
			          </div>
              </div>
		        </div>
		          <!-- Additional fields for University Coordinator -->
	          <div id="uniSupervisorFields" style="display: none;">
              <div class="row">
               <!-- <div class="col-3">
                  <label for="gender" class="form-label d-block">Gender </label>
                  <input type="radio" name="gender" value="M"> Male <input
                    type="radio" name="gender" value="F"> Female
                </div> -->

                <div class="col-6">
                  <label for="cqualification" class="form-label">Qualification </label> <input
                    type="text" class="form-control" id="cqualification" name="cqualification">
                </div>
               <!-- <div class="col-6">
                  <label for="qualification" class="form-label">Qualification </label> <input
                    type="text" class="form-control" id="qualification" name="qualification">
                </div> -->

                <div class="col-3">
                  <label for="cphone" class="form-label">Telephone No </label> <input
                    type="text" class="form-control" id="cphone" name="cphone"
                    placeholder="(5xx)-(xxx)-(xx)-(xx)">
                </div>

                <div class="col-12">
				          <label for="caddress" class="form-label">Address:</label>
				          <textarea class="form-control" name="caddress" id="caddress" rows="3"
					          placeholder="Student Residence Address..."></textarea>
			          </div>
              </div>

                <div class="col-12">
                  <label for="cnotes" class="form-label">Notes:</label>
                  <textarea class="form-control" name="cnotes" id="cnotes" size="40"
                    rows="4"></textarea>
                </div>
              </div>
            </div>
     		      <!-- Additional fields for Supervisor -->               
            <div id="compSupervisorFields" style="display: none;">
              <div class="row">
                <!-- <div class="col-3">
                  <label for="gender" class="form-label d-block">Gender </label>
                  <input type="radio" name="gender" value="M"> Male <input
                         type="radio" name="gender" value="F"> Female
                </div> -->

                <div class="col-6">
                  <label for="squalification" class="form-label">Qualification </label> <input
                    type="text" class="form-control" id="squalification" name="squalification">
                </div>

                <div class="col-3">
                  <label for="sphone" class="form-label">Telephone No </label> <input
                    type="text" class="form-control" id="sphone" name="sphone"
                    placeholder="(5xx)-(xxx)-(xx)-(xx)">
                </div>

                <div class="col-md-6"> 
                  <div class="form-group">
                    <label for="role">Company</label>
                    <select name= "org_id" id= "org_id" class="form-control" required>
                      <option value="">Select Company</option>
                          <?php
                              while($row=mysqli_fetch_array($org))
                              {
                          ?>
                                <option value="<?php echo $row['id']; ?>"> <?php echo $row['name'];?> </option>; 
                              <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="col-12">
				          <label for="saddress" class="form-label">Address:</label>
				          <textarea class="form-control" name="saddress" id="saddress" rows="3"
					          placeholder="Student Residence Address..."></textarea>
			          </div>
              </div>

                <div class="col-12">
                  <label for="snotes" class="form-label">Notes:</label>
                  <textarea class="form-control" name="snotes" id="snotes" size="40"
                    rows="4"></textarea>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="icheck-primary">
                <input type="checkbox" id="agreeTerms" name="terms" value="agree" required>
                <label for="agreeTerms">
                I agree to the <a href="#">terms</a>
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-3">
              <button type="submit" class="btn btn-primary btn-block">Register</button>
                <a href="../index.php">Login</a>
            </div>
          </div>
      </form>
    </div>
  </div>


  <!-- jQuery -->
  <script src="assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="assets/dist/js/adminlte.min.js"></script>
</body>
</html>
