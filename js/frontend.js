
//Display terms and conditions form-sign-up-form.php on creators-space.php
function signUpContinue() {
    event.preventDefault();
    document.getElementById('terms-and-conditions').classList.toggle('hide');
    return;
}

//Adapts form-sign-up-form.php to terms and conditions choice in sign up process
function termsAndConditions( choice ) {
    if ( choice === 'decline') {
        document.getElementById('terms-and-conditions').classList.toggle('hide'); }
    if ( choice === 'accept' ) {
        document.getElementById('terms-and-conditions').classList.toggle('hide');
        document.getElementById('sign-up-submit').classList.toggle('hide');
        document.getElementById('sign-up-continue').classList.toggle('hide');
        document.getElementById('sign-up-toc-container').classList.toggle('hide');
        document.getElementById('sign-up-toc').checked = true; }
}

//Unchecks terms and conditions on creators-space.php
function declineToc () {
    document.getElementById('sign-up-toc').checked = false;
    document.getElementById('sign-up-toc-container').classList.toggle('hide');
    document.getElementById('sign-up-continue').classList.toggle('hide');
    document.getElementById('sign-up-submit').classList.toggle('hide');
}


//Toggle menu function
function toggleOption ( optionName, optionGroup ) {
    if ( optionGroup === 'discover' ) {
        hideAllDiscoverSections();
        clearPreviosResults('discover-results-content');
        highLightDiscoverButton( null ); }
    const menu             = optionName + '-menu';
    const optionGroupMenus = document.querySelectorAll('.' + optionGroup + '-menu');
    for ( var i = 0; i < optionGroupMenus.length; i++ ) {
        let optionTarget = optionGroupMenus[i].id.replace('menu', 'option');
        if ( optionGroupMenus[i].id === menu ) {
            document.getElementById( optionTarget ).classList.remove( 'hide' ); }
        else if ( optionGroupMenus[i].id !== menu ) {
            document.getElementById( optionTarget ).classList.add( 'hide' ); }
    }
}



//Displays passwords in password input fields
function showPasswords ( collection ) {
    const passwordInputs = document.getElementsByClassName( collection );
    for ( let passwordInput of passwordInputs ) {
        if ( passwordInput.type === 'password' ) {
            passwordInput.type = 'text'; }
        else {
            passwordInput.type = 'password'; } }
}


//Adds a tag to list of tags in form-add-blender-post.php and form-update-profile-details.php
function addTag ( event, postType ) {
    if ( event.keyCode === 13 ) {
        event.preventDefault();
        const tagInput = event.target;
        if ( tagInput.value.length > 1 ) {
            const tagContainer = document.getElementById('update-' + postType + '-tag-container');
            const tagItem = document.createElement('div');
            const input = document.createElement('input');
            const deleteIcon = document.createElement('span');
            const label = document.createElement('small');
            tagItem.className = 'tag-item';
            input.setAttribute( 'type', 'hidden');
            input.setAttribute( 'name', postType + '-tag' );
            input.value = tagInput.value.split(' ').join('');
            label.className = 'label-white';
            label.textContent = tagInput.value.split(' ').join('');
            deleteIcon.className = 'delete-small-white-icon trigger';
            deleteIcon.setAttribute( "onclick", "removeTag(this);" );
            tagItem.appendChild(deleteIcon);
            tagItem.appendChild(label);
            tagItem.appendChild(input);
            tagContainer.appendChild(tagItem);
            tagInput.value = " ";
            return; }
        }
}


//Adds a tag to list of tags from list of private tags on in form-add-blender-post.php
function addPrivateTag ( trigger ) {
        event.preventDefault();
        const tagInput = trigger;
        const tagContainer = document.getElementById('update-blender-tag-container');
        const currentTags = tagContainer.children;
        if ( tagInput.innerText.length > 0 ) {
            if ( currentTags.length > 0 ) {
                for ( let currentTag of currentTags ) {
                    if ( tagInput.innerText.toLowerCase() === currentTag.innerText.toLowerCase() ) {
                        return; } }
            }

            const tagItem      = document.createElement('div');
            const input        = document.createElement('input');
            const deleteIcon   = document.createElement('span');
            const label        = document.createElement('small');
            tagItem.className  = 'tag-item';
            input.setAttribute( 'type', 'hidden');
            input.setAttribute( 'name', 'blender-tag' );
            input.value = tagInput.textContent;
            label.textContent = tagInput.textContent;
            label.className = 'label-white';
            deleteIcon.className = 'delete-small-white-icon trigger';
            deleteIcon.setAttribute( "onclick", "removeTag(this);" );
            tagItem.appendChild(deleteIcon);
            tagItem.appendChild(label);
            tagItem.appendChild(input);
            tagContainer.appendChild(tagItem);
            return;
        } else {
            return; }
}


//Removes a tag from the tag selection function on creators-upload.php form
function removeTag ( tag ) {
    const container = tag.parentElement;
    container.remove();
    return;
}

//Toggles the edit menus on creators-settings.php cover and portrait uploads.
function editMenu ( trigger ) {
    const editOptions = trigger.previousElementSibling;
    editOptions.classList.toggle('hide');
    setTimeout( function() {
        editOptions.classList.toggle('hide');
    }, 4000);
    return;
}


function toggleProfileContact ( profileID ) {
    document.getElementById('single-profile-' + profileID ).classList.toggle('hide');
    document.getElementById('single-profile-contact-' + profileID ).classList.toggle('hide');
    return;
}


function iconHover ( trigger ) {
    const icon      = trigger;
    const iconState = icon.className;
    let changedState;
    if ( iconState.indexOf('inactive') !== -1 ) {
        changedState = iconState.replace("inactive", "active");
        icon.className = changedState; }
    else if ( iconState.indexOf('active') !== -1) {
        changedState = iconState.replace("active", "inactive");
        icon.className = changedState; }
    return;
}


function swipeElement ( trigger ) {
    const backward        = trigger.parentElement.firstElementChild;
    const forward         = trigger.parentElement.firstElementChild.nextElementSibling;
    const element         = trigger.parentElement.lastElementChild;
    const elementWidth    = element.scrollWidth;
    const containerWidth  = trigger.parentElement.offsetWidth;
    const currentlySwiped = element.offsetLeft;
    const toSwipeWidth    = elementWidth - ( Math.abs( currentlySwiped ) + containerWidth );
    //If nothing to swipe backward
    if ( trigger.className === 'swipe-backward' && currentlySwiped == 0 ) {
        return; }
    //If nothing to swipe forward
    if ( trigger.className === 'swipe-forward' && ( Math.abs( currentlySwiped ) + containerWidth ) == elementWidth  ) {
        return; }
    if ( currentlySwiped <= 0) {
        backward.classList.remove('hide'); }
    if ( toSwipeWidth >= 0 ) {
        forward.classList.remove('hide'); }
    //If to swipe forward and to swipe is less than container width
    if ( trigger.className === 'swipe-forward'
            && toSwipeWidth > 0
                && toSwipeWidth < containerWidth ) {
                    element.style.marginLeft = '-' + ( Math.abs( currentlySwiped ) + toSwipeWidth ) + 'px'; }
    //If to swipe forward and to swipe is greater than container width
    if ( trigger.className === 'swipe-forward'
            && toSwipeWidth > 0
                && toSwipeWidth > containerWidth ) {
                    element.style.marginLeft = '-' + ( Math.abs( currentlySwiped ) + containerWidth ) + 'px'; }
    //If to swipe backward and swipe backward is less than or equal to container width
    if ( trigger.className === 'swipe-backward'
        && Math.abs( currentlySwiped ) > 0
            && Math.abs( currentlySwiped ) <= containerWidth )  {
                element.style.marginLeft = '0px'; }
    //If to swipe backward and swipe backward is greater than container width
    if ( trigger.className === 'swipe-backward'
        && Math.abs( currentlySwiped ) > 0
            && Math.abs( currentlySwiped ) > containerWidth )  {
                element.style.marginLeft = '-' + ( Math.abs( currentlySwiped ) - containerWidth ) + 'px'; }
    setTimeout( function() {
        setSwipeControls( trigger, false );
    }, 1100);
}

function setSwipeControls ( trigger, set ) {
    const backward        = trigger.parentElement.firstElementChild;
    const forward         = trigger.parentElement.firstElementChild.nextElementSibling;
    const element         = trigger.parentElement.lastElementChild;
    const elementWidth    = element.scrollWidth;
    const containerWidth  = trigger.parentElement.offsetWidth;
    const currentlySwiped = element.offsetLeft;
    const toSwipeWidth    = elementWidth - ( Math.abs( currentlySwiped ) + containerWidth );

    if ( set === true && elementWidth > containerWidth ) {
        forward.classList.remove('hide');
        element.onmouseover = null; }
    if ( toSwipeWidth <= 0 ) {
        forward.classList.add('hide'); }
    if ( currentlySwiped >= 0 ) {
        backward.classList.add('hide'); }
    return;
}

//Resets the color inputs under styling on creators-settings.php to theme defaults
function resetProfileStyleInputs() {
    document.getElementById('update-profile-background').value      = '#F7F7F7';
    document.getElementById('update-profile-contrast-colour').value = '#333333';
    return;
}




function toggleCaseManagager ( selected ) {
    const caseManagerOptions = document.getElementsByClassName('case-manager-option');
    for ( let option of caseManagerOptions ) {
        if ( option.id.includes( selected ) ) {
            option.classList.remove('hide'); }
        else {
            option.classList.add('hide'); } }
    return;
}

function searchPreviewDraft ( container, post ) {
    const anchor             = document.createElement('a');
    const item               = document.createElement('div');
    const preview            = document.createElement('img');
    const contentDiv         = document.createElement('div');
    const titleDiv           = document.createElement('div');
    const objectType         = document.createElement('h5');
    const title              = document.createElement('h5');
    const query              = document.createElement('div');
    const retrieved          = document.createElement('em');
    const taxQuery           = document.createElement('small');
    const lineBreak          = document.createElement('br');
    const postContentDiv     = document.createElement('div');
    const postContent        = document.createElement('small');

    anchor.className         = 'block search-item-anchor no-padding border-bottom';
    anchor.setAttribute( 'href', post[1].link );
    item.className           = 'search-item';
    preview.className        = 'search-item-object-preview-draft';
    contentDiv.className     = 'search-item-content';
    titleDiv.className       = 'flex-container gap-25';
    title.textContent        = 'render in progress';
    objectType.className     = 'search-type-blender-tag';
    objectType.textContent   = '3d object';
    query.className          = 'flex-container';
    retrieved.className      = 'small';
    retrieved.textContent    = 'retrieved with';
    taxQuery.className       = 'label';
    taxQuery.textContent     = post[1].queried_tax;
    postContentDiv.className = 'flex-container';
    postContent.textContent  = 'This object is currently being rendered by the server and will be accessible once the process is complete.';

    anchor.appendChild(item);
    item.appendChild(preview);
    item.appendChild(contentDiv);
    contentDiv.appendChild(titleDiv);
    titleDiv.appendChild(objectType);
    titleDiv.appendChild(title);
    contentDiv.appendChild(query);
    query.appendChild(retrieved);
    query.appendChild(taxQuery);
    contentDiv.appendChild(lineBreak);
    contentDiv.appendChild(postContentDiv);
    postContentDiv.appendChild(postContent);
    container.appendChild(anchor);

    return;
}

function searchPreview ( container, post ) {
    const anchor             = document.createElement('a');
    const item               = document.createElement('div');
    const preview            = document.createElement('img');
    const contentDiv         = document.createElement('div');
    const titleDiv           = document.createElement('div');

    const objectType         = document.createElement('h5');
    const title              = document.createElement('h5');

    const postCount          = post[1].type === 'artist' ? document.createElement('div')  : null;
    const icon               = post[1].type === 'artist' ? document.createElement('span') : null;
    const count              = post[1].type === 'artist' ? document.createElement('b')    : null;

    const author             = post[1].type === '3d object' || post[1].type === 'blog' || post[1].type === 'page' ? document.createElement('div') : null;
    const name               = post[1].type === '3d object' || post[1].type === 'blog' || post[1].type === 'page' ? document.createElement('small') : null;
    const published          = post[1].type === '3d object' || post[1].type === 'blog' || post[1].type === 'page' ? document.createElement('em') : null;

    const query              = document.createElement('div');
    const retrieved          = document.createElement('em');
    const taxQuery           = document.createElement('small');

    const andContainer       = document.createElement('div');

    const lineBreak          = document.createElement('br');
    const postContentDiv     = document.createElement('div');
    const postContent        = document.createElement('small');

    anchor.className         = 'block search-item-anchor no-padding border-bottom';
    anchor.setAttribute( 'href', post[1].link );

    item.className           = 'search-item';

    if ( post[1].thumbnail !== false && post[1].thumbnail !== undefined ) {
        preview.setAttribute( 'src', post[1].thumbnail );
        preview.className        = post[1].type === '3d object' || post[1].type === 'blog' || post[1].type === 'page' ? 'search-item-object-preview' : 'search-item-profile-preview'; }
    else {
        preview.className   = 'search-item-object-preview-draft'; }

    contentDiv.className     = 'search-item-content';

    titleDiv.className       = 'flex-container gap-25';
    title.textContent        = post[1].title;

    switch ( post[1].type ) {
        case '3d object':
            objectType.className = 'search-type-blender-tag';
            break;
        case 'artist':
            objectType.className = 'search-type-profile-tag';
            break;
        case 'blog':
            objectType.className = 'search-type-blog-tag';
            break;
        case 'page':
            objectType.className = 'search-type-page-tag';
            break;
        default: }

    objectType.textContent   = post[1].type;

    if ( post[1].type === 'artist' ) {
        postCount.className  = 'flex-container';
        icon.className       = 'fas fa-cube';
        count.textContent    = post[1].object_count; }

    if ( post[1].type === '3d object' || post[1].type === 'blog' || post[1].type === 'page' ) {
        author.className       = 'flex-container';
        name.className         = 'label';
        name.textContent       = post[1].author;
        published.className    = 'small';
        published.textContent  = 'published by ';
        andContainer.className = 'flex-container gap-45'; }

    query.className          = 'flex-container';
    retrieved.className      = 'small';
    retrieved.textContent    = 'retrieved with';
    taxQuery.className       = 'label';
    taxQuery.textContent     = post[1].queried_tax;

    postContentDiv.className = 'flex-container';
    postContent.textContent  = post[1].content;

    anchor.appendChild(item);
    item.appendChild(preview);
    item.appendChild(contentDiv);

    contentDiv.appendChild(titleDiv);
    titleDiv.appendChild(objectType);
    titleDiv.appendChild(title);
    if ( post[1].type === 'artist' ) {
        titleDiv.appendChild(postCount);
        postCount.appendChild(icon);
        postCount.appendChild(count);
        contentDiv.appendChild(query);
        query.appendChild(retrieved);
        query.appendChild(taxQuery);
        contentDiv.appendChild(query); }
    if ( post[1].type === '3d object' || post[1].type === 'blog' || post[1].type === 'page' ) {
        contentDiv.appendChild(andContainer);
        andContainer.appendChild(author);
        author.appendChild(published);
        author.appendChild(name);
        andContainer.appendChild(query);
        query.appendChild(retrieved);
        query.appendChild(taxQuery); }

    contentDiv.appendChild(lineBreak);
    contentDiv.appendChild(postContentDiv);
    postContentDiv.appendChild(postContent);
    container.appendChild(anchor);

}

function profileBlenderPreview ( container, post ) {
    const anchor     = document.createElement('a');
    const item       = document.createElement('div');
    const preview    = document.createElement('img');
    const overlay    = document.createElement('div');
    const content    = document.createElement('div');
    const title      = document.createElement('h4');

    anchor.className  = 'no-padding';
    item.className    = 'profile-blender-item';
    preview.className = 'blender-item-preview';
    overlay.className = 'blender-item-overlay';
    content.className = 'blender-item-overlay-content';
    title.className   = 'white-text';

    anchor.setAttribute( 'href', post[1].link );
    preview.src            =  post[1].thumbnail;
    title.textContent      =  post[1].title;

    anchor.appendChild(item);
    item.appendChild(preview);
    item.appendChild(overlay);
    overlay.appendChild(content);
    content.appendChild(title);

    container.appendChild(anchor);
}

function blogPreview ( container, post ) {
    const anchor      = document.createElement('a');
    const item        = document.createElement('div');
    const img         = document.createElement('img');
    const details     = document.createElement('div');
    const title       = document.createElement('h5');
    const excerpt     = document.createElement('small');
    anchor.className    = 'blog-item-link';
    anchor.href         = post[1].link;
    item.className      = 'blog-item';
    img.className       = 'blog-item-preview';
    img.src             = post[1].thumbnail;
    details.className   = 'blog-item-details';
    title.textContent   = post[1].title;
    excerpt.textContent = post[1].excerpt;
    anchor.appendChild(item);
    item.appendChild(img);
    item.appendChild(details);
    details.appendChild(title);
    details.appendChild(excerpt);
    container.appendChild(anchor);
}

function commentUIhandler ( commentID, action, state ) {
    const commentContainer = document.getElementById('comment-item-' + commentID );
    switch ( action ) {
        case 'edit':
            toggleAction( commentContainer, action, state );
            break;
        case 'reply':
            toggleAction( commentContainer, action, state );
            break;
        case 'delete':
            toggleAction( commentContainer, action, state );
            break;
        default:
        break; }

    function toggleAction ( commentContainer, action, state ) {
        const actions  = [ 'edit', 'reply', 'delete' ];
        switch ( state ) {
            case 'show':
            commentContainer.querySelector('.comment-content').classList.add('hide');
            actions.forEach(( instance ) => {
                if ( instance === action ) {
                    commentContainer.querySelector('.comment-' + instance ).classList.remove('hide');
                } else {
                    let element = commentContainer.querySelector('.comment-' + instance);
                    if ( element !== null ) {
                        element.classList.add('hide');
                    }
                }
            });
                break;
            case 'hide':
            commentContainer.querySelector('.comment-content').classList.remove('hide');
            actions.forEach(( instance ) => {
                let element = commentContainer.querySelector('.comment-' + instance);
                if ( element !== null ) {
                    element.classList.add('hide');
                }
            });
                break;
            default:
                break;
        }
    }

}


function commentUI ( response, isParent, gettingComment ) {

    const isUsers = response.is_users;

    const comment     = isParent == 1 || isParent === true ? document.createElement('div') : null;
    const commentItem = document.createElement('div');

    if ( isParent == 1 || isParent === true ) {
        const children        = document.createElement('div');
        comment.className     = 'comment-item-container';
        commentItem.className = 'comment-item-parent';
        commentItem.id        = 'comment-item-' + response.object_ID;
        children.className = 'comment-item-children hide';

        comment.appendChild(commentItem);
        comment.appendChild(children); }
    if ( isParent == 0 || isParent === false ) {
        commentItem.className = 'comment-item-child';
        commentItem.id        = 'comment-item-' + response.object_ID; }


    //Content section starts here
    const content            = document.createElement('div');
    const contentLabel       = document.createElement('a');
    const contentText        = document.createElement('small');
    content.className        = 'comment-content flex-container gap-5';
    contentLabel.className   = 'no-padding label';
    contentLabel.setAttribute( 'href', response.author_url );
    contentLabel.textContent = isParent == 1 || isParent === true ? response.author + ' wrote' : response.author + ' replied';
    contentText.textContent  = response.content;

    content.appendChild(contentLabel);
    content.appendChild(contentText);
    commentItem.appendChild(content);


    //Edit section starts here
    if ( isUsers === true ) {
        const edit              = document.createElement('div');
        const editArea          = document.createElement('textarea');
        const editButtons       = document.createElement('div');
        const editCancel        = document.createElement('button');
        const editConfirm       = document.createElement('button');

        edit.className          = 'comment-edit hide';
        editArea.name           = 'comment-edit';
        editArea.value          = response.content;
        editButtons.className   = 'comment-buttons-container';
        editCancel.className    = 'button-cancel';
        editCancel.setAttribute( 'onclick', 'commentUIhandler( ' + response.object_ID + ', "edit", "hide" );' );
        editCancel.textContent  = 'cancel';
        editConfirm.className   = 'button ';
        editConfirm.textContent = 'confirm';
        editConfirm.setAttribute( 'onclick', 'commentHandler( ' + response.object_ID  + ', ' + true + ', ' + '"edit" );' );

        edit.appendChild(editArea);
        edit.appendChild(editButtons);
        editButtons.appendChild(editCancel);
        editButtons.appendChild(editConfirm);
        commentItem.appendChild(edit); }

    //Reply section starts here. Created if response is parent level comment
    if ( isParent == 1 || isParent === true ) {
        const reply             =  document.createElement('div');
        const replyWrapper      =  document.createElement('div');
        const replyLabel        =  document.createElement('small');
        const replyText         =  document.createElement('small');
        const replyArea         =  document.createElement('textarea');
        const replyButtons      =  document.createElement('div');
        const replyCancel       =  document.createElement('button');
        const replyConfirm      =  document.createElement('button');

        reply.className         = 'comment-reply hide';
        replyWrapper.className  = 'flex-container gap-5';
        replyLabel.className    = 'label';
        replyLabel.textContent  = 'replying to ';
        replyText.className     = 'italic';
        replyText.textContent   = response.content;
        replyArea.name          = 'comment-reply';
        replyButtons.className  = 'comment-buttons-container';
        replyCancel.className   = 'button-cancel ';
        replyCancel.setAttribute( 'onclick', 'commentUIhandler( ' + response.object_ID + ', "reply", "hide" );' );
        replyCancel.textContent = 'cancel';
        replyConfirm.className  = 'button ';
        replyConfirm.textContent= 'reply';
        replyConfirm.setAttribute( 'onclick', 'commentHandler( ' + response.object_ID  + ', ' + false + ', ' + '"reply" );' );

        reply.appendChild(replyWrapper);
        replyWrapper.appendChild(replyLabel);
        replyWrapper.appendChild(replyText);
        reply.appendChild(replyArea);
        reply.appendChild(replyButtons);
        replyButtons.appendChild(replyCancel);
        replyButtons.appendChild(replyConfirm);
        commentItem.appendChild(reply); }

    //Delete section starts here
    if ( isUsers === true ) {
        const del             = document.createElement('div');
        const delWrapper      = document.createElement('div');
        const delLabel        = document.createElement('small');
        const delText         = document.createElement('small');
        const delButtons      = document.createElement('div');
        const delCancel       = document.createElement('button');
        const delConfirm      = document.createElement('button');

        del.className         = 'comment-delete hide';
        delWrapper.className  = 'flex-container gap-5';
        delLabel.className    = 'label';
        delLabel.textContent   = 'delete? ';
        delText.className     = 'italic';
        delText.textContent   = response.content;
        delButtons.className  = 'flex-container gap-5 margin-top';
        delCancel.className   = 'button-decline ';
        delCancel.setAttribute( 'onclick', 'commentUIhandler(' + response.object_ID + ', "delete", "hide" );' );
        delCancel.textContent = 'no, keep it';
        delConfirm.className  = 'button-accept ';
        delConfirm.textContent = 'yes, delete';
        delConfirm.setAttribute( 'onclick', 'commentHandler(' + response.object_ID  + ', ' + true + ', ' + '"delete" );' );

        del.appendChild(delWrapper);
        delWrapper.appendChild(delLabel);
        delWrapper.appendChild(delText);
        del.appendChild(delButtons);
        delButtons.appendChild(delCancel);
        delButtons.appendChild(delConfirm);
        commentItem.appendChild(del); }

    //Options section starts here
    if ( ( isParent == 1 || isParent === true ) || isUsers === true ) {
        const options          = document.createElement('div');
        const date             = document.createElement('small');
        const optionsWrapper   = document.createElement('div');
        const editTrigger      = isUsers === true ? document.createElement('span') : null;
        const deleteTrigger    = isUsers === true ? document.createElement('span') : null;
        const replyTrigger     = isParent == 1 || isParent === true ? document.createElement('span') : null;
        options.className      = 'comment-options';

        const dateValue        = new Date (response.date);
        const months           = [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ];
        const monthIndex       = dateValue.getMonth()
        const monthName = months[monthIndex];
        date.textContent       = dateValue.getDate() + ' ' + monthName + ' ' + dateValue.getFullYear() ;

        options.appendChild(date);
        options.appendChild(optionsWrapper);

        if ( isUsers === true ) {
            editTrigger.className   = 'comment-edit-icon';
            editTrigger.setAttribute( 'onclick', 'commentUIhandler( ' + response.object_ID + ', "edit", "show" );' );
            deleteTrigger.className = 'comment-delete-icon';
            deleteTrigger.setAttribute( 'onclick', 'commentUIhandler( ' + response.object_ID + ', "delete", "show" );' );
            optionsWrapper.appendChild(editTrigger);
            optionsWrapper.appendChild(deleteTrigger); }
        if ( isParent == 1 || isParent === true ) {
            replyTrigger.className = 'comment-reply-icon';
            replyTrigger.setAttribute( 'onclick', 'commentUIhandler( ' + response.object_ID + ', "reply", "show" );' );
            optionsWrapper.appendChild(replyTrigger); }
        commentItem.appendChild(options);
    }

    if ( ( isParent == 1 || isParent === true ) && gettingComment === false ) {
        document.getElementById('single-post-comments').prepend(comment);
        return; }
    else if ( ( isParent == 1 || isParent === true ) && gettingComment === true ) {
        document.getElementById('single-post-comments').appendChild(comment);
        return; }
    else if ( isParent == 0 || isParent === false ) {
        const childContainer = document.getElementById('comment-item-' + response.parent ).parentElement.querySelector('.comment-item-children');
        childContainer.classList.remove('hide');
        childContainer.prepend(commentItem);
        return; }
}


//Shows the child categories in the description section in 3D post creator
function showChildCategories( parent ) {
    const childCategoryInputs = document.querySelectorAll('.blender-child-categories');
    for ( let input of childCategoryInputs ) {
        input.classList.add('hide'); }
    for ( let input of childCategoryInputs ) {
        if ( input.id == parent ) {
            input.classList.remove('hide'); } }
    return;
}

function mobileMenu () {
    const menuIcon = document.getElementById('hamburger').querySelector('i');
    const menu     = document.getElementById('mobile-links');
    if (menu.style.display === 'flex' ) {
       menu.style.display = "none";
       menuIcon.className = 'fas fa-bars fa-2x'; }
    else {
       menu.style.display = "flex";
       menuIcon.className = 'fas fa-times fa-2x';
       setTimeout( function() {
           menu.style.display = "none";
           menuIcon.className = 'fas fa-bars fa-2x';
       }, 6000 ); }
    return;
}
