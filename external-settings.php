<h1>Feedback List</h1>

<a href="http://google.com">Helpline</a>
<?php
global $wpdb;
$result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mrk_feedback");

?>
<table border="1" cellpadding="4" cellspacing="4">
    <tr>
        <th>Date</th>
        <th>Name</th>
        <th>Email</th>
        <th>Message</th>
    </tr>
    <?php foreach ($result as $item): ?>
    <tr>
        <td><?php echo date('d M, Y', strtotime($item->time)) ?></td>
        <td><?php echo $item->name ?></td>
        <td><?php echo $item->email ?></td>
        <td><?php echo $item->message ?></td>
    </tr>
    <?php endforeach; ?>
</table>