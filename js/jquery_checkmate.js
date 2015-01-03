//********************************************************************************************************************************************
// show spinner when AJAX queries are pending
//********************************************************************************************************************************************
$(document).on({
  ajaxStart: function() { 
    $.mobile.loading('show');
  },
  ajaxStop: function() {
    $.mobile.loading('hide');
  }    
});

var selected_user;
var selected_uid;
      
//********************************************************************************************************************************************
// handle login form  
//********************************************************************************************************************************************
$(document).ready(function() 
{
  $("#login_form").submit(function(e) {
    e.preventDefault();
    var form = this;

    $.ajax(
    {
      type: "POST",
      url: "login.php",
      cache: false,
      data: { username: $("#username").val(), 
              password: $("#password").val()
            }
    }).done(function()
    {
      form.submit();
    });
  });

  // handle logout form
  $("#logout_form").submit(function(e) {
    e.preventDefault();
    var form = this;

    $.ajax(
    {
      type: "POST",
      url: "login.php",
      cache: false,
      data: { username: "asdf", 
              password: "asdf"
            }
    }).done(function()
    {
      form.submit();
    });
  });

  // handle visitor registration form
  $("#visitor_form").submit(function(e) {
    e.preventDefault();
    var form = this;
    $("#popupLogin").popup("close");

    $.ajax(
    {
      url: "query.php",
      type: "POST",
      cache: false,
      data: { query       : 10,
              fn          : $("#fn").val(),
              mn          : $("#mn").val(),
              ln          : $("#ln").val(),
              bday        : $("#bday").val(),
              gender      : $("#gender").val(),
              cell        : $("#cell").val(),
              homeph      : $("#homeph").val(),
              email       : $("#email").val(),
              addr_street : $("#addr_street").val(),
              addr_city   : $("#addr_city").val(),
              addr_state  : $("#addr_state").val(),
              addr_zip    : $("#addr_zip").val(),
              gradyear    : $("#gradyear").val() 
              },
    }).done(function()
    {
      $("#visitor_form").trigger("reset");
    });;
  });
});


//********************************************************************************************************************************************
// generate content for home page
//********************************************************************************************************************************************
$(document).on("pagebeforeshow", "#home", function () 
{
  // listen for typed characters, populate listview with usernames that match
  $( "#users_lv" ).on( "filterablebeforefilter", function ( e, data ) {
    var $ul = $( this ),
    $input = $( data.input ),
    value = $input.val(),
    html = "";
    $ul.html( "" );

    // wait until at least 2 chars are typed before querying DB
    if ( value && value.length >= 2 ) {
      $ul.html( "<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>" );
      $ul.listview( "refresh" );

      // get student names that match
      $.ajax(
      {
        url: "query.php",
        type: "POST",
        data: {query: 8, pname: value},
        dataType: "json"
      })
      .done( function ( response ) {
        $.each( response, function ( i, value ) {
          html += '<li uname="' + value.firstname + ' ' + value.lastname  + '" uid="' + value.uid + '"><a href="#rightpanel">' + value.firstname + ' ' + value.lastname  + '</a></li>';
        });
        $ul.html( html );
        $ul.listview( "refresh" );
        $ul.trigger( "updatelayout");
        // console.log(html);
      });
    }
  });
});

//********************************************************************************************************************************************
// generate content for reports page 
//********************************************************************************************************************************************
$(document).on("pagebeforeshow", "#reports_page", function () 
{
  // query DB for attendance data
  $.ajax(
  {
    url: "query.php",
    type: "POST",
    data: {query: 7},
    dataType: "json"
  }).done(function( msg ) 
  {
    // console.log(msg);

    // parse through returned data, populate array of dates+attendance
    var r = new Array();
    $.each(msg, function (index, value) 
    {
      r.push( [new Date(value.date),value.attendees]);
    });

    // console.log(r);

    // generate chart after 500ms
    setTimeout( function (){
      new Dygraph(document.getElementById("chart_placeholder"),
        // For possible data formats, see http://dygraphs.com/data.html
        // The x-values could also be dates, e.g. "2012/03/15"
        r, // data from database
        {
          // options go here. See http://dygraphs.com/options.html
          // legend: 'always',
          animatedZooms: true,
          xlabel: 'Date',
          ylabel: 'Attendance',
          labels: ["date","attendance"],
          title: 'Attendance over time',
          fillGraph: true,
          rightGap: 20,
          drawPoints: true,
          pointSize : 3,
          highlightCircleSize: 5,
          // showRangeSelector: true,
        });
    }, 500);
  });
});

//********************************************************************************************************************************************
// only want to do this once (pagecreate) to maintain options
//********************************************************************************************************************************************
$(document).on("pagecreate", "#reports_page", function () 
{
  // get all smallgroup IDs from database and populate selectmenu
  $.ajax(
  {
    url: "query.php",
    type: "POST",
    data: {query: 11},
    dataType: "json"
  }).done(function( msg ) 
  {
    // console.log(msg);
    var r = [];
    r.push('<option value="%">All</option>'); // initial "all" option

    // iterate through data, populate selectmenu's options
    $.each(msg, function (index, value) 
    {
      r.push('<option value="'+value.sg_id+'">'+value.sg_name+'</option>');
    });

    // populate w/ array r
    $('#sgid').html(r.join(""));

    // refresh selectmenu widget
    $('#sgid').selectmenu('refresh', true);
  });

});


//********************************************************************************************************************************************
// listen for clicks on student name and update user panel accordingly
//********************************************************************************************************************************************
$(document).on("click", "#users_lv li" ,function (event) 
{
  selected_user = $(this).attr('uname');
  selected_uid  = $(this).attr('uid');
  $("#output_text").html('Hi ' + selected_user);
}); 

//********************************************************************************************************************************************
// listen for clicks on rightpanel
//********************************************************************************************************************************************
$(document).on("click", "#action_lv li" ,function (event) 
{
  // alert($(this).text());

  // clear filter and update user listview
  $('#filter-for-listview').val("");
  $('#filter-for-listview').trigger("keyup");

  // close panel
  $('#rightpanel').panel("close");

  if($(this).text() === "Check in")
  {
    // checkin user
    $.ajax(
    {
      url: "query.php",
      type: "POST",
      data: {uid: selected_uid, query: 2},
    });

    // show popup
    $.gritter.add(
    {
      position: 'bottom-left',
      title : 'Success!',
      time : 2000,
      text : 'Checked in ' + selected_user
    });
  }
  else
  {
    // show popup
    // $.gritter.add(
    // {
    //   position: 'bottom-left',
    //   title : 'Doh!',
    //   time : 2000,
    //   text : 'Not implemented yet, ' + selected_user
    // });         
  }
});

//********************************************************************************************************************************************
// generate content for user history page 
//********************************************************************************************************************************************
$(document).on("pagebeforeshow", "#user_history", function () 
{
    // Show user's history
    $.ajax(
    {
      url: "query.php",
      type: "POST",
      data: {uid: selected_uid, query: 4},
    }).done(function( msg ) 
    {
      var r = new Array();

      r.push('<li data-role="list-divider">' + selected_user + ' - Attendance Records</li>');

      $.each(msg, function (index, value) 
      {
        r.push( "<li>" + value.days + "</li>");
      });

      $('#user_attendance_lv').html(r.join('')).listview("refresh");

    });    
});

//********************************************************************************************************************************************
// generate content for attendance page 
//********************************************************************************************************************************************
$(document).on("pagebeforeshow", "#attendance_page", function () 
{
  $.ajax(
  {
    url: "query.php",
    type: "POST",
    data: { query         : 0,
            sgid          : $("#sgid").val(),
            gender_rep    : $("#gender_rep").val(),
            gradyear_rep  : $("#gradyear_rep").val(),
          },
    dataType: "json"
  }).done(function( msg ) 
  {
    // alert(msg);
    var r = new Array();
    r.push( '<thead><tr><th>Date</th><th>Attendance</th></tr></thead>')

    $.each(msg, function (index, value) 
    {
      r.push( '<tr><td>' + value.days + '</td><td>' + value.attendees + '</tr>' );
    });

    r.push( '</tbody>')

    // alert(r);
    $('#attendance_table')[0].innerHTML = r.join('');
    // $('#dataTable').html(r.join(''));
  });

  $('#attendance_opt').html(  'Options:<br />'+
                              'Date Range      - '+$("#daterange option:selected").text()+'<br />'+
                              'Small Group ID  - '+$("#sgid option:selected").text()+'<br />'+
                              'Gender          - '+$("#gender_rep option:selected").text()+'<br />'+
                              'Grad Year       - '+$("#gradyear_rep option:selected").text());
});


//********************************************************************************************************************************************
// generate content for stale users page 
//********************************************************************************************************************************************
$(document).on("pagebeforeshow", "#stale_users_page", function () 
{
  $.ajax(
  {
    url: "query.php",
    type: "POST",
    data: { query         : 5,
            sgid          : $("#sgid").val(),
            gender_rep    : $("#gender_rep").val(),
            gradyear_rep  : $("#gradyear_rep").val(),
          },
    dataType: "json"
  }).done(function( msg ) 
  {
    // console.log(msg);
    var r = new Array();
    r.push( '<thead><tr><th>User</th><th>Last Checkin</th></tr></thead>')

    $.each(msg, function (index, value) 
    {
      r.push( '<tr><td>' + value.firstname + ' ' + value.lastname + '</td><td>' + value.day + '</tr>' );
    });

    r.push( '</tbody>')

    $('#stale_users_table')[0].innerHTML = r.join('');
  });  

  $('#stale_users_opt').html(  'Options:<br />'+
                      'Date Range      - '+$("#daterange option:selected").text()+'<br />'+
                      'Small Group ID  - '+$("#sgid option:selected").text()+'<br />'+
                      'Gender          - '+$("#gender_rep option:selected").text()+'<br />'+
                      'Grad Year       - '+$("#gradyear_rep option:selected").text());
});

//********************************************************************************************************************************************
// generate content for checkins page 
//********************************************************************************************************************************************
$(document).on("pagebeforeshow", "#checkins_page", function () 
{
  // get attendance per day and create collapsible
  $.ajax(
  {
    url: "query.php",
    type: "POST",
    data: { query         : 1,
            sgid          : $("#sgid").val(),
            gender_rep    : $("#gender_rep").val(),
            gradyear_rep  : $("#gradyear_rep").val(),
          },
    dataType: "json"
  }).done(function( msg ) 
  {
    // console.log(msg);
    var r = new Array();
    var date = "";
    $.each(msg, function (index, value) 
    {
      if(date==value.days)
      {
        // still on same date - push username
        r.push( '<li>' + value.firstname + ' ' + value.lastname + '</li>');
      }
      else
      {
        // new date - start a new row
        date = value.days;
        if(r.length>0)
        {
          r.push( '</ul></div>');
        }
        r.push( '<div data-role="collapsible"> ');
        r.push( '  <h1><div class="ui-li-count">' + value.subtotals + '</div>' + value.days + '</h1> ');
        r.push( '  <ul data-role="listview"> ');
        r.push( '    <li>' + value.firstname + ' ' + value.lastname + '</li>');
      }
    });
    r.push( '</ul></li>');

    $('#lv_attendance').html(r.join('')).listview("refresh");
    $('#lv_attendance').collapsibleset().trigger('create');
  });

  $('#checkins_opt').html(  'Options:<br />'+
                      'Date Range      - '+$("#daterange option:selected").text()+'<br />'+
                      'Small Group ID  - '+$("#sgid option:selected").text()+'<br />'+
                      'Gender          - '+$("#gender_rep option:selected").text()+'<br />'+
                      'Grad Year       - '+$("#gradyear_rep option:selected").text());

});

//********************************************************************************************************************************************
// generate content for all user history page 
//********************************************************************************************************************************************
$(document).on("pagebeforeshow", "#all_user_history", function () 
{
  $.ajax(
  {
    url: "query.php",
    type: "POST",
    data: { query         : 6,
            sgid          : $("#sgid").val(),
            gender_rep    : $("#gender_rep").val(),
            gradyear_rep  : $("#gradyear_rep").val(),
          },
    dataType: "json"
  }).done(function( msg ) 
  {
    var r = new Array();
    var user = "";

    $.each(msg, function (index, value) 
    {
      if(user==(value.firstname + ' ' + value.lastname))
      {
        // same user, continue adding dates
        r.push('<li>' + value.days + '</li>');
      }
      else
      {
        // new user, start new row
        r.push('<li data-role="list-divider">' + value.firstname + ' ' + value.lastname + ' - Attendance Records</li>');
        r.push('<li>' + value.days + '</li>');
        user = (value.firstname + ' ' + value.lastname);
      }
    });

    $('#all_user_attendance_lv').html(r.join('')).listview("refresh");

  });    

  $('#user_history_opt').html(  'Options:<br />'+
                      'Date Range      - '+$("#daterange option:selected").text()+'<br />'+
                      'Small Group ID  - '+$("#sgid option:selected").text()+'<br />'+
                      'Gender          - '+$("#gender_rep option:selected").text()+'<br />'+
                      'Grad Year       - '+$("#gradyear_rep option:selected").text());

});

//********************************************************************************************************************************************
// generate content for user info page 
//********************************************************************************************************************************************
$(document).on("pagebeforeshow", "#user_info_page", function () 
{
  $.ajax(
  {
    url: "query.php",
    type: "POST",
    data: { query         : 9,
            sgid          : $("#sgid").val(),
            gender_rep    : $("#gender_rep").val(),
            gradyear_rep  : $("#gradyear_rep").val(),
          },
    dataType: "json"
  }).done(function( msg ) 
  {
    // console.log(msg);
    var r = new Array();
    r.push( '<thead><tr><th>User</th><th>Email</th><th>Gender</th><th>Birthday</th><th>Grad Year</th><th>School Year</th><th>Phone</th><th>Small Group ID</th></tr></thead>')

    $.each(msg, function (index, value) 
    {
      r.push( '<tr><td>' + value.firstname + ' ' + value.lastname + '</td>' +
                  '<td>' + value.email + '</td>' +
                  '<td>' + value.gender + '</td>' +
                  '<td>' + value.birthday + '</td>' +
                  '<td>' + value.grad_year + '</td>' +
                  '<td>' + value.school_year + '</td>' +
                  '<td>' + value.cellphone + '</td>' +
                  '<td>' + value.small_group_id + '</td>' +
              '</tr>' );
    });

    $('#user_info_table')[0].innerHTML = r.join('');
  });    

  $('#all_users_history_opt').html(  'Options:<br />'+
                      'Date Range      - '+$("#daterange option:selected").text()+'<br />'+
                      'Small Group ID  - '+$("#sgid option:selected").text()+'<br />'+
                      'Gender          - '+$("#gender_rep option:selected").text()+'<br />'+
                      'Grad Year       - '+$("#gradyear_rep option:selected").text());

});

//********************************************************************************************************************************************
// handle login form  
//********************************************************************************************************************************************
$(document).ready(function() 
{
  $("#submit").click(function()
  {
    var formData = $("#login_form").serialize();

    $.ajax(
    {
        type: "POST",
        url: "login.php",
        cache: false,
        data: formData,
    });
    // return false;
  });
});