<?php get_header();

$meta_key = str_replace( " ", "-", 'vertex colours' );
?>
<p><?php echo $meta_key; ?></p>
<div class="comment-item-container">

    <div class="comment-item-parent" id="comment-item-135">

            <div class="comment-content">
                <small><small>andreas wrote</small>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Suspendisse dictum ornare risus, et blandit nibh facilisis vitae.
                    Donec massa augue, ultrices ut aliquam non, imperdiet id odio.
                    Duis pulvinar efficitur mollis. Nam pellentesque leo a eleifend ornare.
                    Aliquam erat volutpat. Donec dignissim sit amet eros quis accumsan.
                    Aenean interdum felis vel sem facilisis pretium. Ut sit amet
                    nunc bibendum, convallis est ut, lacinia augue.
                    Aenean non lacinia mauris, nec viverra libero.
                </small>
            </div>

            <div class="comment-edit hide">
                <textarea name="comment-edit">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Suspendisse dictum ornare risus, et blandit nibh facilisis vitae.
                          Donec massa augue, ultrices ut aliquam non, imperdiet id odio.
                        Duis pulvinar efficitur mollis. Nam pellentesque leo a eleifend ornare.
                        Aliquam erat volutpat. Donec dignissim sit amet eros quis accumsan.
                        Aenean interdum felis vel sem facilisis pretium. Ut sit amet
                        nunc bibendum, convallis est ut, lacinia augue.
                        Aenean non lacinia mauris, nec viverra libero.</textarea>
                <div class="button-container">
                    <button class="button-cancel button-half" type="button"
                    onclick="commentUIhandler( 135, 'edit', 'hide' );" name="button">cancel</button>
                    <button class="button button-half "type="button" name="button"
                    onclick="commentHandler( 135, true, 'edit' )">post</button>
                </div>
            </div>

            <div class="comment-reply hide">
                <small><small>replying to </small><em>
                    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Suspendisse dictum ornare risus, et blandit nibh facilisis vitae.
                        Donec massa augue, ultrices ut aliquam non, imperdiet id odio.
                        Duis pulvinar efficitur mollis. Nam pellentesque leo a eleifend ornare.
                        Aliquam erat volutpat. Donec dignissim sit amet eros quis accumsan.
                        Aenean interdum felis vel sem facilisis pretium. Ut sit amet
                        nunc bibendum, convallis est ut, lacinia augue.
                        Aenean non lacinia mauris, nec viverra libero."
                </em></small>
                <textarea name="comment-reply"></textarea>
                <div class="button-container">
                    <button class="button-cancel button-half" type="button" name="button"
                    onclick="commentUIhandler( 85, 'reply', 'hide' );">cancel</button>
                    <button class="button button-half "type="button"
                    onclick="commentHandler( 85, true, 'reply' )" name="button">post</button>
                </div>
            </div>

            <div class="comment-delete hide">
                <small><small>delete? </small><em>
                    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Suspendisse dictum ornare risus, et blandit nibh facilisis vitae.
                        Donec massa augue, ultrices ut aliquam non, imperdiet id odio.
                        Duis pulvinar efficitur mollis. Nam pellentesque leo a eleifend ornare.
                        Aliquam erat volutpat. Donec dignissim sit amet eros quis accumsan.
                        Aenean interdum felis vel sem facilisis pretium. Ut sit amet
                        nunc bibendum, convallis est ut, lacinia augue.
                        Aenean non lacinia mauris, nec viverra libero."
                </em></small>
                <div class="button-container">
                    <button class="button-accept button-half "type="button" name="button">yes, delete</button>
                    <button class="button-decline button-half" type="button"
                    onclick="commentUIhandler( 31, 'delete', 'hide' );" name="button">no, keep it</button>
                </div>
            </div>

            <div class="comment-options">
                <small>21-07-2021</small>
                <div>
                    <span class="comment-edit-icon" onclick="commentUIhandler( 135, 'edit', 'show' );"></span>
                    <span class="comment-delete-icon" onclick="commentUIhandler( 135, 'delete', 'show' );"></span>
                    <div class="flex-container">
                        <span class="comment-reply-icon" onclick="commentUIhandler( 135, 'reply', 'show' );"></span>
                    </div>
                </div>
            </div>

        </div>

    <div class="comment-item-children">

        <div class="comment-item-child">

            <div class="comment-content">
                <small><small>madde replied</small>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Suspendisse dictum ornare risus, et blandit nibh facilisis vitae.
                    Donec massa augue, ultrices ut aliquam non, imperdiet id odio.
                    Duis pulvinar efficitur mollis.
                </small>
            </div>

            <div class="comment-edit hide">
                <textarea name="name">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Suspendisse dictum ornare risus, et blandit nibh facilisis vitae.
                        Donec massa augue, ultrices ut aliquam non, imperdiet id odio.
                        Duis pulvinar efficitur mollis.
                </textarea>
                <div class="button-container">
                    <button class="button-cancel button-half" type="button" name="button">cancel</button>
                    <button class="button button-half "type="button" name="button">post</button>
                </div>
            </div>

            <div class="comment-options">
                <small>21-07-2021</small>
                <div class="flex-container">
                    <span class="comment-edit-icon"></span>
                    <span class="comment-delete-icon"></span>
                </div>
            </div>

        </div>


    </div>

</div>

<p onclick="getCommments();">test click</p>








<?php get_footer(); ?>
