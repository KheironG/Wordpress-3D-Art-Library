<?php $tag_url = get_home_url() . '/taxonomy?tax_ID=' . $args->term_taxonomy_id . '&tax_name=' . $args->name; ?>
<a href="<?php echo $tag_url?>">
    <div class="tag-item">
        <small><?php echo $args->name; ?></small>
    </div>
</a>
