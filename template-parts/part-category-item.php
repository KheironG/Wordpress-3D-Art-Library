<?php $category_url = get_home_url() . '/taxonomy?tax_ID=' . $args->term_taxonomy_id . '&tax_name=' . $args->name; ?>
<a href="<?php echo $category_url?>">
    <div class="category-item">
        <small><?php echo $args->name; ?></small>
    </div>
</a>
