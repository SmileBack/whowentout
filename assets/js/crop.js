$.fn.imagesLoaded = function(callback){
  var elems = this.filter('img'),
      len   = elems.length;
      
  elems.bind('load',function(){
      if ( --len <= 0 ) { callback.call(elems, this); }
  }).each(function(){
     // cached images don't fire load sometimes, so we reset src.
     if (this.complete || this.complete === undefined){
        var src = this.src;
        // webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
        // data uri bypasses webkit log warning (thx doug jones)
        this.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
        this.src = src;
     }  
  }); 

  return this;
};

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
