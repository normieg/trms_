<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="grid gap-6 p-6">
  <!-- KPI Cards -->
  <section aria-labelledby="kpi-heading">
    <h2 id="kpi-heading" class="sr-only">Key Performance Indicators</h2>
    <div id="kpi-cards" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6 gap-4"></div>
    <div id="settings-card" class="mt-4"></div>
  </section>

  <!-- Approval Queue -->
  <section aria-labelledby="approval-heading" class="bg-white rounded-2xl shadow-sm">
    <h2 id="approval-heading" class="text-lg font-semibold p-4 border-b">Pending Purchase Requests</h2>
    <div class="overflow-x-auto">
      <table id="approval-queue" class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase sticky top-0">
          <tr>
            <th class="p-2">PR No</th>
            <th class="p-2">Requester</th>
            <th class="p-2 text-center">Items</th>
            <th class="p-2 text-right">Est. Total</th>
            <th class="p-2">Age</th>
            <th class="p-2 text-center">Action</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </section>

  <!-- Alerts & Flags -->
  <section aria-labelledby="alerts-heading" class="bg-white rounded-2xl shadow-sm">
    <div class="flex items-center justify-between border-b p-4">
      <h2 id="alerts-heading" class="text-lg font-semibold">Alerts &amp; Flags</h2>
      <div id="alert-tabs" class="flex space-x-4 text-sm">
        <button data-filter="all" class="pb-1 border-b-2 border-blue-500">All</button>
        <button data-filter="red" class="pb-1">Red</button>
        <button data-filter="amber" class="pb-1">Amber</button>
        <button data-filter="green" class="pb-1">Green</button>
      </div>
    </div>
    <ul id="alerts-list"></ul>
  </section>

  <!-- Cost Trend -->
  <section aria-labelledby="trend-heading" class="bg-white rounded-2xl shadow-sm">
    <h2 id="trend-heading" class="text-lg font-semibold p-4 border-b">Cost Trend (12 months)</h2>
    <div id="cost-trend" class="p-4 overflow-x-auto"></div>
  </section>

  <!-- Truck Rankings -->
  <section aria-labelledby="rankings-heading" class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <h2 id="rankings-heading" class="sr-only">Truck Rankings</h2>
    <div class="bg-white rounded-2xl shadow-sm">
      <h3 class="text-lg font-semibold p-4 border-b">Highest Cost (last 6 months)</h3>
      <div class="overflow-x-auto">
        <table id="rank-high" class="min-w-full text-sm">
          <thead class="bg-gray-50 text-left text-xs uppercase">
            <tr><th class="p-2">#</th><th class="p-2">Plate</th><th class="p-2">Model</th><th class="p-2 text-right">Cost</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm">
      <h3 class="text-lg font-semibold p-4 border-b">Most Repairs (last 6 months)</h3>
      <div class="overflow-x-auto">
        <table id="rank-repairs" class="min-w-full text-sm">
          <thead class="bg-gray-50 text-left text-xs uppercase">
            <tr><th class="p-2">#</th><th class="p-2">Plate</th><th class="p-2">Model</th><th class="p-2 text-right">Repairs</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Inventory At-a-Glance -->
  <section aria-labelledby="inventory-heading" class="bg-white rounded-2xl shadow-sm">
    <div class="flex items-center justify-between p-4 border-b">
      <h2 id="inventory-heading" class="text-lg font-semibold">Inventory At-a-Glance</h2>
      <button disabled class="px-3 py-1.5 bg-gray-200 text-gray-600 rounded cursor-not-allowed">Go to Inventory</button>
    </div>
    <ul id="low-stock-list"></ul>
  </section>

  <!-- Recent Activity Feed -->
  <section aria-labelledby="activity-heading" class="bg-white rounded-2xl shadow-sm">
    <h2 id="activity-heading" class="text-lg font-semibold p-4 border-b">Recent Activity</h2>
    <ul id="activity-feed"></ul>
  </section>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
