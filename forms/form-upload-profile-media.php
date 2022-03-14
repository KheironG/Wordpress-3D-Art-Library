<form id="upload-<?php echo $args; ?>-form" method="post">
    <div class="edit-menu">
        <div class="flex-container gap-25">
            <div class="flex-container gap-25 hide">
                <span class="trigger delete-small-blue-icon" style="padding-right:15px;"
                onclick="deleteProfileMedia('<?php echo $args; ?>');"></span>
                <label for="upload-<?php echo $args; ?>">
                    <span class="upload-small-blue-icon trigger" style="padding-right:10px;"></span>
                </label>
            </div>
            <div class="trigger" onclick="editMenu(this)">
                <span class="edit-small-blue-icon"></span>
            </div>
        </div>
    </div>
    <input type="file" id="upload-<?php echo $args; ?>" name="upload-<?php echo $args; ?>"
     accept="image/png, image/jpeg, image/png" onchange="uploadProfileMedia(this);">
</form>
