<?php require "views/layouts/header.php"; ?>

<div class="dashboard">

<?php require "views/layouts/sidebar.php"; ?>

<main class="main-content">

<header class="dashboard-header d-flex justify-content-between align-items-center">

<div>

<h1 class="h3 fw-bold">
Panel de gestión
</h1>

<p class="text-muted mb-0">
Resumen estratégico de tu carpintería
</p>

</div>

<div class="dashboard-date">

<i class="bi bi-calendar3"></i>
<?= date("d M Y") ?>

</div>

</header>


<section class="dashboard-body container-fluid">

<?php require "views/dashboard/financial-panel.php"; ?>

<?php require "views/dashboard/progress-panel.php"; ?>

<div class="row mt-4">

<div class="col-lg-8">
<?php require "views/dashboard/activity-panel.php"; ?>
</div>

<div class="col-lg-4">
<?php require "views/dashboard/alerts-panel.php"; ?>
</div>

</div>

<?php require "views/dashboard/actions-panel.php"; ?>

</section>

</main>

</div>

<?php require "views/layouts/footer.php"; ?>