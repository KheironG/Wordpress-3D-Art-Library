<div class="tag-item">
    <input type="hidden" name="<?php echo $args['post_type'] ?>-tag" value="<?php echo $args['tag'] ?>">
    <span class="delete-small-white-icon trigger" onclick="removeTag(this);"></span>
    <small class="label-white"><?php echo $args['tag'] ?></small>
</div>
