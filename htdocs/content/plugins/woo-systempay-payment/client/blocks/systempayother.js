/**
 * Copyright © Lyra Network and contributors.
 * This file is part of Systempay plugin for WooCommerce. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @author    Geoffrey Crofte, Alsacréations (https://www.alsacreations.fr/)
 * @copyright Lyra Network and contributors
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL v2)
 */

/**
 * External dependencies.
 */
import { registerPaymentMethod } from '@woocommerce/blocks-registry';

/**
 * Internal dependencies.
 */
import { getSystempayServerData } from './systempay-utils';

const PAYMENT_METHOD_NAME = 'systempayother_lyranetwork';
const systempay_prefix = 'systempayother_';
const styles = 
    {
        divWidth: {
            width: '95%'
        },
        imgFloat: {
            float: 'right'
        }
    };

var systempay_data = getSystempayServerData(PAYMENT_METHOD_NAME);

if (systempay_data?.sub_methods) {
    (systempay_data?.sub_methods).forEach((name) => {
        let method = systempay_prefix + name.toLowerCase();
        let data = getSystempayServerData(method);

        let Content = () => {
            return (data?.description);
        };

        let Label = () => {
            return (
                <div style={ styles.divWidth }>
                    <span>{ data?.title}</span>
                    <img
                        style={ styles.imgFloat }
                        src={ data?.logo_url }
                        alt={ data?.title }
                    />
                </div>
            );
        };

        registerPaymentMethod({
            name: method,
            label: <Label />,
            ariaLabel: 'Systempay payment method',
            canMakePayment: () => true,
            content: <Content />,
            edit: <Content />,
            supports: {
                features: data?.supports ?? [],
            },
        });
    })
}
