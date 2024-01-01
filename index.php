<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Internship Management System</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/internhub/admin/assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">,
  <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <script src="https://kit.fontawesome.com/75e0b79c22.js" crossorigin="anonymous"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="/internhub/admin/assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/internhub/admin/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="/internhub/admin/assets/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/internhub/admin/assets/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/internhub/admin/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="/internhub/admin/assets/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="/internhub/admin/assets/plugins/summernote/summernote-bs4.min.css">
  <link rel="stylesheet" href="/internhub/admin/assets/dist/css/main.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
 
  <!-- Theme style -->

  <script src="https://kit.fontawesome.com/1f952dc3e7.js" crossorigin="anonymous"></script>

  <style>
        body {
        background-image: url('/internhub/admin/assets/dist/img/bg3.png');
        background-size: cover;
    }
    </style>

</head>

<body>
 
    <div class="container mt-3">
        <div class="header d-flex flex-row justify-content-between align-items-center ">
            <a href="index.html" class="header_logo d-inline-flex text-decoration-none align-items-center text-white">
                <img src="admin/assets/dist/img/emulogo3.png" alt="" class="brand-image img-circle elevation-3" style="opacity: .7" />
                <div class="ms-2">
                    <h1 class="text-uppercase fw-bold fs-6">
                    <font color="#0000CD"> <h5>EASTERN MEDITERRANEAN UNIVERSITY </h5> </font>
                    </h1>
                    <span class="text-capitalize"> <font color="#0000CD"> <b><h6> SCHOOL OF COMPUTING AND TECHNOLOGY </h6></b> </font> </span>
                </div>
            </a>
            <nav class="menu">
                <ul class="d-flex list-unstyled">
                <li class="nav-item">
                        <a href="admin/register.php" class="nav-link text-white"> <font color="#0000CD"><b> Sign Up </b></font> </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin/forgotpwd.php" class="nav-link text-white"> <font color="#0000CD""><b> Forgot Password? </b></font> </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin/changepwd.php" class="nav-link text-white"> <font color="#0000CD""><b> Change Password </b></font></a>
                    </li>
                    <li class="nav-item">
                        <a href="admin/contact.php" class="nav-link text-white"> <font color="#0000CD"><b> Contact Us </b></font> </a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>

    <div class="container">
        <div class="login-section d-flex align-items-center justify-content-center my-1">

                <div class="card px-2 py-3" style="width: 22rem;">
                    <div class="card-body">
<?php
                        ob_start(); // Start output buffering
                        require ("config.php");
                       
                        if (isset($_POST["email"]) || isset($_POST["password"]) ){ 
                            $email= $_POST["email"];
                            $password= md5($_POST["password"]);
                            # alert("{$email}");
                            $query = $db->prepare("SELECT users.id,name,surname,email,role_name 
                                    FROM users INNER JOIN roles ON users.rol_id = roles.id 
                                    WHERE email=:vemail AND passwordHash=:vpasswordHash");
                            $query->execute([
                                    "vemail" =>$email,
                                    "vpasswordHash" =>$password
                            ]);
                            $data = $query->fetch(PDO::FETCH_ASSOC);

                            $control = $query->rowCount();
                            $role_name = $data['role_name'];
                            #print_r($data);

                            if($control==0){
                                // Redirect If Login Failed;
                                echo("Login Failed ");
                                header("Location:index.php");
                            }else{
                              
                                echo("Login In ");
                                
                                
                                $_SESSION["users"] = $data;
                                $_SESSION["login"] = true;

                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $data['id']; // Store user ID
                                $_SESSION["username"] = $data['email']; // Store username
                                $_SESSION["role_name"] = $role_name;
                                //header("Location:admin/index.php");
                                        // Debugging
                                        //echo "<pre>";
                                        //print_r($_SESSION);
                                        //echo "</pre>";
                                        //exit; // Temporary exit to check output
                                switch ($role_name){
                                    case 'Administrator':
                                        echo '<script type="text/javascript">';
                                        echo 'window.location.href="admin/index.php";';
                                        echo '</script>';
                                        break;
                                    case 'Coordinator':
                                        echo '<script type="text/javascript">';
                                        echo 'window.location.href="coordinator/coordinatordata.php";';
                                        echo '</script>';
                                    case 'Supervisor':
                                        echo '<script type="text/javascript">';
                                        echo 'window.location.href="company/supervisordata.php";';
                                        echo '</script>';
                                case 'Student':
                                        echo '<script type="text/javascript">';
                                        echo 'window.location.href="student/studentdata.php";';
                                        echo '</script>';
                                }
                                

                               // include($_SERVER["DOCUMENT_ROOT"].'/internhub/admin/includes/header.php');
                                //<meta http-equiv="Location" content="admin/index.php">

                                //echo "<script type='text/javascript'>alert('{$data["role_name"]}');</script>"; 
                            }
                           
                        }
                        if (isset($_POST["register"]) )
                        {
                            header("admin/register.php");
                        }


                        //print_r($data);
                        ?>
                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                            <div class="card-header text-center">
                                <a href="../../index2.html" class="h3"><b>INTERN</b>HUB</a>
                            </div>
                            <div class="mb-3 d-flex justify-content-center">
                                <img src="admin/assets/dist/img/interhublogo.jpg" alt="" class="" width="125" height="125">                   
                            </div>
                            <div class="mb-3 d-flex justify-content-center">
                                <h1 class="text-uppercase fw-bold fs-6">
                                    <font color="#9900FF"> <h5>Internship Management System </h5></font>
                                </h1>
                    
                            </div>
                            <div class="mb-3">
                                <label for="mail" class="form-label"><b>Email address: </b></label>
                                <input type="text" class="form-control" id="mail" name="email" required
                                       placeholder="123456@emu.edu.tr">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"><b>Password:</b></label>
                                <input type="password" class="form-control" id="password" name="password" required
                                       placeholder="Enter Password">
                            </div>
                            <button type="submit"  class="btn btn-primary" name="login">Login</button>
                            <!-- <button type="submit"  class="btn btn-primary" name="register" style="margin-left:145px;">Register</button> -->
                        </form>

                    </div>
                </div>

        </div>
    </div>


    <div class="container">
        <div class="footer">
           <p style="color:blue"> <span class="text-white small"><center><b>Mohamed Sanime Amira, Ahmad Zyadoun Alkhatib, Allwell Dennis Ufot</b></center></span> </p>
        </div>
    </div>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>