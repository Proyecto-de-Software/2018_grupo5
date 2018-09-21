<nav class="navbar navbar-expand-md navbar-dark static-top bg-primary">
  <li class="navbar-brand"><img src="resources/img/hospital-icon.png" alt="icono de un hospital" ></li>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"    aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Consultas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Pacientes</a>
      </li>
      <!-- Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">Administración</a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="#">Usuarios</a>
          <a class="dropdown-item" href="#">Roles</a>
          <a class="dropdown-item" href="#">Permisos</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Configuracion</a>
        </div>
      </li>
    </ul>
    <form class="form-inline mt-2 mt-md-0">
      <input class="form-control mr-sm-2" type="text" placeholder="Buscar..." aria-label="Search" size="50">
      <button class="btn btn-outline-light my-2 my-sm-0" type="submit" >Buscar</button>
    </form>
    <ul class="nav navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="#">Login</a>
      </li>
    </ul>   
  </div>
</nav>