function reloadScript() {
    with(document) {
     var newscr = createElement('script');
     newscr.id = 'demineurScript';
     newscr.appendChild(createTextNode(getElementById('demineurScript').innerHTML));
     body.removeChild(getElementById('demineurScript'));
     body.appendChild(newscr);
    }
   }