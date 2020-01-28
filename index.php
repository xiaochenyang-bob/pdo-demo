<?php
    $pdo = new PDO('sqlite:chinook.db');
    $sql = '
      SELECT
        InvoiceId,
        InvoiceDate,
        Total,
        customers.FirstName as CustomerFirstName,
        customers.LastName as CustomerLastName
      FROM invoices
      INNER JOIN customers
      ON invoices.CustomerId = customers.CustomerId
    ';
    if (isset($_GET['search'])) {
      $sql = $sql . ' WHERE customers.FirstName LIKE ?';
    }
    $statement = $pdo->prepare($sql);
    if (isset($_GET['search'])) {
      $boundSearchParam = '%' . $_GET['search'] . '%';
      $statement->bindParam(1, $boundSearchParam);
    }
    $statement->execute();
    $invoices = $statement->fetchAll(PDO::FETCH_OBJ);
//    var_dump($invoices);
//    die;
?>

<form action = "index.php" method="get">
    <input type="text" name="search" placeholder = "Search..."
    value="<?php echo isset($_GET['search']) ? $_GET['search']: '' ?>">
    <button type="submit">
        Search
    </button>
   <a href= "/">Clear</a>
</form>

<table>
    <thead>
        <tr>
            <th>Invoice ID</th>
            <th>Data</th>
            <th>Total</th>
            <th colspan="2">Customer</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($invoices as $invoice): ?>
        <tr>
            <td>
                <?php echo $invoice->InvoiceId ?>
            </td>
            <td>
                <?php echo $invoice->InvoiceDate ?>
            </td>
            <td>
                <?php echo $invoice->Total ?>
            </td>
            <td>
                <?php echo $invoice->CustomerFirstName. " " . $invoice->CustomerLastName ?>
            </td>
            <td>
                <a href="invoice-details.php?invoice=<?php echo $invoice->InvoiceId ?>">
                  Details
                </a>
            </td>
        </tr>
        <?php endforeach;?>
        <?php if (count($invoices) == 0): ?>
        <tr>
            <td colspan="4">
                No Results
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>




    
