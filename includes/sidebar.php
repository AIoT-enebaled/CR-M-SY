 <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
     <div class="app-brand demo">
         <a href="dashboard.php" class="app-brand-link">
             <span class="app-brand-logo">
                 <img src="./assets/img/logo.jpg" width="60" height="60" />
             </span>

         </a>

         <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
             <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
             <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
         </a>
     </div>

     <div class="menu-inner-shadow"></div>

     <ul class="menu-inner py-1">
         <!-- Dashboards -->
         <li class="menu-item active open">
             <a href="dashboard.php" class="menu-link">
                 <i class="menu-icon tf-icons ti ti-smart-home"></i>
                 <div data-i18n="Dashboards">Dashboards</div>
             </a>

         </li>

         <!-- Layouts -->
         <li class="menu-item">
             <a href="./crime_reports.php" class="menu-link">
                 <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
                 <div data-i18n="Crime Reports">Crimes Reports</div>
             </a>
         </li>
         <?php
            if ($_SESSION["role"] == 'admin') {
            ?>
             <li class="menu-item">
                 <a href="./police_stations.php" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-home"></i>
                     <div data-i18n="Police Stations">Police Stations</div>
                 </a>
             </li>
             <li class="menu-item open">
                 <a href="./members.php" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-users"></i>
                     <div data-i18n="Police Members">Police Members</div>
                 </a>

             </li>
         <?php
            }
            ?>


     </ul>
 </aside>