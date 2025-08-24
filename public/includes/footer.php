</main>
</div>
<footer class="text-center text-xs text-gray-500 p-4">&copy; 2025 TRMS</footer>
<?php $assetPath = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) ? '..' : '.'; ?>
<script type="module" src="<?= $assetPath ?>/assets/js/utils.js"></script>
<script type="module" src="<?= $assetPath ?>/assets/js/mockApi.js"></script>
<script type="module" src="<?= $assetPath ?>/assets/js/dashboard.js"></script>
</body>
</html>
