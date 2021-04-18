
<style>

table {
		font-family: arial, sans-serif;
		border-collapse: collapse;
		width: 100%;
}
 
td {
		border-bottom: 1px solid #dddddd;
		/*text-align: left;*/
		padding: 8px;
}

th {
		background-color: #dddddd;
}
 
tr:nth-child(even) {
		/*background-color: #eeeeee;*/
}

img {
	max-width:200px;
}

</style>

<div style = "font-size:110%">
	
	<table width=100% style = "margin:20px 0px; font-size:80%;">
		<tr>
			<td width="60%"><img src="<?php echo $atts['src-path']; ?>" ></td>  <!-- logo -->
			<td width="40%"><?php echo $atts['company']? wpautop($atts['company'], true) : "" ?></td> <!-- Formatted company info -->
		</tr>
	</table>

	<h2>Invoice</h2>

	<table width=100% style = "margin:20px 0px; font-size:80%;">
		<tr>
		
			<td width="35%">
				<h3>Billing Address</h3>
				<?php echo $atts['order']->get_formatted_billing_address() ?>
			</td>
			
			<td width="35%">
				<h3>Billing Address</h3>
				<?php echo $atts['order']->get_formatted_shipping_address() ?>
			</td>

			<td width="30%">
				<?php echo "Order Nmber: {$atts['order']->get_order_number()} <br/>" ?>  <!--  Order number -->
				<?php echo "Order Date: {$atts['order-date']} <br />"; ?>
				<?php echo "Shipping Method: {$atts['order']->get_shipping_method()}"; ?> <!-- Shipping method -->
					
			</td>
		</tr>
	</table>




	<table width=100% cellpadding="5" style = "border-collapse:collapse; margin:20px 0px; font-size:80%;">
		
		<?php if (isset($_GET['print-packing-slip'])) : ?>
			<tr border=1><th>Item</th><th>Description</th><th align="center">Qty.</th></tr>
		<?php elseif ($_GET['print-invoice']) : ?>
			<tr border=1><th>Item</th><th>Description</th><th align="center">Qty.</th><th align="center">Price</th><th align="center">Subtotal</th></tr>
		<?php endif; ?>

		<?php 
		foreach ($atts['cart'] as $item) { 
			$product = wc_get_product( $item['product_id'] );
			setlocale(LC_MONETARY, 'en_US');
			?>

			<tr>
				<td><?php echo $product->get_name(); ?></td> <!-- Product title -->
				<td><?php echo $product->get_short_description(); ?></td>  <!-- Product short decsription -->
				<td align="center"><?php echo $item['quantity']; ?></td>  <!-- Quantity -->
				<td align="center"><?php echo money_format('%.2n', $product->get_price()); ?></td>  <!-- Price -->
				<td align="center"><?php echo money_format('%.2n', $item['line_subtotal']); ?></td>  <!-- Line subtotal -->
			</tr>


		<?php } ?>
		<tr style="font-size:100%;"><th colspan=4 align="right">Total</th><th align="center"><?php echo money_format('%.2n', $atts['order']->get_total())?></th></tr>  <!-- Order total -->

	</table>

</div>



