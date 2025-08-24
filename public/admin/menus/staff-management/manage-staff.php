<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
$csrf = $_SESSION['csrf'];

$pageTitle = 'Staff Management';

require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/sidebar.php';
?>

<div class="md:pl-72 bg-slate-50 min-h-screen">
    <main
        role="main"
        class="max-w-7xl mx-auto md:max-w-none md:mx-0 px-4 sm:px-6 lg:px-8 py-6">
        <div class="relative">
            <div class="flex items-center w-full">
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                    <?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?>
                </h1>
            </div>

            <div class="absolute right-4 top-12 h-full flex items-center md:fixed md:right-6 md:top-16 md:h-auto md:z-50">
                <button
                    id="open-add-staff"
                    type="button"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                    </svg>
                    Register Staff
                </button>
            </div>
        </div>

        <div class="h-12 md:h-20" aria-hidden="true"></div>
        <div>
            <section class="mt-6">
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-slate-600">List of staff, filters, and actions will go here.</p>
                </div>
            </section>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../../../modals/user-registration-modal.php'; ?>

<script src="/trms/public/assets/js/sidebar-toggle.js"></script>