$(document).ready(function() {
  var engine, remoteHost, template, empty;

  $.support.cors = true;

  remoteHost = 'https://www.docbooking.ch';
  template = Handlebars.compile($("#result-template").html());
  //empty = Handlebars.compile($("#empty-template").html());

  engine = new Bloodhound({
    identify: function(o) { return o.id_str; },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('first_name', 'last_name', 'email', 'phone_number', 'speciality', 'services', 'specialization', 'title'),
    dupDetector: function(a, b) { return a.id_str === b.id_str; },
	//local: doctors_array_json,
    prefetch: remoteHost + '/search/prefetch',
    remote: {
      url: remoteHost + '/search/find?q=%QUERY',
      wildcard: '%QUERY'
    }
  });

  function engineWithDefaults(q, sync, async) {
    if (q === '') {
    }
    else {
      engine.search(q, sync, async);
    }
  }

  $('#search-doctor-field').typeahead({
    hint: $('.Typeahead-hint'),
    menu: $('.Typeahead-menu'),
    minLength: 0,
    classNames: {
      open: 'is-open',
      empty: 'is-empty',
      cursor: 'is-active',
      suggestion: 'Typeahead-suggestion',
      selectable: 'Typeahead-selectable'
    }
  }, {
    source: engineWithDefaults,
    displayKey: 'screen_name',
    templates: {
      suggestion: template,
      //empty: empty
    }
  })
  .on('typeahead:asyncrequest', function() {
    $('.Typeahead-spinner').show();
  })
  .on('typeahead:asynccancel typeahead:asyncreceive', function() {
    $('.Typeahead-spinner').hide();
  });

});