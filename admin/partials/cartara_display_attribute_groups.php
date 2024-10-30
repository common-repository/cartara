<?php
$post_id = intval(get_the_ID());
if (!empty($attribute_groupname)) {
    $i = 1;
    ?>
    <h5><i><b>*Attributes/Options assigned to this product</b></i></h5>
    <ul class ="nested <?php echo 'group_' . $i; ?>" >
        <?php
        foreach ($attribute_groupname as $group_val) {
            $group_n = esc_html($group_val['groupname']);
            ?>
            <li class ="<?php echo $group_n . $i; ?>">
                <b><?php echo $group_n; ?></b>
                <?php
                if (!empty($attribute_data)) {
                    $attribute_group = '';
                    $attributename = '';
                    ?>
                    <ul class = "no_padding">
                        <?php
                        $k = 1;
                        foreach ($attribute_data as $attribute_data_val) {
                            $attribute_group = esc_html($attribute_data_val['groupname']);
                            $attributename = esc_html($attribute_data_val['attributename']);
                            if (!empty($attribute_group)) {
                                if ($group_n == $attribute_group) {
                                    if (!empty($attributename)) {
                                        ?>
                                        <li class="<?php echo'option_' . $i . '.' . $k; ?>">
                                            <b><?php echo '~ ' . $attributename; ?></b>
                                        </li>
                                        <?php
                                        $k++;
                                    }
                                }
                            }
                        }
                        ?>
                    </ul>
                    <?php
                }
                ?>
            </li>
            <?php
            $i++;
        }
        ?>
    </ul>
    <?php
}?>