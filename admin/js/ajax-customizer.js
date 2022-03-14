function adminCustomizer () {

    event.preventDefault();

    const previousErrors  = document.querySelectorAll('.error');
    const previousSuccess = document.querySelectorAll('.success');
    if ( previousErrors.length > 0 ) {
        for ( let error of previousErrors ) {
            error.remove(); } }
    if ( previousSuccess.length > 0 ) {
        for ( let success of previousSuccess ) {
            success.remove(); } }

    const form      = document.getElementById('admin-customizer-form');
    const inputs    = form.getElementsByTagName('input');
    const textareas = form.getElementsByTagName('textarea');
    const selects   = form.getElementsByTagName('select');

    const data = [];
    function compileData ( group ) {
        for ( let instance of group ) {
            if ( instance.type !== 'radio' ) {
                const metaKey = instance.name.replaceAll( "-", "_" );
                const item    = [ metaKey, instance.value ];
                data.push( item ); }
            else if ( instance.type === 'radio' && instance.checked === true ) {
                const metaKey = instance.name.replaceAll( "-", "_" );
                const item    = [ metaKey, instance.value ];
                data.push( item ); } }
        return; }

    compileData( inputs );
    compileData( textareas );
    compileData( selects );

    const xhr = new XMLHttpRequest();
    xhr.open('POST', ajax_customizer.ajax_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {

            const response = JSON.parse(this.response);

            function responseHandler ( responseType, responseMessage ) {
                const container     = document.getElementById('admin-customizer-response');
                const message       = document.createElement('small');
                message.className   = responseType;
                message.textContent = responseMessage;
                container.appendChild(message); }

            if ( typeof response === 'string' && response === 'success' ) {
                responseHandler ( 'success', 'options saved');
                return; }

            if ( typeof response === 'object' && 'error' in response ) {
                responseHandler ( 'error', response.error );
                return; }

            if ( typeof response === 'string' || typeof response === 'number' || typeof response === 'boolean' ) {
                responseHandler ( 'error', response );
                return; }

        } else if ( this.status != 200 ) {
            responseHandler ( 'error', 'server error' );
            return; }
}
    xhr.send('action=customizer_action&data=' + JSON.stringify( data ) + '&nonce=' + ajax_customizer.nonce );
}
