<?php
session_start();
require 'server/connection.php';

if (!isset($_SESSION['order_id']) || !isset($_SESSION['total'])) {
    echo "No order to process. <a href='shopping_cart.php'>Go back to shopping cart</a>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complete_payment'])) {
    $order_id = $_POST['order_id'];
    $transaction_id = $_POST['transaction_id'];
    $payment_date = date('Y-m-d H:i:s'); // Capture the current date and time

    // Get user_id from session
    $user_id = $_SESSION['user_id'];

    // Update the order status in the database
    $sql = "UPDATE orders SET order_status = 'Paid' WHERE order_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param('i', $order_id);
    if ($stmt->execute()) {
        // Insert the transaction details into the payments table
        $sql_payment = "INSERT INTO payments (order_id, transaction_id, user_id, payment_date) VALUES (?, ?, ?, ?)";
        $stmt_payment = $conn->prepare($sql_payment);

        if ($stmt_payment === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt_payment->bind_param('isss', $order_id, $transaction_id, $user_id, $payment_date); // Bind user_id parameter
        if ($stmt_payment->execute()) {
            // Clear the cart
            unset($_SESSION['cart']);
            unset($_SESSION['total']);
            unset($_SESSION['order_id']);
            header("Location: success.php?order_id=$order_id");
            exit();
        } else {
            echo "Error: " . $stmt_payment->error;
        }

        $stmt_payment->close();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$order_id = $_SESSION['order_id'];
$amount = number_format($_SESSION['total'] / 15502, 2, '.', '');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>

    <link rel="stylesheet" href="css/payment.css">
</head>

<body>
    <div class="breadcrumb">
        <span>Payment</span>
    </div>
    <section>
        <div>
            <p>Total Payment: Rp. <?php echo $_SESSION['total']; ?></p>
            <hr>
            <div id="paypal-button-container"></div>
        </div>

        <a href="checkout.php">
      <button type="button" class="btn-back">
        Kembali
      </button>
    </a>
    </section>
    <script src="https://www.paypal.com/sdk/js?client-id=AZc7gISngCVfWIqTNzlMZRSCsd7cte4sTB4ZrK7JEJHUGO9CEALMKj4mzo5ZIe2i6DRAiOhJouUWqxXF&currency=USD"></script>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $amount; ?>'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    const transactionId = details.purchase_units[0].payments.captures[0].id;
                    document.getElementById('transaction-id').value = transactionId;
                    document.getElementById('payment-form').submit();
                });
            }
        }).render('#paypal-button-container');
    </script>

    <form id="payment-form" method="POST" action="payment.php">
        <input type="hidden" name="transaction_id" id="transaction-id">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <input type="hidden" name="complete_payment" value="1">
    </form>
</body>

</html>