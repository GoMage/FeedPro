/**
 * GoMage.com
 *
 * GoMage Feed Pro
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.2
 * @since        Class available since Release 1.0
 */

function gfpSaveConfig(obj) {
    $('loading-mask').show();
    configForm.submit();
}

function gfpGenerate(e) {
    var params = {
        'code_id': e.id
    };
    $('loading-mask').show();
    var request = new Ajax.Request(gomage_feed_config_generate_url, {
        method: 'GET',
        parameters: params,
        loaderArea: false,
        onSuccess: function (transport) {
            window.location.reload();
        }
    });
}


	