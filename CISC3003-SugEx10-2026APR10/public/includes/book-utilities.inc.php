<?php

function readCustomers($filename)
{
    $customers = [];

    if (!is_readable($filename)) {
        return $customers;
    }

    $handle = fopen($filename, 'r');
    if ($handle === false) {
        return $customers;
    }

    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }

        $parts = explode(';', $line);
        if (count($parts) < 12) {
            continue;
        }

        $customers[] = [
            'id' => trim($parts[0]),
            'firstname' => trim($parts[1]),
            'lastname' => trim($parts[2]),
            'email' => trim($parts[3]),
            'university' => trim($parts[4]),
            'address' => trim($parts[5]),
            'city' => trim($parts[6]),
            'state' => trim($parts[7]),
            'country' => trim($parts[8]),
            'postal' => trim($parts[9]),
            'phone' => trim($parts[10]),
            'sales' => trim($parts[11])
        ];
    }

    fclose($handle);

    return $customers;
}

function readOrders($customer, $filename)
{
    $orders = [];

    if (!is_readable($filename)) {
        return $orders;
    }

    $customerId = (string)$customer;
    $handle = fopen($filename, 'r');
    if ($handle === false) {
        return $orders;
    }

    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }

        $parts = explode(',', $line);
        if (count($parts) < 5) {
            continue;
        }

        $orderId = trim($parts[0]);
        $orderCustomerId = trim($parts[1]);
        $isbn = trim($parts[2]);
        $category = trim($parts[count($parts) - 1]);
        $titleParts = array_slice($parts, 3, count($parts) - 4);
        $title = trim(implode(',', $titleParts));

        if ($orderCustomerId === $customerId) {
            $orders[] = [
                'orderid' => $orderId,
                'customerid' => $orderCustomerId,
                'isbn' => $isbn,
                'title' => $title,
                'category' => $category
            ];
        }
    }

    fclose($handle);

    return $orders;
}

?>