CKEDITOR.on('instanceCreated', function (event) {
  var editor = event.editor;

  editor.on('configLoaded', function () {

    editor.config.toolbarGroups = [
      { groups: ['styles', 'basicstyles'] },
      { groups: ['list', 'indent', 'blocks'] },
      { groups: ['links', 'insert'] },
      { groups: ['clipboard', 'undo'] },
      { groups: ['spellchecker'] }
    ];

    editor.config.removeButtons = 'Styles,Underline,Strike,Subscript,Superscript,PasteText,PasteFromWord,Redo,Unlink,Anchor';
  });

  // Send updated content to the back end on change
  editor.on('change', function () {
    $.post('/' + editor.element.getAttribute('data-slug'), editor.getData());
  });
});

// Send CSRF tokens based on meta tag
$.ajaxSetup({ headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') } });

// Add error notification for ajax calls, based on HTTP code
$(document).ajaxError(function (e, xhr) {
  var alert;

  switch (xhr.status) {
    case 401:
      alert = 'Sorry, your session has timed out and you need to log in again.';
      break;
    case 500:
      alert = 'Sorry, an error on the server prevented your request from completing.';
      break;
    default:
      alert = 'Sorry, can\'t communicate with the server. Please check your connection and try again.';
  }

  $('#myModal').modal().find('.modal-body').contents().filter(function () {
    return this.nodeType === 3;
  }).last().replaceWith(document.createTextNode(alert));
});
