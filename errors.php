<?php if (isset($errors[$errorKey])) : ?>
        <?php foreach ($errors[$errorKey] as $error) : ?>
            <p class="text-danger"><?= $error ?></p>
        <?php endforeach ?>>
<?php endif ?>