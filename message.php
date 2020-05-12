<?php 
    $message = '
        <html>
            <head>
                <title>' . sanitize(__('Order number ####')) . '</title>
            </head>
            <body>
                <p>' . sanitize(__('Hello. A new order from ')) . ' ' . sanitize($name) . '</p>
                <p>' . sanitize(__('Please find the order details below:')) . '</p>
                <table border="1" cellpadding="2">
            <tr>
                <th>' . sanitize( __('Name')) . ' </th>
                <th>' . sanitize(__('Description')) . ' </th>
                <th>' . sanitize(__('Price')) . ' </th>
            </tr> ';

    foreach ($rows as $row) {
        $message .= ' <tr>
                        <td>' . sanitize($row['title']) . '</td>
                        <td>' . sanitize($row['description']) . '</td>
                        <td>' . sanitize($row['price']) . '</td>
                    </tr> ';
        $total += $row['price'];
    }
    $message .= ' 
                     <tr>
                        <td colspan="2" align="middle"><b>' . sanitize(__('Total')) . '</b></td>
                        <td colspan="2"><b>$' . sanitize($total) . '</b></td>
                    </tr>
                    </table>
                <p> ' . sanitize(__('Contact details:')) . ' ' . sanitize($contactDetails) . '</p>
                <p> ' . sanitize(__('Comments:')) . ' ' . sanitize($comments) . '</p>
            </body>
        </html> ';
?>