<?php
echo '<div class="wrap">';

/**
 * 
 * activate license
 */
if (isset($_REQUEST['wpsci_activate_license'])) {

    $license_key = $_REQUEST['wpsci_license_key'];

    $action = 'activate';
    $api_url = WPSCI_LICENSE_SERVER_URL . $action . '/' . $license_key;

    $api_url = add_query_arg(array(
        'consumer_key'    => WPSCI_CONSUMER_KEY,
        'consumer_secret' => WPSCI_CONSUMER_SECRET,
        'label'           => $_SERVER['SERVER_NAME'],
        'software'        => 'WPSCI_PLUGIN'
    ), $api_url);


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($response);

    if($response->code == 'data_error'){
        
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Invalid license key!', 'wp-self-check-in' );

        printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
    }

    if($response->code == 'license_expired'){
        
        $class = 'notice notice-error is-dismissible';
        $message = __( 'License key has expired!', 'wp-self-check-in' );

        printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
    }

    if($response->code == 'license_activation_limit_reached'){

        $class = 'notice notice-error is-dismissible';
        $message = __( 'License key reached maximum activation!', 'wp-self-check-in' );

        printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
    }
    
    if($response->success == '1'){

        update_option('wpsci_license_key_token', $response->data->token);
        update_option('wpsci_license_key', $license_key);
        update_option('wpsci_license_key_validate', time());
        
        $class = 'notice notice-success is-dismissible';
        $message = __( 'License key activated successfully!', 'wp-self-check-in' );

        printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
    }

}


/**
 * 
 * deactivate license
 */
if (isset($_REQUEST['wpsci_deactivate_license'])) {

    $license_key = $_REQUEST['wpsci_license_key'];

    $action = 'deactivate';
    $api_url = WPSCI_LICENSE_SERVER_URL . $action . '/' . get_option('wpsci_license_key_token');

    $api_url = add_query_arg(array(
        'consumer_key'    => WPSCI_CONSUMER_KEY,
        'consumer_secret' => WPSCI_CONSUMER_SECRET,
    ), $api_url);


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($response);

    if($response->code == 'data_error'){
        
        $class = 'notice notice-error is-dismissible';
        $message = __( 'License key already deactivated!', 'wp-self-check-in' );

        printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
    }
    
    if($response->success == '1'){

        delete_option('wpsci_license_key_token');
        delete_option('wpsci_license_key');
        
        $class = 'notice notice-success is-dismissible';
        $message = __( 'License key deactivated successfully!', 'wp-self-check-in' );

        printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
    }

}

?>

<h1 class="wp-heading-inline"><?= __('WP Self Check-In Pro Licensing', 'wp-self-check-in') ?></h1>

<?php //license message
if (!wpsci_validate_license()): ?>
    <div class="notice notice-error">
    <p>
        <?php _e('WP Self Check-In Pro license key has expired!', 'wp-self-check-in'); ?>
    </p>
    </div>
<?php endif; ?>

<p>Enter your license key to activate the product. The key was provided to you at the time of purchase.</p>

<form method="post">

    <table class="form-table">
        <tr>
            <th style="width:100px;"><label for="wpsci_license_key">License Key</label></th>
            <td ><input class="regular-text" type="text" id="wpsci_license_key" name="wpsci_license_key"  value="<?= get_option('wpsci_license_key'); ?>" ></td>
        </tr>
    </table>

    <p class="submit">
        <input type="submit" name="wpsci_activate_license" value="Activate" class="button-primary" />
        <input type="submit" name="wpsci_deactivate_license" value="Deactivate" class="button" />
    </p>

</form>

<?php

echo '</div>';