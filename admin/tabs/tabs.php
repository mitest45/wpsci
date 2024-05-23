<h1 class="wp-heading-inline"><?= __('WP Self Check-In Pro', 'wp-self-check-in');?></h1>

<?php if(get_option('wpsci_license_key_token') && wpsci_validate_license()){?>
    <div class="nav-tab-wrapper">

        <?php //overview tab
        if(isset($_GET['page']) && $_GET['page']=='wp-self-check-in' && !isset($_GET['tab'])){?>
            <a href="<?= menu_page_url('wp-self-check-in', false);?>" class="nav-tab nav-tab-active"><?php _e( 'Overview', 'wp-self-check-in' );?></a>
        <?php }else{?>
            <a href="<?= menu_page_url('wp-self-check-in', false);?>" class="nav-tab"><?php _e( 'Overview', 'wp-self-check-in' );?></a>
        <?php }?>

        <?php //setting tab
        if(isset($_GET['tab']) && $_GET['tab']=='setting'){?>
            <a href="<?= menu_page_url('wp-self-check-in', false).'&tab=setting';?>" class="nav-tab nav-tab-active"><?php _e( 'Settings', 'wp-self-check-in' );?></a>
        <?php }else{?>
            <a href="<?= menu_page_url('wp-self-check-in', false).'&tab=setting';?>" class="nav-tab"><?php _e( 'Settings', 'wp-self-check-in' );?></a>
        <?php }?>

        <?php //notification tab
        if(isset($_GET['tab']) && $_GET['tab']=='notification'){?>
            <a href="<?= menu_page_url('wp-self-check-in', false).'&tab=notification';?>" class="nav-tab nav-tab-active"><?php _e( 'Notification', 'wp-self-check-in' );?></a>
        <?php }else{?>
            <a href="<?= menu_page_url('wp-self-check-in', false).'&tab=notification';?>" class="nav-tab"><?php _e( 'Notification', 'wp-self-check-in' );?></a>
        <?php }?>

        <?php //guests-data tab
        if(isset($_GET['tab']) && $_GET['tab']=='guests-data'){?>
            <a href="<?= menu_page_url('wp-self-check-in', false).'&tab=guests-data';?>" class="nav-tab nav-tab-active"><?php _e( 'Guests Data', 'wp-self-check-in' );?></a>
        <?php }else{?>
            <a href="<?= menu_page_url('wp-self-check-in', false).'&tab=guests-data';?>" class="nav-tab"><?php _e( 'Guests Data', 'wp-self-check-in' );?></a>
        <?php }?>

    </div>
<?php }else{?>
    <div class="nav-tab-wrapper">
    
        <span class="nav-tab nav-tab-active"><?php _e( 'Overview', 'wp-self-check-in' );?></span>
        <span class="nav-tab"><?php _e( 'Settings', 'wp-self-check-in' );?></span>
        <span class="nav-tab"><?php _e( 'Notification', 'wp-self-check-in' );?></span>
        <span class="nav-tab"><?php _e( 'Guests Data', 'wp-self-check-in' );?></span>
    
    </div>
<?php }?>
<div class="wpsci-alerts">
    <?php //saved message
    if (isset($_GET['saved']) && $_GET['saved'] == 1): ?>
        <div class="notice notice-success is-dismissible">
        <p><?php _e('Data successfully saved.', 'wp-self-check-in'); ?></p>
        </div>
    <?php endif; ?>

    <?php //delete message
    if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <div class="notice notice-success is-dismissible">
        <p><?php _e('Data successfully deleted.', 'wp-self-check-in'); ?></p>
        </div>
    <?php endif; ?>

    <?php //license message
    if (!get_option('wpsci_license_key_token')): ?>
        <div class="notice notice-error">
        <p>
            <?php _e('WP Self Check-In Pro requires license key!', 'wp-self-check-in'); ?>
            <a href="<?= admin_url('options-general.php?page=wp-self-check-in-license')?>"> <?php _e('Enter License Key', 'wp-self-check-in'); ?></a>
        </p>
        </div>
    <?php endif; ?>

    <?php //license message
    if (!wpsci_validate_license()): ?>
        <div class="notice notice-error">
        <p>
            <?php _e('WP Self Check-In Pro license key has expired!', 'wp-self-check-in'); ?>
            <a href="<?= admin_url('options-general.php?page=wp-self-check-in-license')?>"> <?php _e('See here', 'wp-self-check-in'); ?></a>
        </p>
        </div>
    <?php endif; ?>
    
</div>