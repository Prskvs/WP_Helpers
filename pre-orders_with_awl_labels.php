<?php
require 'global_is_plugin_active.php';

/**
 * Advanced Woo Labels - custom functionality
 * Support Pre-Orders from module: pre-orders-for-woocommerce
 */
if (global_is_plugin_active('advanced-woo-labels/advanced-woo-labels.php')) {
    /**
     * Add pre-order as rule option
     */
    add_filter('awl_label_rules', function($options) {
        $options['attributes'][] = array(
            "name" => "Is pre-order",
            "id" => "is_pre_order",
            "type" => "bool",
            "operators" => "equals",
        );
        return $options;
    });

    /**
     * Add pro-order rule as condition for comparison
     */
    add_filter( 'awl_labels_condition_rules', function($conditions) {
        $conditions['is_pre_order'] = 'custom_awl_label_match_is_pre_order';
        return $conditions;
    });

    /**
     * Match condition for pre-orders
     */
    function custom_awl_label_match_is_pre_order() {
        global $product;

        $value = false;
        if (global_is_plugin_active('pre-orders-for-woocommerce/main.php')) {
            try {
                $p = new \Woocommerce_Preorders\Product($product->get_id());
                $value = $p->isPreOrder();
            }
            catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        return $value;
    }

    /**
     * Add pre-order identification class to product classes
     */
    if (global_is_plugin_active('pre-orders-for-woocommerce/main.php')) {
        add_filter( 'woocommerce_post_class', function($classes, $product_id) {
            $p = new \Woocommerce_Preorders\Product($product_id);
            if ($p->isPreOrder()) {
                $classes[] = 'pre-order';
            }

            return $classes;
        }, 10, 2 );
    }
}
