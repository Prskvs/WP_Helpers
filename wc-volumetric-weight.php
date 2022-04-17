<?php
/**
 * Volumetric shipping cost
 * Currently applied to Flexible Shipping
 */
add_filter( 'woocommerce_package_rates', function ($rates, $package) {
    $total_weight = 0;
    try {
        foreach($package['contents'] as $item) {
            /** @var WC_Product $product */
            $product = $item['data'];
            $weight = floatval($product->get_weight());
            $volume = floatval($product->get_width()) * floatval($product->get_length()) * floatval($product->get_height());
            $volumetric_weight = $volume / 5000;
            if ($volumetric_weight > $weight) {
                $total_weight += $volumetric_weight * $item['quantity'];
            }
            else {
                $total_weight += $weight * $item['quantity'];
            }
        }

        /** @var WC_Shipping_Rate $rate */
        foreach ($rates as $key => $rate) {
            /** 
             * Flexible Shipping support
             */
            if ($rate->get_method_id() === 'flexible_shipping_single') {
                foreach ($rate->get_meta_data()['_fs_method']['method_rules'] as $rule) {
                    $condition = $rule['conditions'][0];

                    if (
                        $condition['condition_id'] === 'weight' &&
                        floatval($condition['min']) <= $total_weight &&
                        (floatval($condition['max']) >= $total_weight || empty($condition['max']))
                    ) {
                        /**
                         * TODO: include tax and additional flexible shipping costs
                         */
                        $rates[$key]->set_cost($rule['cost_per_order']);
                        break;
                    }
                }
                break;
            }
        }
    }
    catch(Exception $e) {
        error_log($e->getMessage());
    }

    return $rates;
}, 50, 2 );