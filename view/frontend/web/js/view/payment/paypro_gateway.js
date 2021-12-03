define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'paypro_afterpay',
                component: 'PayPro_PaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_bancontact',
                component: 'PayPro_PaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_ideal',
                component: 'PayPro_PaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_mastercard',
                component: 'PayPro_PaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_paypal',
                component: 'PayPro_PaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_sepa',
                component: 'PayPro_PaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_sepa_once',
                component: 'PayPro_PaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_sofort_physical',
                component: 'PayPro_PaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_visa',
                component: 'PayPro_PaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );
      
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
