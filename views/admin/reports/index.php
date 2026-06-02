<div class="reports-container">
    <h2>Panel Raportów</h2>

    <div class="tabs">
        <button class="tab-button active" onclick="showReport('sales')">Raport Sprzedaży</button>
        <button class="tab-button" onclick="showReport('demographics')">Raport Demograficzny</button>
    </div>

    <div id="sales-report" class="report-section">
        <h3>Sprzedaż w czasie</h3>

        <form id="sales-filters" class="filters-form">
            <label>Od: <input type="date" name="date_from" id="sales_date_from"></label>
            <label>Do: <input type="date" name="date_to" id="sales_date_to"></label>
            <label>Kategoria:
                <select name="category_id">
                    <option value="">Wszystkie</option>
                </select>
            </label>
            <button type="button" onclick="loadSalesData()">Generuj</button>
        </form>

        <div class="charts-grid">
            <div class="chart-wrapper">
                <canvas id="salesTrendChart"></canvas>
            </div>
            <div class="chart-wrapper">
                <canvas id="salesCategoryChart"></canvas>
            </div>
        </div>
    </div>

    <div id="demographics-report" class="report-section" style="display:none;">
        <h3>Analiza Klientów</h3>

        <form id="demo-filters" class="filters-form">
            <label>Produkt/Marka: <input type="text" name="brand_search"></label>
            <button type="button" onclick="loadDemographicsData()">Generuj</button>
        </form>

        <div class="charts-grid">
            <div class="chart-wrapper">
                <canvas id="ageGroupChart"></canvas>
            </div>
            <div class="chart-wrapper">
                <canvas id="genderChart"></canvas>
            </div>
            <div class="chart-wrapper">
                <canvas id="cityChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/reports.js"></script>
