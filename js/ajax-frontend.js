// POST CREATOR HANDLERS AND HELPER FUNCTIONS START HERE
function addBlenderFile( mode ) {

    event.preventDefault();

    clearPostCreatorErrors();

    const file        = document.getElementsByName('add-blender-file')[0];
    const thumb       = document.getElementsByName('add-blender-thumb')[0];
    const postId      = document.getElementById('add-blender-post-id');

    //Validates file and thumbnail files if mode is add
    if ( mode === 'add' ) {
        if ( file.files[0] === undefined ) {
            postCreatorErrors( 'file', 'blender file is required.' );
            return; }

        if ( file.files[0] !== undefined ) {
            const fileName = file.files[0].name;
            if ( fileName.substr( ( fileName.lastIndexOf('.') + 1 ) ) !== 'blend' ) {
                postCreatorErrors( 'file', 'file must be .blend format.' );
                return;}
            if ( file.files[0].size / 1000024 > 20 ) {
                postCreatorErrors( 'file', 'blender file cannot exceed 20mb.' );
                return; } }

        if ( thumb.files[0] === undefined ) {
            postCreatorErrors( 'thumb', 'feature image is required.' );
            return; }
    }

    //Validates thumb
    if ( thumb.files[0] !== undefined ) {
        if ( thumb.files[0].type !== 'image/png' && thumb.files[0].type !== 'image/jpg' && thumb.files[0].type !== 'image/jpeg' ) {
            postCreatorErrors( 'thumb', 'feature image must be .jpg or .png' );
            return; }
        else if ( thumb.files[0].size / 1000024 > 2 ) {
            postCreatorErrors( 'thumb', 'feature image size limit is 2mb.' );
            return; } }

    togglePostCreatorLoaders ( 'file', 'show' );

    const formData = new FormData();
    formData.append( 'thumb', thumb.files[0] );
    formData.append( 'task', 'blender-file' );
    formData.append( 'mode', mode );
    formData.append( 'action', 'private_ajax_action' );
    formData.append( 'nonce', frontend_ajax.nonce );
    mode === 'add' ? formData.append( 'file', file.files[0]) : null;
    mode === 'edit' ? formData.append( 'post-id', postId.value ) : null;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader( 'enctype', 'multipart/form-data' );

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);
            console.log(response);
            if ( response === null ) {
                togglePostCreatorLoaders ( 'file', 'hide' );
                postCreatorErrors( 'file', response );
                return; }

            if ( typeof response === 'object' && 'success' in response ) {
                postCreatorSuccess( 'file', 'description', null )
                postId.value = response.success;
                return; }

            if ( typeof response === 'object' && 'thumb_edited' in response ) {
                postCreatorSuccess( 'file', 'description', null )
                if ( response.thumb_edited !== 'unchanged' ) {
                    document.getElementById('edit-blender-post-preview').src = response.thumb_edited; }
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                togglePostCreatorLoaders ( 'file', 'hide' );
                postCreatorErrors( 'file', response );
                return; }

            if ( typeof response === 'object' && 'error' in response || 'file' in response || 'thumb' in response ) {
                togglePostCreatorLoaders ( 'file', 'hide' );
                console.log(response);
                postCreatorErrors( 'multiple', response );
                return; }

        } else if ( this.status != 200 ) {
            togglePostCreatorLoaders ( 'file', 'hide' );
            postCreatorErrors( 'file', 'server error' );
            return; }
    };
    xhr.send(formData);
}


function addBlenderDescription() {

    event.preventDefault();

    clearPostCreatorErrors();
    togglePostCreatorLoaders ( 'description', 'show' );

    const data           = {};
    data.title           = document.querySelector('[name="blender-title"]').value;
    data.story           = document.querySelector('[name="blender-story"]').value;
    data.post_id         = document.getElementById('add-blender-post-id').value;
    data.nonce           = frontend_ajax.nonce;

    data.parent_category = document.querySelector('[name="blender-parent-category"]').value;
    if ( document.getElementById('blender-child-category-current') !== null ) {
        data.child_category = document.getElementById('blender-child-category-current').value; }
    else {
        const childCategory  = document.getElementById('blender-child-category-' + data.parent_category);
        if ( childCategory !== null ) {
            data.child_category = childCategory.firstElementChild.value;} }

    const tagInputs = document.querySelectorAll('[name="blender-tag"]');
    const tags = [];
    tagInputs.forEach(( tagInput ) => {
        tags.push( tagInput.value );
    });
    data.tags = tags;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( response === null ) {
                togglePostCreatorLoaders ( 'description', 'hide' );
                postCreatorErrors( 'description', response );
                return; }

            if ( response === 'success' ) {
                postCreatorSuccess( 'description', 'meta', null );
                return; }

            if ( typeof response === 'object' && ( 'title' in response || 'tags' in response || 'category' in response ) ) {
                togglePostCreatorLoaders ( 'description', 'hide' );
                postCreatorErrors( 'multiple', response );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                togglePostCreatorLoaders ( 'description', 'hide' );
                postCreatorErrors( 'description', response );
                return; }

        } else if ( this.status != 200 ) {
            togglePostCreatorLoaders ( 'description', 'hide' );
            postCreatorErrors( 'description', 'server error.' );
            return; }

    };
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=blender-description' );
}


function addBlenderMeta() {

    event.preventDefault();

    clearPostCreatorErrors();
    togglePostCreatorLoaders ( 'meta', 'show' );

    const data   = {};
    data.post_id = document.getElementById('add-blender-post-id').value;
    data.nonce   = frontend_ajax.nonce;

    document.getElementById("add-blender-post-meta-inputs").querySelectorAll('[type="text"]').forEach( metaInput => {
        const name = metaInput.name.replace( "blender-", "" ).replace( "-", "_" );
        data[name] = metaInput.value;
    });

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
    if ( this.readyState == 4 && this.status == 200 ) {

        const response = JSON.parse(this.response);

        if ( response === null ) {
            togglePostCreatorLoaders ( 'meta', 'hide' );
            postCreatorErrors( 'meta', response );
            return; }

        if ( response === 'success' && typeof response === 'string' ) {
            postCreatorSuccess( 'meta', 'options', null );
            return; }

        if ( typeof response === 'object' && 'error' in response ) {
            togglePostCreatorLoaders ( 'meta', 'hide' );
            postCreatorErrors( 'meta', response.error  );
            return; }

        if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean'  ) {
            togglePostCreatorLoaders ( 'meta', 'hide' );
            postCreatorErrors( 'meta', response );
            return; }

    } else if ( this.status != 200 ) {
        togglePostCreatorLoaders ( 'meta', 'hide' );
        postCreatorErrors( 'meta', 'server error.' );
        return; }
};
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=blender-meta' );
}


function addBlenderOptions() {

    event.preventDefault();

    clearPostCreatorErrors();
    togglePostCreatorLoaders ( 'options', 'show' );

    const data   = {};
    data.post_id = document.getElementById('add-blender-post-id').value
    data.license = document.querySelector('[name="blender-license"]').value;
    data.nonce   = frontend_ajax.nonce;

    document.querySelectorAll('[name="blender-comments[]"]').forEach( ( input ) => {
        if ( input.checked === true ) {
            data.comments = input.value;
            return; } } );

    document.querySelectorAll('[name="blender-download[]"]').forEach( ( input ) => {
        if ( input.checked === true ) {
            data.download = input.value;
            return; } } );

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
    if ( this.readyState == 4 && this.status == 200 ) {

        const response = JSON.parse(this.response);

        if ( response === null ) {
            togglePostCreatorLoaders ( 'options', 'hide' );
            postCreatorErrors ( 'options', response );
            return; }

        if ( typeof response === 'object' && 'success' in response ) {
            postCreatorSuccess( 'options', null, null );
            setTimeout( function() { window.location.replace( response.success ); }, 2000 );
            return; }

        if ( typeof response === 'object' && 'error' in response ) {
            togglePostCreatorLoaders ( 'options', 'hide' );
            postCreatorErrors ( 'options', response.error );
            return; }

        if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
            togglePostCreatorLoaders ( 'options', 'hide' );
            postCreatorErrors ( 'options', response );
            return; }

    } else if ( this.status != 200 ) {
        togglePostCreatorLoaders ( 'options', 'hide' );
        postCreatorErrors ( 'options', 'server error' );
        return; }

    };
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=blender-options' );
}


function confirmDeleteBlenderPost ( item ) {
    const object = document.querySelector("[data-gallery-item-id='" + item + "']");
    object.lastElementChild.classList.toggle('hide');
    return;
}


function deleteBlenderPost ( item ) {

    event.preventDefault();

    const data = {};
    data.post_id   = document.querySelector("[data-gallery-item-id='" + item + "']").getAttribute('data-gallery-item-id');
    data.nonce     = frontend_ajax.nonce;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
    if ( this.readyState == 4 && this.status == 200 ) {

        const response = JSON.parse(this.response);

        if ( response === null ) {
            alert( response.error );
            return; }

        if ( typeof response === 'object' && 'success' in response ) {

            const objectToRemove = document.querySelector("[data-gallery-item-id='" + response.success.ID.toString() + "']");
            objectToRemove.innerHTML = "";
            objectToRemove.classList.add('blender-item-deleted');
            const successMessage = document.createElement('small');
            successMessage.textContent = 'item deleted.';
            objectToRemove.appendChild(successMessage);
            setTimeout( function () { objectToRemove.remove(); }, 3000)
            return; }

        if ( typeof response === 'object' && 'error' in response ) {
            alert( response.error );
            return; }

        if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean'  ) {
            alert( response );
            return; }

    } else if ( this.status != 200 ) {
        alert( this.status );
        return;
    }

    };
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=blender-delete' );
}


function getCurrentPostCreatorElements ( current ) {
    const els = {};
    els.current_status  = current === 'file' ? document.getElementById("add-blender-post-" + current + "-status") : null;
    els.submit_loader   = current !== 'file' ? document.getElementById('submit-blender-' + current).firstElementChild : null;
    els.submit_buttons  = current !== 'file' ?  document.getElementById('submit-blender-' + current).lastElementChild: null;
    els.current_div     = document.getElementById('add-blender-post-' + current );
    els.current_title   = document.getElementById("add-blender-post-" + current + "-title");
    els.current_success = document.getElementById("add-blender-post-" + current + "-success");
    els.current_inputs  = document.getElementById("add-blender-post-" + current + "-inputs");
    return els; }


function getNextPostCreatorElements ( next ) {
    const els = {};
    els.next_div        = document.getElementById('add-blender-post-' + next );
    els.next_title      = document.getElementById('add-blender-post-' + next + '-title')
    els.next_success    = document.getElementById("add-blender-post-" + next + "-success");
    els.next_inputs     = document.getElementById("add-blender-post-" + next + "-inputs");
    return els; }


function clearPostCreatorErrors() {
    const currentErrors = document.querySelectorAll('.error');
    if ( currentErrors.length > 0 ) { currentErrors.forEach(( error ) => { error.remove(); }); } }


function togglePostCreatorLoaders ( current, state ) {
    const els = getCurrentPostCreatorElements( current );
    if ( current === 'file' ) {
        els.current_inputs.classList.toggle('hide');
        els.current_title.classList.toggle('hide');
        els.current_status.classList.toggle('hide'); }
    else {
        els.submit_loader.classList.toggle('hide');
        els.submit_buttons.classList.toggle('hide'); }

    if ( current === 'file' && state === 'show' ) {
        els.current_div.className = 'container-inner-reduced-padding loader-gradient'; }
    else if ( current === 'file' && state === 'hide' ) {
        els.current_div.className = 'container-inner'; }
}


function postCreatorSuccess ( current, next, responseObject ) {

    const cEls = getCurrentPostCreatorElements( current );

    //Hides loaders and shows buttons
    if ( current !== 'file' ) {
        cEls.submit_loader.classList.toggle('hide');
        cEls.submit_buttons.classList.toggle('hide'); }
    else if ( current === 'file' ) {
        cEls.current_status.classList.toggle('hide'); }

    //Hides inputs and title, if not file section.
    if ( current !== 'file' ) {
        cEls.current_title.classList.toggle('hide');
        cEls.current_inputs.classList.toggle('hide'); }

    //Shows success feedback
    cEls.current_success.classList.toggle('hide');
    cEls.current_div.className = 'container-inner-reduced-padding green-gradient';
    cEls.current_div.scrollIntoView({behavior: 'smooth'});

    // If not last section, show next section
    if ( next !== null ) {
        const nEls = getNextPostCreatorElements ( next );

        setTimeout(function() {
            nEls.next_div.className = 'container-inner';
            nEls.next_inputs.classList.toggle('hide');
            nEls.next_div.scrollIntoView({behavior: 'smooth'});
    }, 1250 ); } }


function postCreatorPrevious ( current, previous ) {

    event.preventDefault();

    const cEls = getCurrentPostCreatorElements( current );
    const pEls = getNextPostCreatorElements( previous );

    cEls.current_inputs.classList.toggle('hide');
    cEls.current_div.className = 'container-inner-reduced-padding';
    pEls.next_div.className = 'container-inner';
    pEls.next_success.classList.toggle('hide');
    pEls.next_inputs.classList.toggle('hide');
    pEls.next_title.classList.toggle('hide');
    pEls.next_div.scrollIntoView({behavior: 'smooth'}); }


function postCreatorErrors ( param, responseObject ) {

    const errorMessage       = document.createElement('small');
    errorMessage.className   = "error text-right";
    if ( param !== 'multiple' ) {
        errorMessage.textContent = responseObject;}
    let container;

    switch ( param ) {
        case 'multiple':
            const errorType = Object.keys(responseObject);
            const error     = Object.values(responseObject);
            console.log(errorType[0]);
            errorMessage.textContent = error[0];
            container = document.getElementById( 'add-blender-' + errorType[0]);
            container.prepend(errorMessage);
            container.scrollIntoView({behavior: 'smooth'});
            break;
        case 'file':
            document.getElementById('add-blender-file').prepend(errorMessage);
            break;
        case 'thumb':
            document.getElementById('add-blender-thumb').prepend(errorMessage);
            break;
        case 'description':
            container = document.getElementById( 'add-blender-title');
            container.prepend(errorMessage);
            container.scrollIntoView({behavior: 'smooth'});
            break;
        case 'meta':
            container = document.getElementById('add-blender-post-meta');
            container.prepend(errorMessage);
            container.scrollIntoView({behavior: 'smooth'});
            break;
        case 'options':
            container = document.getElementById('add-blender-post-options');
            container.appendChild(errorMessage);
            container.scrollIntoView({behavior: 'smooth'});
            break;
        default:
        break;
    } }


//UPDATE PROFILE/USER HANDLERS AND HELPER FUNCTIONS START HERE
function uploadProfileMedia( trigger ) {

    event.preventDefault();

    const fileSource       = trigger.id;
    const imageType        = fileSource.replace( 'upload-' , '' );
    const imageMetaID      = imageType.split("-")[2];
    const file             = document.getElementById('upload-' + imageType);
    const fileSize         = Math.round( file.files[0].size / 1024 );
    const image            = document.getElementById( imageType );
    const currentImageID   = image.getAttribute('data-' + imageType);
    const imageLoader      = document.getElementById( imageType + '-loader' );

    if ( fileSize > 1500 ) {
        alert( 'File size may not exceed 1.5 mb' );
        return; }

    function toggleLoader () {
        image.classList.toggle('hide');
        imageLoader.classList.toggle('hide'); }

    toggleLoader();

    const formData = new FormData();
    formData.append( 'file', file.files[0]);
    formData.append( 'current-image', currentImageID );
    formData.append( 'image-type', imageType );
    formData.append( 'image-meta-ID', imageMetaID );
    formData.append( 'task', 'upload-media' );
    formData.append( 'action', 'private_ajax_action' );
    formData.append( 'nonce', frontend_ajax.nonce );

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader( 'enctype', 'multipart/form-data' );

    xhr.onreadystatechange = function() {
    if ( this.readyState == 4 && this.status == 200 ) {

        const response = JSON.parse(this.response);

        if ( typeof response === 'object' && 'image' in response ) {
            image.setAttribute('data-' + imageType, response.id );
            image.src = response.image;
            toggleLoader();
            return; }

        if ( typeof response === 'object' && 'error' in response ) {
            toggleLoader();
            alert(response.error);
            return; }

        if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
            toggleLoader();
            alert(response.error);
            return; }

    } else if ( this.status != 200 ) {
        toggleLoader();
        alert(response.error);
        return; }
    };

    xhr.send(formData);
}


function deleteProfileMedia ( imageType ) {

    event.preventDefault();

    const image           = document.getElementById( imageType );
    const imageLoader     = document.getElementById( imageType + '-loader' );
    const data = {};
    data.current_image_ID = image.getAttribute('data-' + imageType);
    data.nonce            = frontend_ajax.nonce;
    data.image_type       = imageType;

    function toggleLoaders() {
        image.classList.toggle('hide');
        imageLoader.classList.toggle('hide'); }

    toggleLoaders();

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'object' && 'image' in response ) {
                image.setAttribute('data-' + imageType, response.id );
                image.src = response.image;
                toggleLoaders(); }

            if ( typeof response === 'object' && 'error' in response ) {
                toggleLoaders();
                alert(response.error); }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                toggleLoaders();
                alert(response); }

        } else if ( this.status != 200 ) {
            toggleLoaders();
            alert('server error');
            return; }
    }
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=delete-media' );
}


function updateProfileDetails ( section ) {

    event.preventDefault();

    removeErrors( 'update-profile-details' );

    const bio          = document.getElementById('update-profile-bio');
    const profession   = document.getElementById('update-profile-profession');
    const mainCategory = document.getElementById('update-profile-main-category')
    const city         = document.getElementById('update-profile-city');
    const country      = document.getElementById('update-profile-country');
    const background   = document.getElementById('update-profile-background');
    const colour       = document.getElementById('update-profile-contrast-colour');

    const data = {};
    data.nonce         = frontend_ajax.nonce;
    data.bio           = bio.value;
    data.profession    = profession.value;
    data.main_category = mainCategory.value;
    data.city          = city.value;
    data.country       = country.value;
    data.background    = background.value;
    data.colour        = colour.value;
    const tagInputs    = document.querySelectorAll('[name="profile-tag"]');
    const tags = [];
    tagInputs.forEach(( tagInput ) => {
        tags.push( tagInput.value );
    });
    data.tags = tags;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'object' && 'profile_details' in response ) {
                bio.value          = response.bio;
                city.value         = response.profile_details.city;
                country.value      = response.profile_details.country;
                background.value   = response.profile_styling.background;
                colour.value       = response.profile_styling.colour;

                bio.style.border = '2px solid green';
                const inputEls = [ profession, mainCategory, city, country, background, colour ];
                inputSuccessFeedback( inputEls );
                const tags = document.getElementsByClassName('tag-item');
                for ( let tag of tags ) {
                    tag.classList.toggle('tag-item-success'); }

                setTimeout (function() {
                    bio.style.border = '2px solid #E0EEF8';
                    inputSuccessFeedback( inputEls );
                    for ( let tag of tags ) {
                        tag.classList.toggle('tag-item-success'); }
                }, 2000);
                return;
            }

            if ( typeof response === 'object' && 'error' in response ) {
                setErrors ( 'update-profile-details', response.error );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                setErrors ( 'update-profile-details', response );
                return; }

        } else if ( this.status != 200 ) {
            setErrors ( 'update-profile-details', 'server error' );
            return; }
    }
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=profile-details' );
}


function updateProfileSocial () {

    event.preventDefault();

    removeErrors( 'update-profile-social' );

    const website      = document.getElementById('update-profile-website');
    const facebook     = document.getElementById('update-profile-facebook');
    const instagram    = document.getElementById('update-profile-instagram');
    const twitter      = document.getElementById('update-profile-twitter');
    const youtube      = document.getElementById('update-profile-youtube');
    const linkedin     = document.getElementById('update-profile-linkedin');

    const data = {};
    data.nonce         = frontend_ajax.nonce;
    data.website       = website.value;
    data.facebook      = facebook.value;
    data.instagram     = instagram.value;
    data.twitter       = twitter.value;
    data.youtube       = youtube.value;
    data.linkedin      = linkedin.value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);
                        console.log(response);
            if ( typeof response === 'object' && 'profile_social' in response ) {
                website.value   = response.profile_social.website;
                facebook.value  = response.profile_social.facebook;
                instagram.value = response.profile_social.instagram;
                twitter.value   = response.profile_social.twitter;
                youtube.value   = response.profile_social.youtube;
                linkedin.value  = response.profile_social.linkedin;

                const inputEls = [ website, facebook, instagram, twitter, youtube, linkedin ];
                inputSuccessFeedback( inputEls );
                setTimeout (function() {
                    inputSuccessFeedback( inputEls );
                }, 2000);
                return; }

            if ( typeof response === 'object' && 'error' in response ) {
                setErrors ( 'update-profile-social', response.error );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                setErrors ( 'update-profile-social', response );
                return; }

        } else if ( this.status != 200 ) {
            setErrors ( 'update-profile-social', 'server-error' );
            return;
        }
    }
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=profile-social' );
}


function updateProfileContact () {

    event.preventDefault();

    removeErrors( 'update-profile-contact' );

    const contactMe    = document.getElementById('update-profile-contact-me');
    const resetChecked = contactMe.checked === false ? true : false;
    const data         = {};
    data.nonce         = frontend_ajax.nonce;
    data.contact       = contactMe.checked;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'object' && 'profile_contact' in response ) {
                return; }

            if ( typeof response === 'object' && 'error' in response ) {
                setErrors ( 'update-profile-contact', response.error );
                document.getElementById('update-profile-contact-me').checked = resetChecked;
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                setErrors ( 'update-profile-contact', response );
                document.getElementById('update-profile-contact-me').checked = resetChecked;
                return; }

        } else if ( this.status != 200 ) {
            setErrors ( 'update-profile-contact', 'server error');
            document.getElementById('update-profile-contact-me').checked = resetChecked;
            return; }
    }
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=profile-contact' );
}


function updateUserDetails () {

    event.preventDefault();

    removeErrors( 'update-user-details' );

    const firstName = document.getElementById('user-first-name');
    const lastName  = document.getElementById('user-last-name');
    const data      = {};
    data.nonce      = frontend_ajax.nonce;
    data.first_name = firstName.value;
    data.last_name  = lastName.value;

    const xhr = new XMLHttpRequest();

    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'object' && 'success' in response ) {
                firstName.value = response.success.first_name;
                lastName.value  = response.success.last_name;

                const inputEls = [ firstName, lastName ];
                inputSuccessFeedback( inputEls );

                setTimeout (function() {
                    inputSuccessFeedback( inputEls );
                }, 2000);
                return; }

            if ( typeof response === 'object' && 'error' in response ) {
                setErrors ( 'update-user-details', response.error );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                setErrors ( 'update-user-details', response );
                return; }

        } else if ( this.status != 200 ) {
            setErrors ( 'update-user-details', 'server error' );
            return;
        }
    }
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=user-details' );
}


function changeEmail () {

    event.preventDefault();

    removeErrors( 'change-email' );
    removeInputErrors();

    const newEmail      = document.getElementById('change-email-new');
    const confirmEmail  = document.getElementById('change-email-confirm');

    const data = {};
    data.new_email     = newEmail.value;
    data.confirm_email = confirmEmail.value;
    data.nonce         = frontend_ajax.nonce;

    const xhr = new XMLHttpRequest();

    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'string' && response === 'success' ) {

                document.getElementById('change-email-form').reset();
                document.getElementById('change-email-form').classList.toggle('hide');
                document.getElementById('change-email-success').classList.toggle('hide');
                setTimeout( function() {
                    location.reload();
                    location.onload = toggleOption('user-details', 'creators-settings');
                }, 10000 );
                return;
            }

            if ( typeof response === 'object' && 'errors' in response ) {
                setInputErrors ( 'change-email', response, 'append' );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                setErrors ( 'change-email', response );
                return; }

        } else if ( this.status != 200 ) {
            setErrors ( 'change-email', 'server error' );
            return; }
    }
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=change-email' );
}


function cancelChangeEmail () {

    event.preventDefault();

    removeErrors( 'verify-email' );

    const data = {};
    data.nonce = frontend_ajax.nonce;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'string' && response === 'success' ) {
                document.getElementById('cancel-change-email-success').classList.toggle('hide');
                document.getElementById('verify-email-form').classList.toggle('hide');
                setTimeout( function() {
                    document.getElementById('change-email-form').classList.toggle('hide');
                    document.getElementById('cancel-change-email-success').classList.toggle('hide');
                }, 3000 );
                return; }

            if ( typeof response === 'object' && 'error' in response ) {
                setErrors ( 'verify-email', response.error );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                setErrors ( 'verify-email', response );
                return; }

        } else if ( this.status != 200 ) {
            setErrors ( 'verify-email', 'server error' );
            return; }
    }
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=cancel-change-email' );
}


function resendChangeEmail () {

    event.preventDefault();

    removeErrors( 'verify-email' );

    const data = {};
    data.nonce = frontend_ajax.nonce;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'string' && response === 'success' ) {

                document.getElementById('resend-change-email-success').classList.toggle('hide');
                setTimeout( function() {
                    document.getElementById('resend-change-email-success').classList.toggle('hide');
                }, 3000 );
                return; }

            if ( typeof response === 'object' && 'error' in response ) {
                setErrors ( 'verify-email', response.error );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                setErrors ( 'verify-email', response );
                return; }

        } else if ( this.status != 200 ) {
            setErrors ( 'verify-email', 'server error' );
            return; }
    }
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=resend-change-email' );
}


function verifyEmail ( ) {

    event.preventDefault();

    removeErrors( 'verify-email' );

    const data = {};
    data.nonce = frontend_ajax.nonce;
    data.key   = document.getElementById('verify-email-key').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'object' && 'success' in response ) {
                document.getElementById('verify-email-form').classList.add('hide');
                document.getElementById('change-email-form').classList.add('hide');
                document.getElementById('verify-email-success').classList.remove('hide');
                document.getElementById('change-email-current').lastElementChild.textContent = response.success;
                setTimeout( function() {
                    document.getElementById('verify-email-success').classList.add('hide');
                    document.getElementById('change-email-form').classList.remove('hide');
                }, 6000 );
                return; }

            if ( typeof response === 'object' && 'error' in response ) {
                setErrors ( 'verify-email', response.error );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                setErrors ( 'verify-email', response );
                return; }

        } else if ( this.status != 200 ) {
            setErrors ( 'verify-email', 'server error' );
            return; }
    }
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=verify-email' );
}


function changePassword () {

    event.preventDefault();

    removeErrors( 'change-password' );
    removeInputErrors();

    const data   = {};
    data.nonce   = frontend_ajax.nonce;
    data.current = document.getElementById('change-password-current').value;
    data.new     = document.getElementById('change-password-new').value;
    data.confirm = document.getElementById('change-password-confirm').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'object' && 'success' in response  ) {
                document.getElementById('change-password-form').reset();
                document.getElementById('change-password-success').classList.remove('hide');
                document.getElementById('change-password-inputs').classList.add('hide');
                setTimeout( function() {
                    document.getElementById('change-password-success').classList.add('hide');
                    document.getElementById('change-password-inputs').classList.remove('hide');
                }, 6000 );
                return;
            }

            if ( typeof response === 'object' && 'errors' in response ) {
                setInputErrors ( 'change-password', response, 'prepend' );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                setErrors( 'change-password', response );
                return; }

        } else if ( this.status != 200 ) {
            setErrors( 'change-password', 'server-error' );
            return; }
    }
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=change-password' );
}


function inputSuccessFeedback( els ) {
    for ( let el of els ) {
        el.classList.toggle('input-success'); }
}


//CONTACT HANDLER STARTS HERE
function contact( option, profile ) {

    event.preventDefault();

    let data = {};
    if ( option === 'profile-as-public' || option === 'admin-as-public' ) {
        data.name  = document.getElementById('contact-name').querySelector("input[type=text]").value;
        data.email = document.getElementById('contact-email').querySelector("input[type=email]").value; }
    if ( option === 'profile-as-public' || option === 'profile-as-user' ) {
        data.receiver = profile; }
    data.subject = document.getElementById('contact-subject').querySelector("input[type=text]").value
    data.message = document.getElementById('contact-message').querySelector("textarea").value;
    data.nonce   = frontend_ajax.nonce;
    data.option  = option;

    const oldErrors = document.querySelectorAll('.error');
    if ( oldErrors.length > 0 ) {
        for ( let oldError of oldErrors ) {
            oldError.remove(); }
    }

    function contactErrors ( object, type, field ) {
        const errorContainer = document.getElementById('contact-' + field );
        const errorMessage = document.createElement('small');
        errorMessage.className = 'error';
        if ( type === 'object' ) {
            errorMessage.textContent = object[Object.keys(object)[0]]; }
        else if ( type === 'primitive' ) {
            errorMessage.textContent = object; }
        else if ( type = 'status' ) {
            errorMessage.textContent = 'error' + this.status; }
        errorContainer.appendChild(errorMessage); }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'string' && response === 'success' ) {
                const form = document.getElementById('contact-form');

                let success;
                if ( option === 'profile-as-public' || option === 'admin-as-public' ) {
                    success = document.getElementById('connect-contact-success'); }
                else {
                    success = document.getElementById('profile-contact-success'); }
                form.classList.toggle('hide');
                success.classList.toggle('hide');
                setTimeout( function() {
                    form.reset();
                    form.classList.toggle('hide');
                    success.classList.toggle('hide');
                }, 5000 );
                return; }

            if ( typeof response === 'object' && 'error' in response ) {
                contactErrors( response, 'object', 'error' );
                return; }

            if ( typeof response === 'object' && 'email' in response ) {
                contactErrors( response, 'object', 'email' );
                return; }

            if ( typeof response === 'object' && 'message' in response ) {
                contactErrors( response, 'object', 'message' );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' && response !== 'success' ) {
                contactErrors( 'primitive', 'error' );
                return; }

        } else if ( this.status != 200 ) {
            contactErrors( null, 'status', 'error' );
            return; }
    }

    xhr.send('action=all_ajax_action&data=' + JSON.stringify(data) + '&task=contact' );

}

//COMMENT HANDLERS AND HELPER FUNCTIONS GO HERE
function getComments( isParent, parent ) {

    toggleResponseError( 'comments', 'enter' );

    const data     = {};
    data.ID        = ( isParent == 1 || isParent == true ) ? frontend_ajax.post_id : parent;
    data.is_parent = isParent;

    const xhr = new XMLHttpRequest();
    xhr.open('GET', frontend_ajax.ajax_url + '?action=private_ajax_action&data=' + JSON.stringify(data) + '&task=get-comments', true );
    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response  = JSON.parse(this.response);
            const container = isParent === true ? document.getElementById('single-post-comments') : document.getElementById('comment-item-' + parent );
            console.log(response);
            if ( typeof response === 'object' && 'success' in response ) {
                if ( ( isParent == 1 || isParent == true ) && response.success.parents.length == 0 ) {
                    document.getElementById('comments-results-loader').classList.add('hide');
                    return; }

                if ( isParent == 1 || isParent == true ) {
                    response.success.parents.forEach( comment => {
                        commentUI( comment , isParent, true );
                    });
                    console.log(response.success.children);
                }

                document.getElementById('comments-results-loader').classList.add('hide');
                return;

            }


            if ( typeof response === 'object' &&  'error' in response ) {
                console.log(response.error);
                toggleResponseError( 'comments', 'exit' );
                return;
            }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                console.log(this.response);
                toggleResponseError( 'comments', 'exit' );
                return;
            }

        } else if ( this.status != 200 ) {
            console.log(this.status);
            toggleResponseError( 'comments', 'exit' );
            return;
        }
    }

    xhr.send();
}


function commentHandler ( commentID, isParent, task ) {

    event.preventDefault();

    const data = {}
    data.comment_ID = commentID;
    data.is_parent  = isParent;
    data.post_ID    = frontend_ajax.post_id;
    data.nonce      = frontend_ajax.nonce;

    const id = task === 'add' ? frontend_ajax.post_id : commentID;
    if ( task === 'reply' || task === 'edit' || task === 'add' ) {
        comment = ( task === 'reply' || task === 'edit' ) ? document.getElementById('comment-item-' + id ) : document.getElementById('add-comment');
        data.content = comment.querySelector('[name="comment-' + task + '"]' ).value;
        if ( data.content.length == 0 || data.content === ' ' ) { return; }
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
    xhr.onreadystatechange = function() {
    if ( this.readyState == 4 && this.status == 200 ) {

        const response = JSON.parse(this.response);

        if ( 'success' in response && ( task === 'add' || task === 'reply' ) ) {
            commentUI( response.success , isParent, false );
            return; }

        if (  ( 'success' in response && task === 'edit' )  ) {
            commentResponseSuccess( id , task );
            return; }

        if ( response === 'deleted'  ) {
            commentResponseSuccess( id , task );
            return; }

        if ( 'errors' in response ) {
            console.log(response);
            commentResponseError( id , task );
            return; }

        if ( ( typeof response === 'string' && response !== 'deleted' )
                || typeof response === 'number' || typeof response === 'boolean'
                    || typeof response === undefined || typeof response === null ) {
            console.log(this.response);
            commentResponseError( id , task );
            return; }

    } else if ( this.status != 200 ) {
        console.log(this.status);
        commentResponseError( id , task );
        return;}

    }
    xhr.send('action=private_ajax_action&data=' + JSON.stringify(data) + '&task=' + task + '-comment' );
}

function commentResponseSuccess( id, task ) {
    const container = document.getElementById('comment-item-' + id );
    container.classList.add('hide');
    container.parentElement.classList.add('comment-success');
    const message   = document.createElement('small');
    message.textContent = task === 'delete' ? 'comment deleted.' : 'comment edited.';
    container.parentElement.prepend(message);
    setTimeout( function() {
        if ( task === 'delete' ) {
            container.parentElement.remove();
        } else if ( task === 'edit') {
            container.querySelector('.comment-content').querySelector('small').textContent  = response.success.content;
            container.querySelector('.comment-edit').querySelector('textarea').value        = response.success.content;
            container.querySelector('.comment-reply').querySelector('.italic').textContent  = response.success.content;
            container.querySelector('.comment-delete').querySelector('.italic').textContent = response.success.content;
            container.parentElement.classList.remove('comment-success');
            container.parentElement.firstElementChild.remove();
            container.classList.remove('hide');
        }
    }, 3000 );
    return; }

function commentResponseError ( id, task ) {
    const container = document.getElementById('comment-item-' + id );
    container.classList.add('hide');
    const message   = document.createElement('small');
    container.parentElement.classList.add('comment-error');
    if ( task === 'delete' ) {
        message.textContent = 'unable to delete comment.'; }
    else if ( task === 'edit' ) {
        message.textContent = 'unable to edit comment.' }
    else if ( task === 'reply' ) {
        message.textContent = 'unable to post reply.'; }
    container.parentElement.prepend(message);
    setTimeout( function() {
        container.parentElement.classList.remove('comment-error');
        container.parentElement.firstElementChild.remove();
        container.classList.remove('hide');
    }, 3000 );
    return; }


// CREATORS SPACE HANDLERS START HERE
function signIn () {

    event.preventDefault();

    const data = {};
    data.email    = document.getElementById('sign-in-email').value;
    data.pass     = document.getElementById('sign-in-pass').value;
    data.nonce    = frontend_ajax.nonce;

    removeErrors ( 'sign-in' );

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'object' && 'sign_in' in response ) {
                location.href = response.sign_in;
                return; }

            if ( typeof response === 'object' && 'activate' in response ) {
                location.replace(response.activate);
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                setErrors ( 'sign-in', response );
                return; }

            if ( typeof response === 'object' && 'error' in response ) {
                setErrors ( 'sign-in', response.error );
                return; }

        } else if ( this.status != 200 ) {
            setErrors ( 'sign-in', 'server error' );
            return; }
    }
    xhr.send('action=all_ajax_action&data=' + JSON.stringify(data) + '&task=sign-in' );
}


function signUp () {

    event.preventDefault();

    removeErrors ( 'sign-up' );
    removeInputErrors();

    const data = {};
    data.username     = document.getElementById('sign-up-username').value;
    data.email        = document.getElementById('sign-up-email').value;
    data.pass         = document.getElementById('sign-up-password').value;
    data.confirm_pass = document.getElementById('sign-up-confirm').value;
    data.nonce        = frontend_ajax.nonce;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'string' && response === 'success' ) {
                document.getElementById('sign-up-form').reset();
                document.getElementById('sign-up-form').classList.toggle('hide');
                document.getElementById('sign-up-success').classList.toggle('hide');
                return; }

            if ( typeof response === 'object' ) {
                setInputErrors ( 'sign-up', response, 'prepend' );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' && response !== 'success'  ) {
                setErrors ( 'sign-up', response );
                return; }

        } else if ( this.status != 200 ) {
            setErrors ( 'sign-up', 'server error' );
            return; }
    }
    xhr.send('action=all_ajax_action&data=' + JSON.stringify(data) + '&task=sign-up' );
}


function resetPassword () {

    event.preventDefault();

    removeErrors ( 'reset-password' );

    const data = {};
    data.email = document.getElementById('reset-password-email').value;
    data.nonce =frontend_ajax.nonce;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'string' && response === 'success' ) {
                document.getElementById('reset-password-form').reset();
                document.getElementById('reset-password-form').classList.toggle('hide');
                document.getElementById('reset-password-success').classList.toggle('hide');
                setTimeout( function() {
                    document.getElementById('reset-password-form').classList.toggle('hide');
                    document.getElementById('reset-password-success').classList.toggle('hide');
                }, 6000 );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                setErrors ( 'reset-password', response );
                return; }

            if ( typeof response === 'object' && 'error' in response ) {
                setErrors ( 'reset-password', response.error );
                return; }

        } else if ( this.status != 200 ) {
            setErrors ( 'reset-password', 'server error' );
            return; }
    }
    xhr.send('action=all_ajax_action&data=' + JSON.stringify(data) + '&task=reset' );
}


function activateAccount () {

    event.preventDefault();

    const data = {};
    data.email    = document.getElementById('activate-account-email').value;
    data.password = document.getElementById('activate-account-password').firstElementChild.value;
    data.key      = document.getElementById('activate-account-key').value;
    data.nonce    = frontend_ajax.nonce;

    removeErrors( 'activate-account' );

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'object' && 'success' in response ) {
                document.getElementById('activate-account-form').classList.toggle('hide');
                document.getElementById('activate-account-success').classList.toggle('hide');
                setTimeout( function() {
                    location.replace( response.success );
                    return;
                }, 4000 ); }

            if ( typeof response === 'object' && 'error' in response ) {
                setErrors( 'activate-account', response.error );
                return; }

            if ( typeof response === 'object' && 'sign_in_failed' in response ) {
                setErrors( 'activate-account', 'unable to sign you in. re-directing to sign in page.' );
                setTimeout( function() {
                    location.replace( response.sign_in_failed );
                }, 2500 );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                setErrors( 'activate-account', response );
                return; }

        } else if ( this.status != 200 ) {
            setErrors( 'activate-account', 'server error' );
            return; }
    }
    xhr.send('action=all_ajax_action&data=' + JSON.stringify(data) + '&task=activate' );
}

//Toggles the activate and resend activation keys on activate-account-php
function activateAccountOptions( option ) {
    document.getElementById('activate-account-password').classList.toggle('hide');
    document.getElementById('activate-account-key').classList.toggle('hide');
    document.getElementById('activate-account-submit').classList.toggle('hide');
    document.getElementById('resend-activation-key-option').classList.toggle('hide');
    document.getElementById('go-back-to-activate-option').classList.toggle('hide');
    document.getElementById('activate-account-resend-submit').classList.toggle('hide');
    return;
}

function resendActivationKey() {

    event.preventDefault();

    const data = {};
    data.email = document.getElementById('activate-account-email').value;
    data.nonce = frontend_ajax.nonce;

    removeErrors( 'activate-account' );

    const xhr = new XMLHttpRequest();
    xhr.open('POST', frontend_ajax.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            if ( typeof response === 'string' && response === 'success' ) {
                document.getElementById('activate-account-form').classList.toggle('hide');
                document.getElementById('resend-key-success').classList.toggle('hide');
                setTimeout( function() {
                    location.reload();
                    return;
                }, 10000 );
            }

            if ( typeof response === 'object' && 'error' in response ) {
                setErrors( 'activate-account', response.error );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                setErrors( 'activate-account', response );
                return; }

        } else if ( this.status != 200 ) {
            setErrors( 'activate-account', 'server error.' );
            return; }
    }
    xhr.send('action=all_ajax_action&data=' + JSON.stringify(data) + '&task=resend' );
}


// GET OBJECT HANDLERS START HERE
function getObjects ( objectID, objectName, origin, outputType, pagAmount ) {

    toggleResponseError( origin, 'enter' );

    const data       = {};
    data.object_ID   = objectID;
    data.origin      = origin;
    data.output_type = outputType;

    const xhr = new XMLHttpRequest();
    xhr.open('GET', frontend_ajax.ajax_url + '?action=all_ajax_action&data=' + JSON.stringify(data) + '&task=get-objects', true );
    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response  = JSON.parse(this.response);
            const container = document.getElementById( origin + '-results-content');

            //If output on page-discover.php
            if ( typeof response === 'object' && 'discover' in response ) {
                const postObjects = Object.entries(response.discover);
                //If CPT blender
                if ( outputType === 'blender' || outputType === 'profile' ) {
                    if ( postObjects.length > pagAmount ) {
                        const firstPaginationChunk = postObjects.slice( 0, pagAmount );
                        firstPaginationChunk.forEach( ( post ) => {
                            if ( post[1].status === 'publish' ) {
                                searchPreview( container, post ); }
                            if ( post[1].status === 'draft' ) {
                                searchPreviewDraft( container, post ); } });
                        generateObjectsPaginatorUI( pagAmount, postObjects.length, objectID, origin, outputType );
                    } else {
                        postObjects.forEach( ( post ) => {
                            if ( post[1].status === 'publish' ) {
                                searchPreview( container, post ); }
                            if ( post[1].status === 'draft' ) {
                                searchPreviewDraft( container, post ); } });
                    } }
                    document.getElementById( origin + '-results-loader').classList.add('hide');
                    return; }

            //If output on single-profile-php
            if ( typeof response === 'object' && 'profile_gallery' in response ) {
                console.log(response);
                const postObjects = Object.entries(response.profile_gallery);
                if ( postObjects.length > pagAmount ) {
                    const firstPaginationChunk = postObjects.slice( 0, pagAmount );
                    firstPaginationChunk.forEach( ( post ) => {
                        profileBlenderPreview( container, post ); });
                    generateObjectsPaginatorUI( pagAmount, postObjects.length, objectID, origin, outputType ); }
                else {
                    postObjects.forEach( ( post ) => {
                        profileBlenderPreview( container, post ); }); }
                    document.getElementById( origin + '-results-loader').classList.add('hide');
                    return; }

            //If output on page-taxonomy.php
            if ( typeof response === 'object' && 'taxonomy' in response ) {
                const postObjects = Object.entries(response.taxonomy);
                if ( postObjects.length > pagAmount ) {
                    const firstPaginationChunk = postObjects.slice( 0, pagAmount );
                    firstPaginationChunk.forEach( ( post ) => {
                        searchPreview( container, post ); });
                    generateObjectsPaginatorUI( pagAmount, postObjects.length, objectID, origin, outputType );
                } else {
                    postObjects.forEach( ( post ) => {
                        searchPreview( container, post ); }); }
                    document.getElementById( origin + '-results-loader').classList.add('hide');
                    return; }

            if ( typeof response === 'object' && 'blog' in response ) {
                const postObjects = Object.entries(response.blog);
                if ( postObjects.length > pagAmount ) {
                    const firstPaginationChunk = postObjects.slice( 0, pagAmount );
                    firstPaginationChunk.forEach( ( post ) => {
                        blogPreview( container, post ); });
                    generateObjectsPaginatorUI( pagAmount, postObjects.length, null, origin, outputType );
                } else {
                    postObjects.forEach( ( post ) => {
                        blogPreview( container, post ); }); }
                    document.getElementById( origin + '-results-loader').classList.add('hide');
                    return;
            }

            if ( typeof response === 'object' && 'error' in response ) {
                console.log(response.error);
                toggleResponseError( origin, 'exit' );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                console.log(this.response);
                toggleResponseError( origin, 'exit' );
                return; }

        } else if ( this.status != 200 ) {
            console.log(this.status);
            toggleResponseError( origin, 'exit' );
            return; }
    }
    xhr.send();
}


//The function that paginates post results which where first retrievd with getObjects() function
function paginateObjects ( trigger, direction, objectID, origin, outputType, pagAmount ) {

    let newIndexes = getSetPaginatorUi( trigger, direction, origin, pagAmount );
    clearPreviosResults( origin );
    toggleResponseError( origin, 'enter' );
    const data       = {};
    data.object_ID   = objectID;
    data.origin      = origin;
    data.output_type = outputType;

    const xhr = new XMLHttpRequest();
    xhr.open('GET', frontend_ajax.ajax_url + '?action=all_ajax_action&data=' + JSON.stringify(data) + '&task=paginate-objects', true );
    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response  = JSON.parse(this.response);
            const container = document.getElementById( origin + '-results-content');
            const loader    = document.getElementById( origin + '-results-loader');
            
            //If output on page-discover.php
            if ( typeof response === 'object' && 'discover' in response ) {
                const postObjects = Object.entries(response.discover);
                if ( outputType === 'blender' || outputType === 'profile' ) {
                    postObjects.forEach( ( post, index ) => {
                        if ( index >= newIndexes.first && index <= newIndexes.last ) {
                            if ( post[1].status === 'publish' ) {
                                searchPreview( container, post ); }
                            if ( post[1].status === 'draft' ) {
                                searchPreviewDraft( container, post ); } } }); }
                loader.classList.add('hide');
                container.scrollIntoView(true);
                return; }

            //If output on single-profile-php
            if ( typeof response === 'object' && 'profile_gallery' in response ) {
                const postObjects = Object.entries(response.profile_gallery);
                postObjects.forEach( ( post, index ) => {
                    if ( index >= newIndexes.first && index <= newIndexes.last ) {
                        profileBlenderPreview( container, post ); } });
                loader.classList.add('hide');
                container.scrollIntoView(true);
                return; }

            //If output on page-taxonomy.php
            if ( typeof response === 'object' && 'taxonomy' in response ) {
                const postObjects = Object.entries(response.taxonomy);
                postObjects.forEach( ( post, index ) => {
                    if ( index >= newIndexes.first && index <= newIndexes.last ) {
                        searchPreview( container, post ); } });
                loader.classList.add('hide');
                container.scrollIntoView(true);
                return; }

            //If output on page-blog.php
            if ( typeof response === 'object' && 'blog' in response ) {
                const postObjects = Object.entries(response.blog);
                postObjects.forEach( ( post, index ) => {
                    if ( index >= newIndexes.first && index <= newIndexes.last ) {
                        blogPreview( container, post ); } });
                loader.classList.add('hide');
                container.scrollIntoView(true);
                return; }

            if ( typeof response === 'object' && 'error' in response ) {
                console.log(response.error);
                toggleResponseError( origin, 'exit' );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                console.log(this.response);
                toggleResponseError( origin, 'exit' );
                return; }

        } else if ( this.status != 200 ) {
            console.log(this.status);
            toggleResponseError( origin, 'exit' );
            return; }
    }
    xhr.send();
}


function generateObjectsPaginatorUI ( pagAmount, objectsAmount, objectID, origin, outputType ) {
    console.log(pagAmount);
    console.log(objectsAmount);
    console.log(objectID);
    console.log(origin);
    console.log(outputType);
    const paginator       = document.createElement('div');
    const paginateUp      = document.createElement('span');
    const paginateIndexes = document.createElement('div');
    const currentIndex    = document.createElement('span');
    const seperator       = document.createElement('span');
    const lastIndex       = document.createElement('span');
    const paginateDown    = document.createElement('span');

    paginator.className       = 'paginator-ui';
    paginator.setAttribute( 'data-pagination-index', 1 );
    paginator.setAttribute( 'data-pagination-amount', objectsAmount );
    paginator.setAttribute( 'data-last-pagination-index', Math.ceil( objectsAmount/ parseInt(pagAmount) ) );
    paginateUp.className      = 'paginate-up hide';
    paginateUp.setAttribute( "onclick", "paginateObjects(" + "this, -1 , " + objectID + ", '" + origin + "', '" + outputType + "', "  + pagAmount + ");" );
    paginateIndexes.className = 'flex-container-center paginate-indexes';
    paginator.setAttribute( 'data-pagination-index', 1 );
    currentIndex.className    = 'paginate-current-index';
    currentIndex.textContent  = 1;
    seperator.className       = 'paginate-seperator';
    lastIndex.className       = 'paginate-last-index';
    lastIndex.textContent     = Math.ceil( objectsAmount/ parseInt(pagAmount) );
    paginateDown.className    = 'paginate-down';
    paginateDown.setAttribute( "onclick", "paginateObjects(" + "this, 1 , " + objectID + ", '" + origin + "', '" + outputType + "', "  + pagAmount + ");" );

    paginator.appendChild(paginateUp);
    paginator.appendChild(paginateIndexes);
    paginateIndexes.appendChild(currentIndex);
    paginateIndexes.appendChild(seperator);
    paginateIndexes.appendChild(lastIndex);
    paginator.appendChild(paginateDown);

    document.getElementById(origin + '-results-paginator').appendChild(paginator);
    return;
}


//Updates paginator interface when user interacts with it
function getSetPaginatorUi ( trigger, direction, origin, pagAmount ) {
    const currentPaginationIndex = parseInt( trigger.parentElement.getAttribute( 'data-pagination-index') );
    const newPaginationIndex     = direction === 1 ? currentPaginationIndex + 1 : currentPaginationIndex - 1;
    const newLastObjectIndex     = newPaginationIndex * pagAmount -1;
    const newFirstObjectIndex    = newLastObjectIndex - pagAmount + 1;
    const objectsAmount          = trigger.parentElement.getAttribute('data-pagination-amount');
    const lastPaginationIndex    = trigger.parentElement.getAttribute('data-last-pagination-index');
    const paginateUp             = trigger.parentElement.getElementsByClassName('paginate-up')[0];
    const paginateDown           = trigger.parentElement.getElementsByClassName('paginate-down')[0];

    if ( newPaginationIndex == lastPaginationIndex ) {
        paginateDown.classList.add('hide'); }
    else {
        paginateDown.classList.remove('hide'); }
    if ( newPaginationIndex === 1 ) {
        paginateUp.classList.add('hide') }
    else {
        paginateUp.classList.remove('hide'); }
    trigger.parentElement.setAttribute('data-pagination-index', newPaginationIndex );
    trigger.parentElement.getElementsByClassName('paginate-current-index')[0].textContent = newPaginationIndex;
    return { first: newFirstObjectIndex, last: newLastObjectIndex };
}


function toggleResponseError ( origin, action ) {
    switch ( action ) {
        case 'enter':
            document.getElementById( origin + '-results-error').classList.add('hide');
            document.getElementById( origin + '-results-loader').classList.remove('hide');
            break;
        case 'exit':
            document.getElementById( origin + '-results-error').classList.remove('hide');
            document.getElementById( origin + '-results-loader').classList.add('hide');
            break; }
    return; }


//The function that handles the library interface on page-discover.php. Triggers the getObjects() function
function discoverUiHandler ( option, objectID, objectName, outputType, pagAmount, trigger, hasChild, isChild ) {

    //Higlights clicked button, toggles corresponding option, clears all other discover ui highligths and hides sections
    if ( option === 'objects' || option === 'artists' || option === 'search' ) {
        const menus   = document.getElementsByClassName('discover-menu');
        const options = document.getElementsByClassName('discover-option');
        for ( let menu of menus ) {
            menu.id === 'discover-' +  option + '-menu' ? menu.classList.add('blue-text') : menu.classList.remove('blue-text'); }
        for (let instance of options ) {
            instance.id === 'discover-' +  option + '-option' ? instance.classList.remove('hide') : instance.classList.add('hide'); }
        hideAllDiscoverSections();
        highLightDiscoverUi( null, null );
        //removes search query string from url if present
        window.history.replaceState(null, null, window.location.pathname);
        return; }

    function prepare () {
        highLightDiscoverUi( trigger, isChild );
        clearPreviosResults( 'discover' );
        clearPaginatorUi( 'discover' ); }

    //If parent category with child
    if ( outputType === 'blender' && hasChild === true ) {
        prepare();
        toggleDiscChildCat( objectID, objectName, pagAmount, isChild  );
        getObjects( objectID, objectName, 'discover', outputType, pagAmount );
        return; }
    //If parent category without child
    if ( outputType === 'blender' && hasChild === false && isChild === false  ) {
        prepare();
        toggleDiscChildCat( null, null, null, false );
        getObjects( objectID, objectName, 'discover', outputType, pagAmount );
        return; }
    //If child category
    if ( outputType === 'blender' && isChild === true  ) {
        prepare();
        toggleDiscChildCat( null, null, null, true );
        getObjects( objectID, objectName, 'discover', outputType, pagAmount );
        return; }

    //Toggles artist taxnomy options
    if ( option === 'artists-by-category-section' || option === 'artists-by-name-section' ) {
        showDiscoverSection( option, trigger, isChild );
        return; }

    if ( outputType === 'profile' && hasChild === false && isChild === true  ) {
        prepare();
        toggleDiscChildCat( null, null, null, true );
        getObjects( objectID, objectName, 'discover', outputType, pagAmount );
        return; }
}


//Clears the content from specified element in DOM.
function clearPreviosResults ( origin ) {
    let resultsContainer = document.getElementById( origin + '-results-content' );
    while(resultsContainer.firstChild) {
           resultsContainer.removeChild(resultsContainer.firstChild); }
     document.getElementById( origin + '-results-loader').classList.add('hide'); }


//Removes the paginator interface from the DOM
function clearPaginatorUi ( origin ) {
    const container = document.getElementById( origin + '-results-paginator' );
    while(container.firstChild) {
        container.removeChild(container.firstChild); } }


//Toggles taxonomy children in discover Ui
function toggleDiscChildCat ( id, taxName, pagAmount, isChild ) {

    if ( isChild === true ) {
        return; }

    const allChildren = document.getElementById('browse-by-category-children').children;
    for ( let child of allChildren ) {
        if ( !child.classList.contains('hide') ) {
            child.classList.add('hide'); } }

    // if click on parent category without children
    if ( id === null && taxName === null && pagAmount === null && isChild === false ) {
        return; }

    const childCategoryContainer = document.getElementById('browse-by-category-children');
    const parentCategory         = document.createElement('div');
    parentCategory.className     = 'discover-child-active';
    parentCategory.textContent   = 'All in ' + taxName;
    parentCategory.setAttribute( 'onclick', 'discoverUiHandler( ' + null + ',' + id + ', null, "blender"' + ', ' + pagAmount + ', this, false, true );' );
    childCategoryContainer.classList.remove('hide');
    childCategoryContainer.prepend(parentCategory);
    const children               = document.querySelectorAll('.category-' + id );
    for ( let child of children ) {
        child.classList.remove('hide'); }
    return;
}


//Shows the category children corresponding to parent term id on page-discover.php
function showDiscoverSection( id, trigger, isChild ) {
    hideAllDiscoverSections();
    document.getElementById(id).classList.remove('hide');
    highLightDiscoverUi( trigger, isChild )
    return; }


// Hides all elements with .discover-section CSS class
function hideAllDiscoverSections () {
    const discoverSections = document.getElementsByClassName('discover-section');
    for ( let discoverSection of discoverSections ) {
        discoverSection.classList.add('hide'); }
    return; }


// Highlights selected buttons in discover UI
function highLightDiscoverUi( trigger, isChild ) {

    function toggleThese ( elements ) {
        for ( let element of elements ) {
            element.classList.remove( 'discover-ui-item-selected' ); } }

    // If parent button
    if ( trigger !== null && isChild === false ) {
        const parentsToToggle = document.getElementsByClassName('discover-parent-active');
        toggleThese( parentsToToggle );
        const childrenToToggle = document.getElementsByClassName('discover-child-active');
        toggleThese( childrenToToggle );
        trigger.classList.add( 'discover-ui-item-selected' );
        return; }
    // If child button
    if ( trigger !== null && isChild === true ) {
        const childrenToToggle = document.getElementsByClassName('discover-child-active');
        toggleThese( childrenToToggle );
        trigger.classList.add( 'discover-ui-item-selected' );
        return; }
    // If toggle menu
    if ( trigger === null && isChild === null ) {
        const parentsToToggle = document.getElementsByClassName('discover-parent-active');
        toggleThese( parentsToToggle );
        const childrenToToggle = document.getElementsByClassName('discover-child-active');
        toggleThese( childrenToToggle );
        return; }
}


// SEARCH HANDLERS AND HELPER FUNCTIONS GO HERE

//Retrievs and display the autofill response in search forms. Triggered in search forms.
function searchAutofill ( form ) {

    event.preventDefault();

    const searchQuery = document.getElementById(form + '-search-input').value;

    if ( event.key === 'Enter'  ) {
        activateSearch( 'keyword', null, null, searchQuery );
        return; }

    if ( searchQuery.length < 3 && event.key === 'Backspace' ) {
        const previousResults = document.querySelectorAll('.autofill-item');
        for ( let result of previousResults ) {
            result.remove(); }
        document.getElementById(form + '-search-form').style.height = '50px';
        return; }

    if ( searchQuery.length < 3 ) {
        return; }

    if ( event.key === 'Backspace'  ) {
        return; }

    setTimeout( function() {
        const previousResults = document.querySelectorAll('.autofill-item');
        for ( let result of previousResults ) {
            result.remove(); }
        document.getElementById(form + '-search-form').style.height = '50px';
        return;
    }, 7000);

    const xhr = new XMLHttpRequest();
    xhr.open('GET',
                frontend_ajax.ajax_url
                + '?action=all_ajax_action'
                +  '&client-query=' + searchQuery
                + '&task=search-autofill', true );

    xhr.onreadystatechange = function() {
    if ( this.readyState == 4 && this.status == 200 ) {

        const response = JSON.parse(this.response);

        if ( typeof response === 'object' && 'success' in response ) {

            document.getElementById(form + '-search-form').style.height = ( 50 + ( 40 * response.success.length ) ) + 'px';

            const autofillItems   = Object.entries(response.success);

            const previousResults = document.querySelectorAll('.autofill-item');
            for ( let result of previousResults ) {
                result.remove(); }

            autofillItems.forEach( ( item , i ) => {
                    const autofillItem     = document.createElement('div');
                    autofillItem.className = 'autofill-item flex-container';
                    autofillItem.setAttribute( 'onclick', 'activateSearch("autofill", "' + item[1].value + '", "' + item[1].term_id + '", null, "' + form + '");' );
                    const value            = document.createElement('b');
                    value.textContent      = item[1].value;
                    const type             = document.createElement('em');
                    type.className         = 'autofill-item-type';
                    type.textContent       = item[1].query_type;
                    autofillItem.appendChild(value);
                    autofillItem.appendChild(type);
                    document.getElementById(form + '-search-form').appendChild(autofillItem);
                });
            return; }

        function autofillError () {
            const previousResults = document.querySelectorAll('.autofill-item');
            for ( let result of previousResults ) {
                result.remove(); }
            document.getElementById(form + '-search-form').style.height = '50px'; }

        if ( typeof response === 'string' && response === 'failed' ) {
            autofillError();
            return; }

        if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean'
                && response !== 'success' || response !== 'failed' ) {
            autofillError();
            return; }

        } else if ( this.status != 200 ) {
            console.log(this.status);
            autofillError();
            return; }
    };
    xhr.send();
}


//Retrievs and displays the results for both keyword and autofill searches
function search ( searchType, taxName, taxID, searchQuery, pagAmount ) {

    clearPreviosResults( 'discover' );
    clearPaginatorUi( 'discover' );
    toggleResponseError( 'discover', 'enter' );

    const xhr    = new XMLHttpRequest();
    //If autofill taxonomy search
    if ( searchType === 'autofill' ) {
        xhr.open('GET',
                    frontend_ajax.ajax_url
                    + '?action=all_ajax_action'
                    +  '&tax-name=' + taxName
                    +  '&tax-ID='   + taxID
                    +  '&task='     + searchType, true ); }

    //If keyword search
    if ( searchType === 'keyword' ) {
        xhr.open('GET',
                    frontend_ajax.ajax_url
                    + '?action=all_ajax_action'
                    +  '&search-query=' + searchQuery
                    + '&task=' + searchType, true ); }

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response  = JSON.parse(this.response);
            const container = document.getElementById('discover-results-content');

            if ( typeof response === 'object' && 'success' in response ) {
                const postObjects = Object.entries(response.success);
                if ( postObjects.length > pagAmount ) {
                    const firstPaginationChunk = postObjects.slice( 0, pagAmount );
                    firstPaginationChunk.forEach( ( post ) => {
                        searchPreview( container, post ); });
                    if ( searchType === 'keyword' ) {
                        searchPaginatorUI ( pagAmount, postObjects.length, searchType, null, null, searchQuery ); }
                    else if ( searchType === 'autofill' ) {
                        searchPaginatorUI ( pagAmount, postObjects.length, searchType, taxName, taxID, null ); } }
                else {
                    postObjects.forEach( ( post ) => {
                        searchPreview( container, post ); }); }
                document.getElementById('discover-results-loader').classList.add('hide');
                return; }

            if ( typeof response === 'object' && 'error' in response ) {
                console.log(response.error);
                toggleResponseError( 'discover', 'exit' );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                console.log(this.response);
                toggleResponseError( 'discover', 'exit' );
                return; }

        } else if ( this.status != 200 ) {
            console.log(this.status);
            toggleResponseError( 'discover', 'exit' );
            return;}
    };
    xhr.send();
}


//Activates all keyword and taxonomy search queries. Fires directly by search form or through searchAutofill().
function activateSearch ( searchType, taxName, taxID, searchQuery, form ) {

    event.preventDefault();
    const baseURL     = window.location.origin;
    let redirectUrl;
    if ( searchType === 'keyword' ) {
        const query = searchQuery === null ? document.getElementById( form + '-search-input').value : searchQuery;
        if ( query.length > 0 || query !== '' ) {
            redirectUrl = baseURL + "/discover?search=true&search_type=" + searchType + '&tax_name=' + null + '&tax_ID=' + null + '&query=' + query;
            window.location.href = redirectUrl;
        } }
    if ( searchType === 'autofill' ) {
        //Checks if request is for taxonomy or CPT profile title
        const isTerm      = taxID === 'undefined' ? null : taxID;
        redirectUrl = baseURL + "/discover?search=true&search_type=" + searchType + '&tax_name=' + taxName + '&tax_ID=' + isTerm + '&query=' + null;
        window.location.href = redirectUrl; }
    return;
}


//The function that paginates search results which where first retrievd with search() function
function paginateSearch ( trigger, direction, searchType, taxName, taxID, searchQuery, pagAmount ) {

    let newIndexes = getSetPaginatorUi( trigger, direction, 'discover', pagAmount );
    clearPreviosResults( 'discover' );
    toggleResponseError( 'discover', 'enter' );
    const container = document.getElementById('discover-results-content');

    const xhr = new XMLHttpRequest();
    //If autofill taxonomy search
    if ( searchType === 'autofill' ) {
        xhr.open('GET',
                    frontend_ajax.ajax_url
                    + '?action=all_ajax_action'
                    +  '&tax-name=' + taxName
                    +  '&tax-ID='   + taxID
                    +  '&task='     + searchType, true ); }
    //If keyword search
    if ( searchType === 'keyword' ) {
        xhr.open('GET',
                    frontend_ajax.ajax_url
                    + '?action=all_ajax_action'
                    +  '&search-query=' + searchQuery
                    + '&task=' + searchType, true ); }

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response  = JSON.parse(this.response);

            if ( typeof response === 'object' && 'success' in response ) {
                const postObjects = Object.entries(response.success);
                postObjects.forEach( ( post, index ) => {
                    if ( index >= newIndexes.first && index <= newIndexes.last ) {
                            searchPreview( container, post ); } });
                document.getElementById('discover-results-loader').classList.add('hide');
                container.scrollIntoView(true);
                return; }

            if ( typeof response === 'object' && 'error' in response ) {
                console.log(response.error);
                toggleResponseError( 'discover', 'exit' );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                console.log(this.response);
                toggleResponseError( 'discover', 'exit' );
                return; }

        } else if ( this.status != 200 ) {
            console.log(this.status);
            toggleResponseError( 'discover', 'exit' );
            return; }
    }
    xhr.send();
}


//Generates the paginator interface for search results
function searchPaginatorUI ( pagAmount, objectsAmount, searchType, taxName, taxID, searchQuery ) {

    const paginator       = document.createElement('div');
    const paginateUp      = document.createElement('span');
    const paginateIndexes = document.createElement('div');
    const currentIndex    = document.createElement('span');
    const seperator       = document.createElement('span');
    const lastIndex       = document.createElement('span');
    const paginateDown    = document.createElement('span');

    paginator.className       = 'paginator-ui';
    paginator.setAttribute( 'data-pagination-index', 1 );
    paginator.setAttribute( 'data-pagination-amount', objectsAmount );
    paginator.setAttribute( 'data-last-pagination-index', Math.ceil( objectsAmount/ parseInt(pagAmount) ) );

    paginateUp.className      = 'paginate-up hide';
    if ( taxName === null && taxID === null && searchQuery !== null ) {
        paginateUp.setAttribute( "onclick", "paginateSearch(" + "this, -1 , '" + searchType + "', '" + null + "', '" + null + "', '"  + searchQuery + "', "  + pagAmount + ");" );
    } else if ( taxName !== null && taxID !== null && searchQuery === null ) {
        paginateUp.setAttribute( "onclick", "paginateSearch(" + "this, -1 , '" + searchType + "', '" + taxName + "', '" + taxID + "', "  + null + ", "  + pagAmount + ");" );
    }

    paginateIndexes.className = 'flex-container-center paginate-indexes';
    paginator.setAttribute( 'data-pagination-index', 1 );
    currentIndex.className    = 'paginate-current-index';
    currentIndex.textContent  = 1;
    seperator.className       = 'paginate-seperator';
    lastIndex.className       = 'paginate-last-index';
    lastIndex.textContent     = Math.ceil( objectsAmount/ parseInt(pagAmount) );

    paginateDown.className    = 'paginate-down';
    if ( taxName === null && taxID === null && searchQuery !== null ) {
         paginateDown.setAttribute( "onclick", "paginateSearch(" + "this, 1 , '" + searchType + "', '" + null + "', '" + null + "', '"  + searchQuery + "', "  + pagAmount + ");" );
    } else if ( taxName !== null && taxID !== null && searchQuery === null ) {
         paginateDown.setAttribute( "onclick", "paginateSearch(" + "this, 1 , '" + searchType + "', '" + taxName + "', '" + taxID + "', "  + null + ", "  + pagAmount + ");" ); }

    paginator.appendChild(paginateUp);
    paginator.appendChild(paginateIndexes);
    paginateIndexes.appendChild(currentIndex);
    paginateIndexes.appendChild(seperator);
    paginateIndexes.appendChild(lastIndex);
    paginator.appendChild(paginateDown);

    document.getElementById('discover-results-paginator').appendChild(paginator);

    return;

}


//GENERAL HELPER FUNCTIONS
function setErrors ( errorClass, responseObject ) {
    const errorContainer     = document.getElementById( errorClass + '-errors');
    errorContainer.className = "error-container";
    const errorMessage       = document.createElement('small');
    errorMessage.className   = errorClass + '-error';
    errorMessage.textContent = responseObject;
    errorContainer.appendChild(errorMessage); }

function removeErrors ( errorClass ) {
    const oldErrors = document.querySelectorAll('.' + errorClass +  '-error');
    if ( oldErrors.length > 0 ) {
        document.getElementById(errorClass + '-errors').className = '';
        for ( let oldError of oldErrors ) {
            oldError.remove(); } } }

function setInputErrors ( errorClass, responseObject, method ) {
    const errors = Object.entries(responseObject);
    errors.forEach(( error, index) => {
        const errorType = error[0];
        const errorMessages = error[1];
        if ( errorMessages.length > 0 ) {
            let errorContainer = document.getElementById( errorClass + '-' + errorType );
            for (var i = 0; i < errorMessages.length; i++) {
                let errorMessage = document.createElement('small');
                if ( errorType !== 'errors' ) {
                    errorContainer.classList.add('input-error');
                    errorMessage.textContent = errorMessages[i];
                    errorMessage.className = errorClass + '-error error';
                    if ( method === 'append' ) {
                        errorContainer.parentElement.appendChild(errorMessage);
                    } else if ( method === 'prepend' ) {
                        errorContainer.parentElement.prepend(errorMessage); }
                    return; }
                errorMessage.className = errorClass + '-error';
                errorMessage.textContent = errorMessages[i];
                errorContainer.classList.toggle('error-container');
                errorContainer.appendChild(errorMessage);
                return; } } }); }

function removeInputErrors ( ) {
    const oldErrors = document.querySelectorAll('.input-error');
    if ( oldErrors.length > 0 ) {
        for ( let oldError of oldErrors ) {
            oldError.classList.remove('input-error');
        }
    }
}
