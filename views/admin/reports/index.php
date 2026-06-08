<div class="container my-4">
    <h2 class="mb-4"><i class="bi bi-graph-up text-primary"></i> Panel Raportów</h2>

    <ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales-report" type="button" role="tab" aria-selected="true">
                Raport Sprzedaży
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="demo-tab" data-bs-toggle="tab" data-bs-target="#demographics-report" type="button" role="tab" aria-selected="false">
                Raport Demograficzny
            </button>
        </li>
    </ul>

    <div class="tab-content" id="reportTabsContent">
        <?php require BASE_PATH . '/views/admin/reports/partials/sales_tab.php'; ?>
        <?php require BASE_PATH . '/views/admin/reports/partials/demo_tab.php'; ?>
    </div>
</div>

<div id="reports-data-payload" class="d-none"
    data-active-tab="<?= e($active_tab) ?>"
    data-sales="<?= e(json_encode($salesData ?? [])) ?>"
    data-demo="<?= e(json_encode($demoData ?? [])) ?>">
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1"></script>
<script src="/js/reports/tree.js"></script>
<script src="/js/reports/form.js"></script>
<script src="/js/reports/render.js"></script>
