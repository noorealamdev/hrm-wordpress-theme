<?php
defined( 'ABSPATH' ) || exit;

$invoice_id = $args['data']['invoice_id'];

if ( ! $invoice_id ) {
	return;
} else {
	$invoice    = Hrm_DbQuery::get_table_single_row('invoices', $invoice_id);
	$items_data = json_decode($invoice->items_data);
	$item_name  = $items_data->item_name;
	$item_desc  = $items_data->item_desc;
	$item_qty   = $items_data->item_qty;
	$item_price = $items_data->item_price;

	$total_price = array_sum($item_price);
	$item_single = [];
	foreach ( $item_name as $key => $name ) {
	    $item_single[] = ['name' => $item_name[$key], 'desc' => $item_desc[$key], 'qty' => $item_qty[$key], 'price' => $item_price[$key]];
    }
	?>

	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $invoice->invoice_no ?></title>

		<!-- Web Fonts
		======================= -->
		<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>

		<!-- Stylesheet
		======================= -->
		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() . '/assets/invoice/stylesheet.css'?>" />
	</head>
	<body>
	<!-- Container -->
	<div class="container-fluid invoice-container">
		<!-- Header -->
		<header>
			<div class="row align-items-center gy-3">
				<div class="col-sm-7 text-center text-sm-start">
					<img id="logo" src="images/logo.png" title="HRM" alt="HRM" />
				</div>
				<div class="col-sm-5 text-center text-sm-end">
					<h4 class="text-7 mb-0">Invoice</h4>
				</div>
			</div>
			<hr>
		</header>

		<!-- Main Content -->
		<main>
			<div class="row">
				<div class="col-sm-6"><strong>Date:</strong> <?php echo $invoice->date ?></div>
				<div class="col-sm-6 text-sm-end"> <strong>Invoice No:</strong> <?php echo 'Inv-' .$invoice->id ?></div>

			</div>
			<hr>
			<div class="row">
				<div class="col-sm-6 text-sm-end order-sm-1"> <strong>Pay To:</strong>
					<address>
						<?php echo $invoice->pay_name ?><br />
						<?php echo $invoice->pay_address ?><br />
						<?php echo $invoice->pay_email ?>
					</address>
				</div>
				<div class="col-sm-6 order-sm-0"> <strong>Invoiced To:</strong>
					<address>
						<?php echo $invoice->invoice_name ?><br />
						<?php echo $invoice->invoice_address ?><br />
						<?php echo $invoice->invoice_email ?>
					</address>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table border mb-0">
					<thead>
					<tr class="bg-light">
						<td class="col-3"><strong>Item</strong></td>
						<td class="col-4"><strong>Description</strong></td>
						<td class="col-1 text-center"><strong>QTY</strong></td>
						<td class="col-2 text-end"><strong>Amount</strong></td>
					</tr>
					</thead>
					<tbody>
                    <?php foreach ($item_single as $item) : ?>
					<tr>
						<td class="col-3"><?php echo $item['name'] ?></td>
						<td class="col-4 text-1"><?php echo $item['desc'] ?></td>
						<td class="col-1 text-center"><?php echo $item['qty'] ?></td>
						<td class="price-column col-2 text-end" data-price="<?php echo $item['price'] ?>">$<?php echo $item['price'] ?></td>
					</tr>
                    <?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="table-responsive">
				<table class="table border border-top-0 mb-0">
					<tr class="bg-light">
						<td class="text-end"><strong>Sub Total:</strong></td>
						<td id="subtotalPrice" class="col-sm-2 text-end">$<?php echo $total_price ?>.00</td>
					</tr>
					<tr class="bg-light">
						<td class="text-end"><strong>Tax:</strong></td>
						<td class="col-sm-2 text-end">$0.00</td>
					</tr>
					<tr class="bg-light">
						<td class="text-end"><strong>Total:</strong></td>
						<td id="totalPrice" class="col-sm-2 text-end">$<?php echo $total_price ?>.00</td>
					</tr>
				</table>
			</div>
		</main>
		<!-- Footer -->
		<footer class="text-center mt-4">
			<p class="text-1"><strong>NOTE :</strong> This is computer generated receipt and does not require physical signature.</p>
			<div class="btn-group btn-group-sm d-print-none"> <a href="javascript:window.print()" class="btn btn-light border text-black-50 shadow-none"><i class="fa fa-print"></i> Print & Download</a> </div>
		</footer>
	</div>

	</body>
	</html>

<?php }