<html>

<head>
    <title><?= __('Order number ####') ?></title>
</head>

<body>
    <p><?= __('Hello. A new order from ') . ' ' . $name ?></p>
        <p><?=__('Please find the order details below:') ?></p>
            <table border="1" cellpadding="2">
                <tr>
                    <th><?=__('Name') ?></th>
                    <th><?=__('Description') ?></th>
                    <th><?=__('Price') ?></th>
                </tr>
            <?php foreach ($rows as $row) : ?>
                <tr>
                    <td><?=$row['title'] ?></td>
                    <td><?=$row['description'] ?></td>
                    <td><?=$row['price'] ?></td>
                </tr>
            <?php
            $total += $row['price'];
            endforeach; ?>
                <tr>
                    <td colspan="2" align="middle"><b><?=__('Total') ?></b></td>
                    <td colspan="2"><b>$<?=$total ?></b></td>
                </tr>
            </table>
            <p> <?=__('Contact details:') . ' ' .$contactDetails ?></p>
            <p> <?=__('Comments:') . ' ' .$comments ?></p>
</body>

</html>