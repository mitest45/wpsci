<div class="entry-content" id="verify_email">
    <div class="wpsci-main-wrapper ">
        <div class="table-res">
            <form method="POST" id="verify_email_form" enctype="multipart/form-data">
                <div class="table-res" id="guest_form_add">
                    <section id="mphb-customer-details" class="mphb-checkout-section mphb-customer-details">
                        <h3 class="mphb-customer-details-title">
                            <?php _e( 'Please enter booking email to verify.', 'wp-self-check-in' );?>
                        </h3>
                        
                        <p class="mphb-customer-first-name mphb-customer-name mphb-text-control">
                            <label for="mphb_first_name">
                            <?php _e( 'Email', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                            </label><br>
                            <input name="email" id="email" class="regular-text" type="text">
                        </p>
                    
                    </section>
                    <div class="col-12 mt-3 mb-3">
                        <button type="submit" name="verify_email" class="button button-primary"><?php _e( 'Verfiy', 'wp-self-check-in' );?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>