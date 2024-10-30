<div class="wrap wrap-data">
    <div class="head_wrap">
        <h1 class="cn_title dashicons-before dashicons-cloud">Cartara Survey Forms</h1>
    </div>
    <div id="content">
        <h3 class="page_title">Shortcoder 
            <div class="cn_menu">
                <a href="<?php echo admin_url(); ?>admin.php?page=cartasurvey" class="button cn_back_btn">
                    <span class="dashicons dashicons-arrow-left-alt2"></span>Back to Form list</a>
            </div>
        </h3>
        <form method="post" id="cn_edit_form">
            <div class="cn_section">
                <label for="cn_name">Name</label>
                <div class="cn_name_wrap">
                    <input type="text" id="cn_name" name="cn_name" value="<?php echo ($data['formname'] != '') ? $data['formname'] : ''; ?>" class="widefat" required="required" placeholder="Enter a name for the form, case sensitive" pattern="[a-zA-z0-9 \-]+">
                    <?php if (intval(isset($_GET['id'])) != '') { ?>
                        <div class="copy_shortcode">
                            Your shortcode is - 
                            <strong>
                                <?php echo '[cartara_survey_form id =' . intval($_GET['id']) . ']'; ?>
                            </strong>
                        </div>
                    <?php } else { ?>
                        <div class="copy_shortcode">Allowed characters A to Z, a to z, 0 to 9, hyphens, underscores and space</div>
                    <?php } ?>
                </div>

            </div>
            <div class="cn_section">
                <label for="cn_content">Add action survey code below<span class="dashicons dashicons-info cn_note_btn" title="Put form data in below box."></span>
                </label>

                <div id="wp-cn_content-wrap" class="wp-core-ui wp-editor-wrap html-active">
                    <?php
                    $content = '';
                    if ($data['formdata'] != '') {
                        $content = stripslashes($data['formdata']);
                    }
                    $editor_id = 'form_content';
                    wp_editor($content, $editor_id);
                    ?>
                </div>
            </div>
            <div class="cn_settings">
                <div class="cn_section"><h4>Settings</h4><label>
                        <input type="checkbox" name="cn_disable" value="1" <?php echo ($data['status'] == '1') ? "checked" : ""; ?> > Temporarily disable this form shortcode</label>
                </div>
                <div class="cn_section"><h4>Visibility</h4>
                    <label>Show this form shortcode</label>
                    <select name="cn_devices">
                        <option value="all" <?php echo ($data['visible'] == 'all') ? 'selected="selected"' : ''; ?>>On both desktop and mobile devices</option>
                        <option value="mobile_only" <?php echo ($data['visible'] == 'mobile_only') ? 'selected="selected"' : ''; ?>>On mobile devices alone</option>
                        <option value="desktop_only" <?php echo ($data['visible'] == 'desktop_only') ? 'selected="selected"' : ''; ?>>On desktops alone</option>
                    </select>
                </div>
            </div>
            <footer class="page_footer">
                <input class="button button-primary cn_save" type="submit" name="cn_survey_save" value="Save settings">
                <?php wp_nonce_field('cn_survey_save_action', 'cn_create_survey_form'); ?>
            </footer>
        </form>
    </div>
</div>