<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<h2>
	<?php
	if ( $sent_to_admin ) {
		$before = '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
		$after  = '</a>';
	} else {
		$before = '';
		$after  = '';
	}
	/* translators: %s: Order ID. */
	echo wp_kses_post( $before . sprintf( __( '[Order #%s]', 'woocommerce' ) . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
	?>
</h2>

<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>


        <?php
        // Loop through order items
            foreach ( $order->get_items() as $item_id => $item ) {
                ?>
                <tr>
                    <td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;">
                        <h4 style="margin-top:0px; margin-bottom:10px"><?php echo esc_html( $item->get_name() ); ?></h4>
                        <?php
                        // Fetch custom meta fields and display them
                        $width = wc_get_order_item_meta( $item_id, 'Width', true );
                        $height = wc_get_order_item_meta( $item_id, 'Height', true );
                        $mount = wc_get_order_item_meta( $item_id, 'Mount', true );
                        $window_name = wc_get_order_item_meta( $item_id, 'Window Name', true );
                        $returns = wc_get_order_item_meta( $item_id, 'Returns', true );
                        $color = wc_get_order_item_meta( $item_id, 'Color', true );
        
                        // Display each custom field in the product line
                        if ( $width ) {
                            echo '<p style="margin: 0 0 5px;"><strong>Width:</strong> ' . esc_html( $width ) . '</p>';
                        }
                        if ( $height ) {
                            echo '<p style="margin: 0 0 5px;"><strong>Height:</strong> ' . esc_html( $height ) . '</p>';
                        }
                        if ( $mount ) {
                            echo '<p style="margin: 0 0 5px;"><strong>Mount:</strong> ' . esc_html( $mount ) . '</p>';
                        }
                        if ( $window_name ) {
                            echo '<p style="margin: 0 0 5px;"><strong>Window Name:</strong> ' . esc_html( $window_name ) . '</p>';
                        }
                        if ( $returns ) {
                            echo '<p style="margin: 0 0 5px;"><strong>Returns:</strong> ' . esc_html( $returns ) . '</p>';
                        }
                        if ( $color ) {
                            echo '<p style="margin: 0 0 5px;"><strong>Color:</strong> ' . esc_html( $color ) . '</p>';
                        }
                        ?>
                    </td>
                    <td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;">
                        <?php echo esc_html( $item->get_quantity() ); ?>
                    </td>
                    <td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;">
                        <?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
                    </td>
                </tr>
                <?php
            }
        ?>


           
		</tbody>
		<tfoot>
			<?php
			$item_totals = $order->get_order_item_totals();

			if ( $item_totals ) {
				$i = 0;
				foreach ( $item_totals as $total ) {
					$i++;
					?>
					<tr>
						<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['label'] ); ?></th>
						<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
					</tr>
					<?php
				}
			}
			if ( $order->get_customer_note() ) {
				?>
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses( nl2br( wptexturize( $order->get_customer_note() ) ), array() ); ?></td>
				</tr>
				<?php
			}
			?>
		</tfoot>
	</table>
</div>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
