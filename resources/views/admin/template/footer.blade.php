  <?php
    use App\Models\Resources\Company\Company;
    $company = Company::first();
  ?>
<footer class="app-footer bg-light border-top py-3 px-4 mt-auto shadow-sm">
  <div class="container-fluid d-flex flex-column flex-sm-row justify-content-between align-items-center">
    
    <div class="text-muted small text-center text-sm-start mb-2 mb-sm-0">
      <strong>&copy; 2025</strong>
      <a class="text-decoration-none ms-1">{{ $company->name }}</a>.
      All rights reserved.
    </div>

    <div class="text-muted small text-center text-sm-end">
      <i class="bi bi-heart-fill text-danger"></i> Built with care
    </div>
    
  </div>
</footer>
