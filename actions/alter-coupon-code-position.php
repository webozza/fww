<?php

add_action('woocommerce_review_order_after_order_total', function () {
    ?>
    <div id="custom-coupon-box">
        <label for="custom_coupon_code" style="display: block; font-weight: bold; margin-bottom: 10px;">Have a discount code?</label>
        <input placeholder="discount code" type="text" id="custom_coupon_code" style="width: calc(100% - 110px); padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-right: 10px;" />
        <button id="apply_custom_coupon" style="padding: 10px 20px; background-color: #af312e; color: #fff; border: none; border-radius: 4px; text-transform: uppercase; cursor: pointer;">enter here</button>
        <div id="coupon-loading" style="display: none; margin-top: 10px; font-size: 14px; color: #52A37F;">Applying discount...</div>
    </div>
    <?php
});