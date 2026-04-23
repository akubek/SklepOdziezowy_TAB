<div class="container my-5 py-5 text-center">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h1 class="display-1 fw-bold text-secondary"><?= $errorCode ?? '404' ?></h1>
            <h2 class="mb-4"><?= $title ?? 'Oops! Strona nie znaleziona.' ?></h2>
            <p class="lead text-muted mb-5">
                <?= $message ?? 'Wygląda na to, że zgubiłeś się w naszym sklepie. Strona, której szukasz, nie istnieje lub została przeniesiona.' ?>
            </p>
            <a href="index.php?page=home" class="btn btn-primary btn-lg px-4 shadow-sm">
                Wróć na stronę główną
            </a>
        </div>
    </div>
</div>
