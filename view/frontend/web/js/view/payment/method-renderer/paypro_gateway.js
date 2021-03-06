define(
    [
        'Magento_Checkout/js/view/payment/default',
        'mage/url'
    ],
    function (Component, url) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'PayPro_PaymentGateway/payment/form',
                selectedIssuer: ''
            },

            initObservable: function () {
                this._super().observe('selectedIssuer');

                return this;
            },

            redirectAfterPlaceOrder: false,
            
            getIssuers: function () {
                return window.checkoutConfig.payment.issuers;
            },

            isDisabled: function () {
                return this.getCode() === 'paypro_ideal' && this.selectedIssuer() === '';
            },
            
            getData: function() {
                if (this.item.method === 'paypro_ideal') {
                    return {
                        'method': this.item.method,
                        'additional_data': {
                            'issuer': this.selectedIssuer(),
                        }
                    }
                }

                return {
                    'method': this.item.method,
                };
            },

            afterPlaceOrder: function() {
                window.location = url.build('paypro/startpayment/');
            }
        });
    }
);