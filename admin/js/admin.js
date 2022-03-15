function toggleAdminInputs ( inputClass, state ) {
    const inputs = document.getElementsByClassName(inputClass);
    for ( let input of inputs ) {
        if ( state === 'hide' ) {
            input.classList.add('hide');
        } else {
            input.classList.remove('hide');
        }
    }
    return;
}


function uploadBlenderFile() {
    event.preventDefault();
    media_uploader = wp.media({
        title: 'Blender File',
        library : {
            type : 'application/octet-stream'
        },
        button: {
            text: 'Add this file to post'
        },
        multiple: false
    }).on('select', function() {
            const attachment = media_uploader.state().get('selection').first().toJSON();
            const blenderFileInput = document.getElementById('admin-upload-blender');
            blenderFileInput.textContent = attachment.url;
        }).open();
        return;
}
