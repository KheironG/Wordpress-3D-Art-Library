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
