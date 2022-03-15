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

function toggleCustomizerSections ( trigger, section ) {

    //If click on expanded
    if ( trigger.lastElementChild.className === 'fas fa-minus' ) {
        const allSections = document.getElementsByClassName(section);
        for ( let sections of allSections ) {
            sections.classList.add('hide'); }
        trigger.lastElementChild.className = 'fas fa-plus'
        return; }

    //If clicked on closed
    const allTitles = document.querySelectorAll('.customizer-flex');
    for ( let title of allTitles ) {
        title.lastElementChild.className = 'fas fa-plus'; }
    trigger.lastElementChild.className = 'fas fa-minus';
    const allSections = document.querySelectorAll('.fieldset');
    for ( let sections of allSections ) {
        if ( sections.classList.contains(section) ) {
            sections.classList.remove('hide'); }
        else {
            sections.classList.add('hide'); } }
    return;
}
