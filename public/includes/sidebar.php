<?php
// Preserve your original $base logic
$base = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) ? '..' : '.';

// Determine the current page for active link styles
$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) ?: 'dashboard.php';

// Reusable classes for nav links
$navBase      = 'group flex items-center gap-3 px-3 py-2 rounded-lg transition hover:bg-white/10 focus:outline-none focus-visible:ring-2 ring-white/50';
$navActive    = 'bg-white/15 text-white';
$navInactive  = 'text-white/90';

// Is the current page inside the Users group?
$isUsersSection = in_array($currentPage, ['mechanics.php', 'manage-staff.php'], true);

require_once __DIR__ . '/init.php';
?>

<div class="h-screen overflow-hidden bg-slate-50 text-slate-900 md:flex">
  <!-- Mobile topbar -->
  <header class="sticky top-0 z-40 flex items-center justify-between gap-2 bg-white/80 backdrop-blur border-b border-slate-200 px-4 py-3 md:hidden">
    <button id="sidebar-open" class="inline-flex items-center gap-2 rounded-md border border-slate-300 px-3 py-2 text-sm font-medium shadow-sm hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
      <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
      Menu
    </button>

    <div class="flex items-center gap-2">
      <img src="/trms/public/assets/images/filstarlogo.png" alt="FilStar logo" class="h-7 w-7 rounded" />
      <span class="text-lg font-semibold tracking-tight">FilStar</span>
    </div>
  </header>

  <!-- Backdrop for mobile drawer -->
  <div id="backdrop" class="fixed inset-0 z-30 hidden bg-slate-900/50 backdrop-blur-sm md:hidden" aria-hidden="true"></div>

  <!-- Sidebar -->
  <aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-72 transform -translate-x-full bg-gradient-to-b from-blue-900 to-blue-600 text-white shadow-2xl ring-1 ring-white/10 transition-transform duration-300 ease-in-out
          md:translate-x-0 md:shadow-none"
    aria-label="Sidebar">

    <div class="flex flex-col h-full justify-between">

      <!-- Sidebar header -->
      <div class="flex items-center justify-between px-4 pt-4">
        <div class="flex items-center gap-2">
          <img src="/trms/public/assets/images/filstarlogo.png" alt="FilStar logo" class="h-9 w-9 rounded bg-white/10 p-1 ring-1 ring-white/20" />
          <span class="text-2xl font-bold">FilStar</span>
        </div>
        <!-- Close button (mobile) -->
        <button id="sidebar-close" class="md:hidden inline-flex items-center justify-center rounded-md p-2 hover:bg-white/10 focus:outline-none focus-visible:ring-2 ring-white/50" aria-label="Close sidebar">
          <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Nav -->
      <nav class="mt-6 px-3 pb-6 space-y-1" role="navigation">
        <a href="/trms/public/admin/menus/admin-dashboard/admin-dashboard.php"
          class="<?= $navBase . ' ' . ($currentPage === 'admin-dashboard.php' ? $navActive : $navInactive) ?>"
          aria-current="<?= $currentPage === 'admin-dashboard.php' ? 'page' : 'false' ?>">
          <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z" />
          </svg>
          <span>Dashboard</span>
        </a>

        <a href="#"
          class="<?= $navBase . ' ' . ($currentPage === 'trucks.php' ? $navActive : $navInactive) ?>">
          <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h13l5 5v5h-3a3 3 0 11-6 0H9a3 3 0 11-6 0H2V9a2 2 0 011-2z" />
          </svg>
          <span>Trucks</span>
        </a>

        <a href="#"
          class="<?= $navBase . ' ' . ($currentPage === 'purchases.php' ? $navActive : $navInactive) ?>">
          <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18M6 7h12l1 12a2 2 0 01-2 2H7a2 2 0 01-2-2l1-12zM9 11h6" />
          </svg>
          <span>Purchase Requests</span>
        </a>

        <a href="#"
          class="<?= $navBase . ' ' . ($currentPage === 'work-orders.php' ? $navActive : $navInactive) ?>">
          <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6M9 11h6M9 15h6M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
          <span>Work Orders</span>
        </a>

        <a href="#"
          class="<?= $navBase . ' ' . ($currentPage === 'reports.php' ? $navActive : $navInactive) ?>">
          <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6h6v6M8 3h8a2 2 0 012 2v2H6V5a2 2 0 012-2zM6 9h12v8a2 2 0 01-2 2H8a2 2 0 01-2-2V9z" />
          </svg>
          <span>Reports</span>
        </a>

        <a href="#"
          class="<?= $navBase . ' ' . ($currentPage === 'settings.php' ? $navActive : $navInactive) ?>">
          <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6h4l1 2 2 1v4l-2 1-1 2h-4l-1-2-2-1V9l2-1 1-2z" />
            <circle cx="12" cy="12" r="3" />
          </svg>
          <span>Settings</span>
        </a>

        <!-- Collapsible group: Users -->
        <div class="pt-2">
          <button id="people-toggle"
            class="w-full <?= $navBase ?> <?= $isUsersSection ? $navActive : $navInactive ?> justify-between"
            aria-expanded="<?= $isUsersSection ? 'true' : 'false' ?>"
            aria-controls="people-menu">
            <span class="inline-flex items-center gap-3">
              <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <span>Users</span>
            </span>
            <svg id="people-caret" class="h-4 w-4 transition-transform <?= $isUsersSection ? 'rotate-180' : '' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>

          <ul id="people-menu" class="mt-1 ml-11 space-y-1 <?= $isUsersSection ? '' : 'hidden' ?>">
            <li>
              <a href="#"
                class="<?= $navBase . ' ' . ($currentPage === 'mechanics.php' ? $navActive : $navInactive) ?> pl-0">
                <svg class="h-4 w-4 opacity-90 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232a4 4 0 01-5.464 5.464l-4.536 4.536a2 2 0 102.828 2.828l4.536-4.536a4 4 0 015.464-5.464z" />
                </svg>
                <span>Mechanics</span>
              </a>
            </li>
            <li>
              <a href="/trms/public/admin/menus/staff-management/manage-staff.php"
                class="<?= $navBase . ' ' . ($currentPage === 'manage-staff.php' ? $navActive : $navInactive) ?> pl-0">
                <svg class="h-4 w-4 opacity-90 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Staff</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Logout (bottom) -->
      <div class="px-3 py-4 mt-auto border-t border-white/10">
        <a href="#" id="logout-open"
          class="group flex items-center gap-3 px-3 py-2 rounded-lg transition hover:bg-white/10 focus:outline-none focus-visible:ring-2 ring-white/50">
          <svg class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16v1a2 2 0 002 2h6a2 2 0 002-2v-1" />
          </svg>
          <span>Logout</span>
        </a>
      </div>
    </div>
  </aside>


  <script src="/trms/public/assets/js/sidebar-toggle.js"></script>
  <script src="/trms/public/assets/js/logout-modal.js"></script>
  <!-- Logout modal backdrop & container -->
  <div id="logout-backdrop" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm"></div>
  <div id="logout-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="max-w-md w-full">
      <?php include __DIR__ . '/../modals/logout-modal.php'; ?>
    </div>
  </div>