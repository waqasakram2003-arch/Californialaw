    </main>
  </div>
</div>
<script src="/admin/assets/admin.js" defer></script>
<?php foreach (($pageScripts ?? []) as $src): ?>
<script src="<?= e($src) ?>" defer></script>
<?php endforeach; ?>
<?php if (!empty($pageInlineScript)): ?>
<script><?= $pageInlineScript ?></script>
<?php endif; ?>
</body>
</html>
