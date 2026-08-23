
// scale a card is shown at once it has been placed, vs. while dragging/hovering
var PLACED_SCALE = 0.50;
var FULL_SCALE = 1;

// target elements with the "draggable" class
interact('.draggable')
  .draggable({

    // enable inertial throwing
    inertia: false,
    // keep the element within the area of it's parent
    modifiers: [
      interact.modifiers.restrict({
        restriction: 'self'
      }),
    ],
    // enable autoScroll
    autoScroll: true,

    // full size and no transition animation while actively dragging
    onstart: function (event) {
      event.target.classList.add('dragging');
      setTransform(event.target, FULL_SCALE);
    },
    // call this function on every dragmove event
    onmove: dragMoveListener,
    // call this function on every dragend event
    onend: function (event) {
      //var textEl = event.target.querySelector('p');
      var element = document.getElementById(event.target.id);
      var pos = element.getBoundingClientRect();
      var positionFloat = (pos.left/document.getElementById('container').offsetWidth)*100;
      var position = parseFloat(positionFloat).toFixed(0);
      var hash = window.location.search.substr(1);
      var postdata = "item["+ event.target.id + "]=" + parseFloat(position).toFixed(0) + "&ajax=1&" + hash;

      // AJAX call
      postAjax(postdata);

      //Update table
      var cellId = "col" + event.target.id;
      var cell= document.getElementById(cellId);
      cell.innerHTML = position;

      // shrink the card now that it has been placed, to reduce clutter -
      // unless the pointer is still resting on it right after the drop (no
      // mouseover/mouseout transition will fire until it actually leaves and
      // comes back, so shrinking underneath a still-hovering cursor would get
      // stuck shrunk until the next hover)
      element.classList.remove('dragging');
      element.classList.add('placed');
      setTransform(element, element.matches(':hover') ? FULL_SCALE : PLACED_SCALE);
    }
  });

  function dragMoveListener (event) {
    var target = event.target,
        // keep the dragged position in the data-x/data-y attributes
        x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx,
        y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

    // update the position attributes
    target.setAttribute('data-x', x);
    target.setAttribute('data-y', y);

    // translate the element, full size while being dragged
    setTransform(target, FULL_SCALE);
  }

  // combine the stored translate position with a scale factor;
  // interact.js sets style.transform directly so scale has to travel
  // through here rather than a separate CSS rule, which would be
  // overridden by that inline style.
  function setTransform (target, scale) {
    var x = parseFloat(target.getAttribute('data-x')) || 0;
    var y = parseFloat(target.getAttribute('data-y')) || 0;
    var transform = 'translate(' + x + 'px, ' + y + 'px) scale(' + scale + ')';
    target.style.webkitTransform = target.style.transform = transform;
  }

  // once a card is placed, grow it back to full size on hover so it can
  // still be inspected/re-dragged, then shrink it again on mouseleave
  document.addEventListener('mouseover', function (event) {
    var el = event.target.closest && event.target.closest('.draggable.placed');
    if (el) setTransform(el, FULL_SCALE);
  });
  document.addEventListener('mouseout', function (event) {
    var el = event.target.closest && event.target.closest('.draggable.placed');
    if (el && !el.contains(event.relatedTarget)) setTransform(el, PLACED_SCALE);
  });

  function postAjax(postdata) {
    
    var xhr = new XMLHttpRequest();
    
    xhr.open('POST', 'exp.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(encodeURI(postdata));
}
  

  
