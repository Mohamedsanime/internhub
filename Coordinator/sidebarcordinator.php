  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="/internhub/admin/assets/dist/img/interhublogo.jpg" alt="InterHub Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><b>InterHub</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="assets/dist/img/emulogo2.png" class="img-circle elevation-2" alt="User Image" width="110" height="50">
        </div>
        <div class="info">
          <a href="#" class="d-block"><b>emu.edu.tr</b></a>
          <!-- <a href="#" class="d-block"><p style="color:white;">User Type : {{Auth::user()->type}}</p></a> -->
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="coordinatordata.php" class="nav-link ">
              <i class="fa-solid fa-user-secret"></i>
              <p>
              &nbsp&nbsp Personal Data
              </p>
            </a>
            <a href="insurform.php" class="nav-link">
              <i class="fa-solid fa-users"></i>
              <p>
              &nbsp&nbsp Insurance Forms 
              </p>
            </a>
            <a href="offerslist.php" class="nav-link">
              <i class="fa-solid fa-users"></i>
              <p>
              &nbsp&nbsp Internship Offers 
              </p>
            </a>
            <a href="applicationcor.php" class="nav-link ">
              <i class="fa-solid fa-globe"></i>
              <p>
              &nbsp&nbsp Review Applications
              </p>
            </a>
            <a href="cevaluation.php" class="nav-link">
              <i class="fa-solid fa-landmark"></i>
              <p>
              &nbsp&nbsp Evaluation
              </p>
            </a>
           
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>