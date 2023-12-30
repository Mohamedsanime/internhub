<?php
include($_SERVER["DOCUMENT_ROOT"].'/internhub/admin/includes/header.php');
#echo get_include_path();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <script src="https://kit.fontawesome.com/75e0b79c22.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="internhub/admin/assets/dist/css/main.css">

    <title>Internship Management System</title>


</head>

<body>
 
    <div class="container mt-3">
        <div class="header d-flex flex-row justify-content-between align-items-center ">
            <a href="index.html" class="header_logo d-inline-flex text-decoration-none align-items-center text-white">
                <img src="admin/assets/dist/img/emulogo3.png" alt="" class="brand-image img-circle elevation-3" style="opacity: .7" />
                <div class="ms-2">
                    <h1 class="text-uppercase fw-bold fs-6">
                       Eastern Mediterraneen University
                    </h1>
                    <span class="text-capitalize">Internship Management System</span>
                </div>
            </a>
            <nav class="menu">
                <ul class="d-flex list-unstyled">
                <li class="nav-item">
                        <a href="admin/register.php" class="nav-link text-white">Sign Up</a>
                    </li>
                    <li class="nav-item">
                        <a href="admin/forgotpwd.php" class="nav-link text-white">Forgot Password?</a>
                    </li>
                    <li class="nav-item">
                        <a href="admin/changepwd.php" class="nav-link text-white">Change Password</a>
                    </li>
                    <li class="nav-item">
                        <a href="admin/contact.php" class="nav-link text-white">Contact Us </a>
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
                            //print_r($data);

                            if($control==0){
                                // Redirect If Login Failed;
                                echo("Login Failed ");
                                header("Location:index.php");
                            }else{
                              
                                echo("Login In ");
                                session_start();
                                
                                $_SESSION["users"] = $data;
                                $_SESSION["login"] = true;
                                header("Location:admin/index.php");

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
                            <div class="mb-3 d-flex justify-content-center">
                                <img src="admin/assets/dist/img/interhublogo.jpg" alt="" class="" width="125" height="125">
                            </div>
                            <div class="mb-3">
                                <label for="mail" class="form-label">Email address:</label>
                                <input type="text" class="form-control" id="mail" name="email" required
                                       placeholder="123456@emu.edu.tr">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required
                                       placeholder="Enter Password">
                            </div>
                            <button type="submit"  class="btn btn-primary" name="login">Login</button>
                            <button type="submit"  class="btn btn-primary" name="register" style="margin-left:145px;">Register</button>
                        </form>

                    </div>
                </div>

        </div>
    </div>


    <div class="container">
        <div class="footer">
            <span class="text-white small"><center>Mohamed Sanime Amira, Ahmad Zyadoun Alkhatib, Allwell Dennis Ufot</center></span>
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