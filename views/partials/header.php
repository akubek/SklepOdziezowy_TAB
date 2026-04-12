<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nasz sklep mvc</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.php?page=home">
        <span class="text-primary">sklep</span>odzieżowy
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarnav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarnav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" href="index.php?page=home">strona główna</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=catalog">katalog</a>
          </li>
        </ul>

        <div class="d-flex align-items-center">
          <a href="index.php?page=cart" class="btn btn-outline-light me-2">
            koszyk <span class="badge bg-primary">0</span>
          </a>
          <a href="index.php?page=login" class="btn btn-primary">zaloguj</a>
        </div>
      </div>
    </div>
  </nav>
</header>

<main class="container mt-4"> 
