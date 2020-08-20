jQuery(document).ready(function($) {
    $('input[name="daterange"]').daterangepicker({
        opens: 'center',
        autoUpdateInput: false,
        autoApply: true,
    }, function(start, end, label) {
        $('input[name="daterange"]').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));

        $('.checkin_date').val(start.format('YYYY-MM-DD'));
        $('.checkout_date').val(end.format('YYYY-MM-DD'));
    });

    $(document).on('click', '.booking-widget .dropdown-menu', function (e) {
        e.stopPropagation();
      });
   
});
var room_count = 1;
var adult_count = 1;
var child_count = 0;
var rooms = document.getElementById("rooms");
var adults = document.getElementById("adults");
var children = document.getElementById("children");
var total_room = document.getElementById("total_room");
var total_guest = document.getElementById("total_guest");

function bookingOnSubmit() {
    jQuery(document).ready(function($) {

        // We'll pass this variable to the PHP function example_ajax_request
       
        var checkin = $('#checkin_date').val();
        var checkout = $('#checkout_date').val();
        var rooms = $('#rooms').val();
        var adults = $('#adults').val();
        var children = $('#children').val();
        var name = $('#name').val();
        var email = $('#email').val();
        var phone = $('#phone').val();



        
        // This does the ajax request
        $.ajax({
            url: ca_booking_obj.ajaxurl, // or example_ajax_obj.ajaxurl if using on frontend
            data: {
                'action': 'save_bookings',
                'checkin' : checkin,
                'checkout' : checkout,
                'rooms' : rooms,
                'adults' : adults,
                'children' : children,
                'name' : name,
                'email' : email,
                'phone' : phone
            },
            success:function(data) {
                // This outputs the result of the ajax request
                console.log(data);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });  
            
    });


    return true;
}

function plus(item){

    if(item == 'room'){
        room_count++;
        rooms.value = room_count;
        total_room.innerHTML = room_count;
    }
    if(item == 'adult'){
        adult_count++;
        adults.value = adult_count;
        total_guest.innerHTML = adult_count + child_count;
    }
    if(item == 'children'){
        child_count++;
        children.value = child_count;
        total_guest.innerHTML = adult_count + child_count;
    }
    
}

function minus(item){
    
    if(item == 'room'){
        if (room_count > 1) {
            room_count--;
            rooms.value = room_count;
            total_room.innerHTML = room_count;
        }  
    }
    if(item == 'adult'){
        if (adult_count > 1) {
            adult_count--;
            adults.value = adult_count;
            total_guest.innerHTML = adult_count + child_count;
        }  
    }
    if(item == 'children'){
        if (child_count > 0) {
            child_count--;
            children.value = child_count;
            total_guest.innerHTML = adult_count + child_count;
        }  
    }
    
}



function mobileBookingOnSubmit() {
    jQuery(document).ready(function($) {

        // We'll pass this variable to the PHP function example_ajax_request
    
        var checkin = $('#checkin_date').val();
        var checkout = $('#checkout_date').val();
        var rooms = $('#mobile-rooms').val();
        var adults = $('#mobile-adults').val();
        var children = $('#mobile-children').val();
        var name = $('#mobile-name').val();
        var email = $('#mobile-email').val();
        var phone = $('#mobile-phone').val();



        
        // This does the ajax request
        $.ajax({
            url: ca_booking_obj.ajaxurl, // or example_ajax_obj.ajaxurl if using on frontend
            data: {
                'action': 'save_bookings',
                'checkin' : checkin,
                'checkout' : checkout,
                'rooms' : rooms,
                'adults' : adults,
                'children' : children,
                'name' : name,
                'email' : email,
                'phone' : phone
            },
            success:function(data) {
                // This outputs the result of the ajax request
                console.log(data);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });  
            
    });


    return true;
}


var mobile_room_count = 1;
var mobile_adult_count = 1;
var mobile_child_count = 0;
var mobile_rooms = document.getElementById("mobile-rooms");
var mobile_adults = document.getElementById("mobile-adults");
var mobile_children = document.getElementById("mobile-children");
var mobile_total_room = document.getElementById("mobile-total_room");
var mobile_total_guest = document.getElementById("mobile-total_guest");

function mobilePlus(item){
    
    if(item == 'room'){
        mobile_room_count++;
        mobile_rooms.value = mobile_room_count;
        mobile_total_room.innerHTML = mobile_room_count;
    }
    if(item == 'adult'){
        mobile_adult_count++;
        mobile_adults.value = mobile_adult_count;
        mobile_total_guest.innerHTML = mobile_adult_count + mobile_child_count;
    }
    if(item == 'children'){
        mobile_child_count++;
        mobile_children.value = mobile_child_count;
        mobile_total_guest.innerHTML = mobile_adult_count + mobile_child_count;
    }
    
}

function mobileMinus(item){
    
    if(item == 'room'){
        if (mobile_room_count > 1) {
            mobile_room_count--;
            mobile_rooms.value = mobile_room_count;
            mobile_total_room.innerHTML = mobile_room_count;
        }  
    }
    if(item == 'adult'){
        if (mobile_adult_count > 1) {
            mobile_adult_count--;
            mobile_adults.value = mobile_adult_count;
            mobile_total_guest.innerHTML = mobile_adult_count + mobile_child_count;
        }  
    }
    if(item == 'children'){
        if (mobile_child_count > 0) {
            mobile_child_count--;
            mobile_children.value = mobile_child_count;
            mobile_total_guest.innerHTML = mobile_adult_count + mobile_child_count;
        }  
    }
    
}


