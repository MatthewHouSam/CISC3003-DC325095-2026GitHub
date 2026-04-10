<?php
include 'includes/book-utilities.inc.php';

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$customers = readCustomers('data/customers.txt');
$selectedCustomerId = isset($_GET['id']) ? trim($_GET['id']) : '';
$selectedCustomer = null;
$orders = [];

if ($selectedCustomerId !== '') {
    foreach ($customers as $customer) {
        if ($customer['id'] === $selectedCustomerId) {
            $selectedCustomer = $customer;
            break;
        }
    }

    if ($selectedCustomer !== null) {
        $orders = readOrders($selectedCustomerId, 'data/orders.txt');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>dc325095 CHUNG HOU SAM</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/material.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/demo-styles.css">

    <script src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="js/material.min.js"></script>
    <script src="js/jquery.sparkline.2.1.2.js"></script>
</head>
<body>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <?php include 'includes/header.inc.php'; ?>
    <?php include 'includes/left-nav.inc.php'; ?>

    <main class="mdl-layout__content mdl-color--grey-50">
        <section class="page-content">
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--7-col card-lesson mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title mdl-color--orange">
                        <h2 class="mdl-card__title-text">Customers</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <table class="mdl-data-table mdl-shadow--2dp">
                            <thead>
                            <tr>
                                <th class="mdl-data-table__cell--non-numeric">Name</th>
                                <th class="mdl-data-table__cell--non-numeric">University</th>
                                <th class="mdl-data-table__cell--non-numeric">City</th>
                                <th>Sales</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td class="mdl-data-table__cell--non-numeric">
                                        <a href="cisc3003-sugex10-after.php?id=<?php echo urlencode($customer['id']); ?>">
                                            <?php echo e($customer['firstname'] . ' ' . $customer['lastname']); ?>
                                        </a>
                                    </td>
                                    <td class="mdl-data-table__cell--non-numeric"><?php echo e($customer['university']); ?></td>
                                    <td class="mdl-data-table__cell--non-numeric"><?php echo e($customer['city']); ?></td>
                                    <td><span class="inlinesparkline"><?php echo e(str_replace(' ', '', $customer['sales'])); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mdl-grid mdl-cell--5-col">
                    <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                            <h2 class="mdl-card__title-text">Customer Details</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <?php if ($selectedCustomer !== null): ?>
                                <h3 class="customer-detail-name"><?php echo e($selectedCustomer['firstname'] . ' ' . $selectedCustomer['lastname']); ?></h3>
                                <p>Email: <?php echo e($selectedCustomer['email']); ?><br>
                                    University: <?php echo e($selectedCustomer['university']); ?><br>
                                    Address: <?php echo e(trim($selectedCustomer['address'] . ', ' . $selectedCustomer['city'] . ', ' . $selectedCustomer['country'])); ?><br>
                                    Phone: <?php echo e($selectedCustomer['phone']); ?></p>
                            <?php else: ?>
                                <p>Select a customer to view details</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                            <h2 class="mdl-card__title-text">Order Details</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <table class="mdl-data-table mdl-shadow--2dp">
                                <thead>
                                <tr>
                                    <th class="mdl-data-table__cell--non-numeric">Cover</th>
                                    <th class="mdl-data-table__cell--non-numeric">ISBN</th>
                                    <th class="mdl-data-table__cell--non-numeric">Title</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($selectedCustomer === null): ?>
                                    <tr>
                                        <td colspan="3" class="mdl-data-table__cell--non-numeric">Select a customer to view orders.</td>
                                    </tr>
                                <?php elseif (count($orders) === 0): ?>
                                    <tr>
                                        <td colspan="3" class="mdl-data-table__cell--non-numeric">No order information for this customer.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($orders as $order): ?>
                                        <?php $cover = 'images/tinysquare/' . $order['isbn'] . '.jpg'; ?>
                                        <tr>
                                            <td class="mdl-data-table__cell--non-numeric">
                                                <?php if (file_exists($cover)): ?>
                                                    <img src="<?php echo e($cover); ?>" alt="book cover" style="height:32px;">
                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            </td>
                                            <td class="mdl-data-table__cell--non-numeric"><?php echo e($order['isbn']); ?></td>
                                            <td class="mdl-data-table__cell--non-numeric"><?php echo e($order['title']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer style="padding:12px 20px; color:#37474f;">
            CISC3003 Web Programming: dc325095 CHUNG HOU SAM 2026
        </footer>
    </main>
</div>

<script>
    $(function () {
        $('.inlinesparkline').sparkline('html', {
            type: 'bar',
            barColor: '#2196f3',
            chartRangeMin: 0
        });
    });
</script>
</body>
</html>
