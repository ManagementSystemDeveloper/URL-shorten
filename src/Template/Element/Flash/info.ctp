<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-info" role="alert" onclick="this.classList.add('hidden')"><?= $message ?></div>
