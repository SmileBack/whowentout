function initialize_crop_ui() {
  var x = parseInt( $('#x').val() ),
      y = parseInt( $('#y').val() ),
      width = parseInt( $('#width').val() ),
      height = parseInt( $('#height').val() );
      
  var api = WWO.api = $.Jcrop('#crop img', {
    aspectRatio: 0.75,
    onChange: onChange,
    onSelect: onSelect
  });
  
  api.setSelect([x, y, x + width, y + height]);
  
  api.selection.enableHandles();
  
  function set_textbox_coordinates(x, y, width, height) {
    $('#x').val(x);
    $('#y').val(y);
    $('#width').val(width);
    $('#height').val(height);
  }
  
  function onChange(coords) {
    set_textbox_coordinates(coords.x, coords.y, coords.w, coords.h);
    showPreview(coords);
  }
  
  function onSelect(coords) {
    set_textbox_coordinates(coords.x, coords.y, coords.w, coords.h);
    showPreview(coords);
  }
  
  function showPreview(coords) {
    
    if (parseInt(coords.w) > 0) {
      var smallWidth = $('#crop_preview').width();
      var smallHeight = $('#crop_preview').height();
      var largeWidth = $('#crop img').width();
      var largeHeight = $('#crop img').height();
      var rx = smallWidth / coords.w;
      var ry = smallHeight / coords.h;
 
      $('#crop_preview img').css({
        width: Math.round(rx * largeWidth) + 'px',
        height: Math.round(ry * largeHeight) + 'px',
        marginLeft: '-' + Math.round(rx * coords.x) + 'px',
        marginTop: '-' + Math.round(ry * coords.y) + 'px'
      });
    }
    
  }
}

jQuery(function($) {
  $('#crop img').imagesLoaded(initialize_crop_ui);
});
