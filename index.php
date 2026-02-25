<?php
// File to save orders
$dataFile = __DIR__ . '/orders.json';

// Admin password (please replace with stron password)
$adminPassword = 'YourSecretPassword';

// Secret key used as admin link 
$adminSecretKey = 'YourSecretLink';

// If file doesn't exist -> empty array
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([]));
}

// Load data
$orders = json_decode(file_get_contents($dataFile), true);

// Form edited?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset']) && ($_POST['password'] ?? '') === $adminPassword) {
        // Reset orders
        file_put_contents($dataFile, json_encode([]));
        $orders = [];
        $message = "Orders reset successfully!";
    } else {
        // Normal order
        $name = trim($_POST['name'] ?? '');
        $callsign = trim($_POST['callsign'] ?? '');
        $amount = (int)($_POST['amount'] ?? 0);

        if ($name !== '' && $amount > 0) {
            $orders[] = [
                'name' => htmlspecialchars($name),
                'callsign' => htmlspecialchars($callsign),
                'amount' => $amount,
                'time' => date('Y-m-d H:i')
            ];

            file_put_contents($dataFile, json_encode($orders, JSON_PRETTY_PRINT));
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Sum up total order amounts
$total = array_sum(array_column($orders, 'amount'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sausage orders for our club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <style>
    /* Basic styles for all device types */
    body {
        font-family: Arial, sans-serif;
        max-width: 100%;
        margin: 0 auto;
        padding: 15px;
        line-height: 1.6;
        color: #333;
    }
    h1 {
        text-align: center;
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    form {
        background: #f4f4f4;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 1rem;
    }
    input[type="text"],
    input[type="number"],
    input[type="password"] {
        box-sizing: border-box;
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    button {
        width: 100%;
        padding: 12px;
        margin-top: 15px;
        background: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        **min-height: 44px; /* 6a: Touch-Friendly Buttons */**
    }
    button:hover {
        background: #45a049;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    th, td {
        border-bottom: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    th {
        background: #f4f4f4;
        font-weight: bold;
    }
    tfoot td {
        font-weight: bold;
        background: #f4f4f4;
    }

    /* Details for mobile devices */
    @media (max-width: 600px) {
        body {
            padding: 10px;
        }
        h1 {
            font-size: 1.5rem;
        }
        form {
            padding: 15px;
        }
        table {
            font-size: 0.9rem;
            **display: block; /* 6b: Make table scrollable */
            overflow-x: auto; /* 6b */
            white-space: nowrap; /* 6b */**
        }
    }

    /* Admin-Area */
    .admin-form {
        background: #f0f0f0;
        padding: 15px;
        border-radius: 6px;
        margin-top: 20px;
    }
    .admin-form input {
        margin-bottom: 10px;
    }
</style>
</head>
<body>

<h1>🌭 Sausage orders for our club</h1>

<form method="post">
    <label>
        Name (Mandatory):
        <input type="text" name="name" required>
    </label>

    <label>
        Callsign:
        <input type="text" name="callsign">
    </label>

    <label>
        Amount of sausages (Mandatory):
        <input type="number" name="amount" min="1" max="10" required>
    </label>

    <button type="submit">Order</button>
</form>

<?php if (!empty($orders)): ?>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Callsign</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $b): ?>
        <tr>
            <td><?= $b['name'] ?></td>
            <td><?= $b['callsign'] ?></td>
            <td><?= $b['amount'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td>Total</td>
            <td><?= $total ?></td>
        </tr>
    </tfoot>
</table>
<?php else: ?>
<p>No orders yet.</p>
<?php endif; ?>

<!-- Admin-Area: Visible only when secret key is in the URL -->
<?php if (isset($_GET['admin']) && $_GET['admin'] === $adminSecretKey): ?>
    <div class="admin-form">
        <h2>Admin-Area</h2>
        <?php if (isset($message)): ?>
            <p style="color: green; text-align: center;"><?= $message ?></p>
        <?php endif; ?>
        <form method="post">
            <label>
                Admin-Password:
                <input type="password" name="password" required>
            </label>
            <button type="submit" name="reset" style="background: #ff6b6b; color: white; border: none; padding: 8px 12px; cursor: pointer;">
                Reset orders
            </button>
        </form>
    </div>
<?php endif; ?>

</body>
</html>
