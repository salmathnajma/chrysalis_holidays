//------------------ call button link in phone-----------------

jQuery(document).ready(function ($) {
  //document.getElementById("ui-id-5").href = "#tab-review";
  $('.single-post .comment-form textarea').attr('placeholder', 'Comments...');

      if (!navigator.userAgent.match(/(iPhone|Android)/)) {
              $('#call_button').click(function (e) {
                //alert ("Phone link is prevented from working since this is not a smartphone");
                var url = 'https://chrysalis.cahosting.biz/contact/';
                window.location.href = url;
                e.preventDefault();
              });
      }

});

//------------------ comment_form_validation------------------


jQuery(document).ready(function($) {
       
  // validation for single post form
      $('#commentform').validate({
        ignore:'',

      rules: {
        author: {
          required: true,
          minlength: 2
        },

        email: {
          required: true,
          email: true
        },

        comment: {
          required: true,
          minlength: 5
        }
      },

      messages: {
        author: "Please fill the required field",
        email: "Please enter a valid email address.",
        comment: "Please enter your comments at least 5 characters"
      },

      errorElement: "div",
      errorPlacement: function(error, element) {
        element.after(error);
      }

      });
  // validation for single Destinations form
      $('#custom_comment_form').validate({
        ignore:'',

          rules: {
            author: {
              required: true,
              minlength: 2
            },

            email: {
              required: true,
              email: true
            },

            comment: {
              required: true,
              minlength: 5
            },

            'star-rating-control': {
              required:true
            }
          },

          messages: {
            author: "Please fill the required field",
            email: "Please enter a valid email address.",
            comment: "Please enter your comments at least 5 characters",
          },
          errorElement: "div",
          errorPlacement: function(error, element) {
            element.after(error);
          }

          });

}); 

//------------------ alert after form submission------------------

jQuery(document).ready(function($) {
       
  
          // submit alert for post single page 

          $(".submit").click(function() {
            var comment = $("#comment").val();
            var author = $("#author").val();
            var email = $("#email").val();
          if(comment != "" && author != "" && email != ""){ 
            swal({
            text: "successfully submitted!",
            icon: "success",
            button: false,
            });
          }
          });

          // submit alert for Destinations single page 

          $( ".submit" ).click(function() {
          if($('#custom_comment_form').valid()){
            swal({
              text: "successfully submitted!",
              icon: "success",
              button: false,
              });
          }
          });

}); 

//------------------ isotope filtering -----------------

jQuery(document).ready(function($) {

  // init Isotope
  var $grid = $('.grid').isotope({
    itemSelector: '.element-item',
    layoutMode: 'fitRows'
  });
  // filter functions
  var filterFns = {};
  // bind filter on select change
  $('.destination_header').on( 'change', function( event ) {
    // get filter value from option value
    var $select = $( event.target );
    var filterGroup = $select.attr('value-group');
      // set filter for group
      filterFns[ filterGroup ] = event.target.value;
    // combine filters
    var filterValue = concatValues( filterFns );
  // set filter for Isotope
  $grid.isotope({ filter: filterValue });
  });
  
// flatten object by concatting values
function concatValues( obj ) {
  var value = '';
  for ( var prop in obj ) {
    value += obj[ prop ];
  }
  return value;
}


/* code for redirect after commenting */

var urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('tab') == 'true') {

  $( "#ui-id-5" ).trigger( "click" );
  $('html, body').animate({
    scrollTop: ($('.main').first().offset().top)
},500);

$(document).ready(function(){
  var uri = window.location.toString();
  if (uri.indexOf("?") > 0) {
      var clean_uri = uri.substring(0, uri.indexOf("?"));
      window.history.replaceState({}, document.title, clean_uri);
  }
});


}


}); 

