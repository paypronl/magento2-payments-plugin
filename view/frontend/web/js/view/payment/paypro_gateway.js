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
                component: 'Magento_PayProPaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_bancontact',
                component: 'Magento_PayProPaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_ideal',
                component: 'Magento_PayProPaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_mastercard',
                component: 'Magento_PayProPaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_paypal',
                component: 'Magento_PayProPaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_sepa',
                component: 'Magento_PayProPaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_sepa_once',
                component: 'Magento_PayProPaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_sofort_digital',
                component: 'Magento_PayProPaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_sofort_physical',
                component: 'Magento_PayProPaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );

        rendererList.push(
            {
                type: 'paypro_visa',
                component: 'Magento_PayProPaymentGateway/js/view/payment/method-renderer/paypro_gateway'
            }
        );
      
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
