<?php
/**
 * Provide a admin area view for the plugin
 */
?>

<div class="wrap wrap-data">
    <div class="head_wrap">
        <h1 class="dashicons-before dashicons-cloud">Cartara</h1>
    </div>
    <div id="content">
        <h3 class="page_title">List of shortcodes created (<?php echo count($data); ?>)<span class="cn_menu">

                <span class="button search_btn" tooltip="Search shortcodes">
                    <span class="dashicons dashicons-search"></span>
                    <input type="search" class="search_box" placeholder="Search ...">
                </span>
                <a href="#" class="button buttons link-sort-list asc" tooltip="Ascending Order">
                    <span class="dashicons dashicons-download"></span>
                </a>
                <a href="#" class="button buttons link-sort-list desc" tooltip="Descending Order">
                    <span class="dashicons dashicons-upload"></span>
                </a>

                <a href="<?php echo admin_url(); ?>admin.php?page=cartara_shortcoder" class="button button-primary cn_new_btn">
                    <span class="dashicons dashicons-plus">
                    </span> Create a new shortcode</a>
            </span>
        </h3>
        <ul class="cn_list" id="cn_list" data-empty="No shortcodes are created. Go ahead create one !">
            <div class="cn_list_m"></div>
            <?php
            if ($data != '') {
                foreach ($data as $data_val) {
                    $id = esc_attr($data_val['id']);
                    $name = esc_html($data_val['formname']);
                    ?>
                    <li data-name= <?php echo $name; ?>  data-tags="">
                        <a href="<?php echo admin_url(); ?>admin.php?action=edit&id=<?php echo $id; ?>&page=cartara_shortcoder" class="cn_link" title="Edit shortcode">
                            <?php echo $name; ?> 
                        </a>
                        <span class="cn_controls">
                            <a href="javascript:void(0);" class="cartara_copy"  title="Copy shortcode"  onclick="copyToClipboard('#shortcode<?php echo $id; ?>')">
                                <span class="dashicons dashicons-editor-code"></span>
                            </a>
                            <a href="javascript:void(0);" class="cn_delete" data-sname="<?php echo esc_html($data_val['formname']); ?>" data-id="<?php echo $id; ?>" title="Delete" onclick="cartara_delete_form_code(<?php echo $id; ?>);">
                                <span class="dashicons dashicons-trash "></span>
                            </a>
                        </span>
                        <input type="text" value="[cartara_opt_form id ='<?php echo $id; ?>']" class="cartara_copy_box" readonly="readonly" title="Copy shortcode" >
                    </li>
                    <?php
                }
            } else {
                echo '<p align="center">No forms are created. Go ahead create one !</p>';
            }
            ?>
        </ul>
    </div>
</div>