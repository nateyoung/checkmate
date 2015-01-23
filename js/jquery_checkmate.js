'use strict';

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

// var $loading = $('#loadingDiv').hide();
// $(document)
//   .ajaxStart(function () {
//     $loading.show();
//   })
//   .ajaxStop(function () {
//     $loading.hide();
//   });

var selectedUser;
var selectedUid;
var smallgroupIDs = [];
      
//********************************************************************************************************************************************
// handle login form  
//********************************************************************************************************************************************
$(document).ready(function() 
{
  $('#login_form').submit(function(e) {
    e.preventDefault();
    var form = this;

    $.ajax(
    {
      type: 'POST',
      url: 'pages/login.php',
      cache: false,
      data: { username: $('#username').val(), 
              password: $('#password').val()
            }
    }).done(function()
    {
      form.submit();
    });
  });

  // handle logout form
  $('#logout_form').submit(function(e) {
    e.preventDefault();
    var form = this;

    $.ajax(
    {
      type: 'POST',
      url: 'pages/login.php',
      cache: false,
      data: { username: 'asdf', 
              password: 'asdf'
            }
    }).done(function()
    {
      form.submit();
    });
  });

  // handle visitor registration form
  $('.validate-form').submit(function (e) {
    if (!this.checkValidity()) 
    {
      // Prevent default stops form from firing
      e.preventDefault();
      $(this).addClass('invalid');
      $('#status').html('invalid');
    } 
    else 
    {
      $(this).removeClass('invalid');
      $('#status').html('submitted');
    }
  });

  $('#visitor_form').submit(function(e) {

    e.preventDefault();
    e.stopImmediatePropagation(); // stop double-submitted forms
    
    if(this.checkValidity())
    {
      $('#popupLogin').popup('close');
  
      $.ajax(
      {
        url: 'pages/query.php',
        type: 'POST',
        cache: false,
        data: { query       : 10,
                fn          : $('#fn').val(),
                mn          : $('#mn').val(),
                ln          : $('#ln').val(),
                bday        : $('#bday').val(),
                gender      : $('#gender_select').val(),
                cell        : $('#cphone').val(),
                homeph      : $('#homeph').val(),
                email       : $('#email').val(),
                addrStreet  : $('#addrStreet').val(),
                addrCity    : $('#addrCity').val(),
                addrState   : $('#addrState').val(),
                addrZip     : $('#addrZip').val(),
                gradyear    : $('#gradeSelect').val() 
                },
      }).done(function()
      {
        // show popup
        $.gritter.add(
        {
          position: 'bottom-left',
          title : 'Welcome!',
          time : 2000,
          text : 'Registered and checked in ' + $('#fn').val() + ' ' + $('#ln').val()
        });
  
        // reset form after gritter done (500ms)
        setTimeout( function (){
          $('#visitor_form').trigger('reset');
        }, 500);
  
      });
    }
  });
/*
$('#visitor_form').validate({
  messages: {
    firstname: "First name is required.",
    lastname:  "Last name is required.",
    email:  "Email is required."
    //email: {
    //  required: "Email is required."
    //  email: "You must provide a valid email address."
    //}
  },
  focusInvalid: false
});
*/
});


//********************************************************************************************************************************************
// generate content for home page
//********************************************************************************************************************************************
$(document).on('pagecreate', '#home', function () 
{
  // listen for typed characters, populate listview with usernames that match
  $( '#users_lv' ).on( 'filterablebeforefilter', function ( e, data ) {
    var $ul = $( this ),
    $input = $( data.input ),
    value = $input.val(),
    html = '';
    $ul.html( '' );

    // wait until at least 2 chars are typed before querying DB
    if ( value && value.length >= 2 ) {
      $ul.html( '<li><div class="ui-loader"><span class="ui-icon ui-icon-loading"></span></div></li>' );
      $ul.listview( 'refresh' );
      var names=value.split(' ');
      // console.log("len:"+names.length);
      if(names.length>1) {
        // get student names that match - first and last name entered
        $.ajax(
        {
          url: 'pages/query.php',
          type: 'POST',
          data: {query: 13, fn: names[0], ln: names[1]},
          dataType: 'json'
        })
        .then( function ( response ) {
          $.each( response, function ( i, value ) {
            html += '<li data-filtertext="' + $('#filter-for-listview').val() + '" uname="' + value.firstname + ' ' + value.lastname  + '" uid="' + value.uid + '"><a href="#rightpanel">' + value.firstname + ' ' + value.lastname  + '</a></li>';
          });

          // update ul
          $ul.html( html );
          $ul.listview( 'refresh' );
          $ul.trigger( 'updatelayout');
        });
      }
      else {
        // get student names that match - only 1 name entered - don't know whether first or last, so check both
        $.ajax(
        {
          url: 'pages/query.php',
          type: 'POST',
          data: {query: 8, fn: names[0], ln: names[0]},
          dataType: 'json'
        })
        .then( function ( response ) {
          // console.log(response);
          $.each( response, function ( i, value ) {
            html += '<li data-filtertext="' + $('#filter-for-listview').val() + '" uname="' + value.firstname + ' ' + value.lastname  + '" uid="' + value.uid + '"><a href="#rightpanel">' + value.firstname + ' ' + value.lastname  + '</a></li>';
          });

          // update ul
          $ul.html( html );
          $ul.listview( 'refresh' );
          $ul.trigger( 'updatelayout');
        });
      }
    }
  });
});

//********************************************************************************************************************************************
// generate content for reports page 
//********************************************************************************************************************************************
$(document).on('pagebeforeshow', '#reports_page', function () 
{
  // query DB for attendance data
  $.ajax(
  {
    url: 'pages/query.php',
    type: 'POST',
    // data: {query: 7},
    data: {query: 14},
    dataType: 'json'
  }).done(function( msg ) 
  {
    // console.log(msg);

    // parse through returned data, populate array of dates+attendance
    var r = [];
    var date = '';
    var str = '';
    var gradYears = [];
    var total = 0;

    $.each(msg, function (index, value) 
    {
      // keep up w/ all possible gradYear values
      if(jQuery.inArray(value.gradYear,gradYears)===-1)
      {
         gradYears.push(value.gradYear);
      }
      // r.push( [new Date(value.date),value.attendees]);
      if(date===value.date)
      {
        // still on same day - keep appending data
        // console.log('same day: '+value.date);
        str += ', "'+value.gradYear+'":"'+value.attendees+'"';
        total = total + parseInt(value.attendees);
        //console.log('updated total:'+total);
      }
      else
      {
        // new day - begin new string
        date = value.date;
        // console.log('new day: '+value.date);
        
        // if str has anything in it, push it to r
        if(str.length)
        {
          // console.log('pushing: '+str+'}');
          //r.push(JSON.parse(str+',"Total":"'+total+'"}'));
          r.push(JSON.parse(str+'}'));
        }
        total = parseInt(value.attendees);
        //console.log('new total: '+total);

        str = '{"day":"'+value.date+'", "'+value.gradYear+'":"'+value.attendees+'"';
      }
      // r.push( {day:value.date, value:value.attendees});
    });
    // r.push( {day:value.date, value:value.attendees});

    // push last str
    r.push(JSON.parse(str+'}'));
    //console.log(r);
    //console.log(gradYears);

    // clear chart so multiples aren't created
    $('#total_att_chart').empty();

    // generate chart after 500ms
    setTimeout( function (){
/*
      new Dygraph(document.getElementById("total_att_chart"),
         // For possible data formats, see http://dygraphs.com/data.html
         // The x-values could also be dates, e.g. "2012/03/15"
         r, // data from database
         {
           // options go here. See http://dygraphs.com/options.html
           // legend: 'always',
           animatedZooms: true,
           xlabel: 'day',
           //ylabel: 'Attendance',
           ylabel: ['fresh','soph','jun','sen','unk'],
           //labels: ["date","attendance"],
           title: 'Attendance over time',
           fillGraph: true,
           rightGap: 20,
           drawPoints: true,
           pointSize : 3,
           highlightCircleSize: 5,
           // showRangeSelector: true,
         });
*/
      new Morris.Area({
        // ID of the element in which to draw the chart.
        element: 'total_att_chart',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: r,
        // The name of the data record attribute that contains x-values.
        xkey: ['day'],
        // A list of names of data record attributes that contain y-values.
        // ykeys: gradYears,
        ykeys: ['Freshman','Sophomore','Junior','Senior','unknown'],
        // Labels for the ykeys -- will be displayed when you hover over the
        // chart.
        labels: ['Freshman','Sophomore','Junior','Senior','unknown'],

        lineColors: ['#022245','#0D3D70','#22558C','#36689E','#5081B5'],
        //fillOpacity: 0.7
        /*
        hoverCallback: function (index, options, content) {
          console.log(content);
          return content;
          //var row = options.data[index];
          //return '<div class="hover-title">' + options.dateFormat(row.y) + '</div><b style="color: ' + options.lineColors[0] + '">' + row.x.toLocaleString() + " </b><span>" + options.labels[0] + "</span>";
        }
        */
      });
    }, 500);
  });
});

//********************************************************************************************************************************************
// only want to do this once (pagecreate) to maintain options
//********************************************************************************************************************************************
$(document).on('pagecreate', '#reports_page', function () 
{
  // get all smallgroup IDs from database and populate selectmenu
  $.ajax(
  {
    url: 'pages/query.php',
    type: 'POST',
    data: {query: 11},
    dataType: 'json'
  }).done(function( msg ) 
  {
    // console.log(msg);
    smallgroupIDs.push('<option value="%" selected="selected">All</option>'); // initial "all" option

    // iterate through data, populate selectmenu's options
    $.each(msg, function (index, value) 
    {
      smallgroupIDs.push('<option value="'+value.sgid+'">'+value.sgName+'</option>');
    });

    // populate w/ array r
    $('#sgid_att').html(smallgroupIDs.join(''));
    localStorage.setItem('smallgroupIDs', JSON.stringify(smallgroupIDs));

    // refresh selectmenu widget
    // $('#sgid').selectmenu('refresh', true);
  });

});


//********************************************************************************************************************************************
// listen for clicks on student name and update user panel accordingly
//********************************************************************************************************************************************
$(document).on('click', '#users_lv li' ,function () 
{
  selectedUser = $(this).attr('uname');
  selectedUid  = $(this).attr('uid');
  $('#output_text').html('Hi ' + selectedUser);
}); 

//********************************************************************************************************************************************
// listen for clicks on rightpanel
//********************************************************************************************************************************************
$(document).on('click', '#action_lv li' ,function () 
{
  // alert($(this).text());

  // clear filter and update user listview
  $('#filter-for-listview').val('');
  $('#filter-for-listview').trigger('keyup');

  // close panel
  $('#rightpanel').panel('close');

  if($(this).text() === 'Check in')
  {
    // checkin user
    $.ajax(
    {
      url: 'pages/query.php',
      type: 'POST',
      data: {uid: selectedUid, query: 2},
    });

    // show popup
    $.gritter.add(
    {
      position: 'bottom-left',
      title : 'Success!',
      time : 2000,
      text : 'Checked in ' + selectedUser
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
    //   text : 'Not implemented yet, ' + selectedUser
    // });         
  }
});

//********************************************************************************************************************************************
// generate content for user history page 
//********************************************************************************************************************************************
$(document).on('pagebeforeshow', '#user_history', function () 
{
    // Show user's history
    $.ajax(
    {
      url: 'pages/query.php',
      type: 'POST',
      data: {uid: selectedUid, query: 4},
    }).done(function( msg ) 
    {
      var r = [];

      r.push('<li data-role="list-divider">' + selectedUser + ' - Attendance Records</li>');

      $.each(msg, function (index, value) 
      {
        r.push( '<li>' + value.days + '</li>');
      });

      $('#user_attendance_lv').html(r.join('')).listview('refresh');

    });    
});

//********************************************************************************************************************************************
// generate content for attendance page 
//********************************************************************************************************************************************
function updateAttendanceTable()
{
  $.ajax(
  {
    url: 'pages/query.php',
    type: 'POST',
    data: { query         : 0,
            sgid          : $('#sgid_att').val(),
            genderRep     : $('#gender_att').val(),
            gradyearRep   : $('#gradyear_att').val(),
          },
    dataType: 'json'
  }).done(function( msg ) 
  {
    // alert(msg);
    var r = [];
    r.push( '<thead><tr><th>Date</th><th>Attendance</th></tr></thead>');

    $.each(msg, function (index, value) 
    {
      r.push( '<tr><td>' + value.days + '</td><td>' + value.attendees + '</tr>' );
    });

    r.push( '</tbody>');

    // alert(r);
    $('#attendance_table')[0].innerHTML = r.join('');
  });
}

$(document).on('pagebeforeshow', '#attendance_page', function () 
{
  // hack to preserve dynamically loaded selectmenu values - async problems?
  $('#sgid_att').html(JSON.parse(localStorage.getItem('smallgroupIDs')).join(''));

  // update attendance table w/ values from options selectmenus
  updateAttendanceTable();
});

// listen for option changes
$(document).on('change', '#daterange_att' , function() { updateAttendanceTable(); });
$(document).on('change', '#sgid_att'      , function() { updateAttendanceTable(); });
$(document).on('change', '#gender_att'    , function() { updateAttendanceTable(); });
$(document).on('change', '#gradyear_att'  , function() { updateAttendanceTable(); });


//********************************************************************************************************************************************
// generate content for stale users page 
//********************************************************************************************************************************************
function updateStaleUsersTable()
{
  $.ajax(
  {
    url: 'pages/query.php',
    type: 'POST',
    data: { query         : 5,
            daterange     : $('#daterange_stale').val(),
            sgid          : $('#sgid_stale').val(),
            genderRep     : $('#gender_stale').val(),
            gradyearRep   : $('#gradyear_stale').val(),
          },
    dataType: 'json'
  }).done(function( msg ) 
  {
    // console.log(msg);
    var r = [];
    r.push( '<thead><tr><th>User</th><th>Last Checkin</th></tr></thead>');

    $.each(msg, function (index, value) 
    {
      r.push( '<tr><td>' + value.firstname + ' ' + value.lastname + '</td><td>' + value.day + '</tr>' );
    });

    r.push( '</tbody>');

    $('#stale_users_table')[0].innerHTML = r.join('');
  });  

}

$(document).on('pagebeforeshow', '#stale_users_page', function () 
{
  // hack to preserve dynamically loaded selectmenu values - async problems?
  $('#sgid_stale').html(JSON.parse(localStorage.getItem('smallgroupIDs')).join(''));

  updateStaleUsersTable();
});

$(document).on('change', '#daterange_stale' , function() { updateStaleUsersTable(); });
$(document).on('change', '#sgid_stale'      , function() { updateStaleUsersTable(); });
$(document).on('change', '#gender_stale'    , function() { updateStaleUsersTable(); });
$(document).on('change', '#gradyear_stale'  , function() { updateStaleUsersTable(); });

//********************************************************************************************************************************************
// generate content for checkins page 
//********************************************************************************************************************************************
function updateCheckinsTable()
{
  // get attendance per day and create collapsible
  $.ajax(
  {
    url: 'pages/query.php',
    type: 'POST',
    data: { query         : 1,
            sgid          : $('#sgid_ci').val(),
            genderRep     : $('#gender_ci').val(),
            gradyearRep   : $('#gradyear_ci').val(),
          },
    dataType: 'json'
  }).done(function( msg ) 
  {
    // console.log(msg);
    var r = [];
    var date = '';
    $.each(msg, function (index, value) 
    {
      if(date===value.days)
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

    $('#lv_attendance').html(r.join('')).listview('refresh');
    $('#lv_attendance').collapsibleset().trigger('create');
  });
}

$(document).on('pagebeforeshow', '#checkins_page', function () 
{
  // hack to preserve dynamically loaded selectmenu values - async problems?
  $('#sgid_ci').html(JSON.parse(localStorage.getItem('smallgroupIDs')).join(''));

  updateCheckinsTable();
});

$(document).on('change', '#daterange_ci' , function() { updateCheckinsTable(); });
$(document).on('change', '#sgid_ci'      , function() { updateCheckinsTable(); });
$(document).on('change', '#gender_ci'    , function() { updateCheckinsTable(); });
$(document).on('change', '#gradyear_ci'  , function() { updateCheckinsTable(); });

//********************************************************************************************************************************************
// generate content for all user history page 
//********************************************************************************************************************************************
function updateUserHistoryTable()
{
  $.ajax(
  {
    url: 'pages/query.php',
    type: 'POST',
    data: { query         : 6,
            sgid          : $('#sgid_uh').val(),
            genderRep     : $('#gender_uh').val(),
            gradyearRep   : $('#gradyear_uh').val(),
          },
    dataType: 'json'
  }).done(function( msg ) 
  {
    var r = [];
    var user = '';

    $.each(msg, function (index, value) 
    {
      if(user===(value.firstname + ' ' + value.lastname))
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

    $('#all_user_attendance_lv').html(r.join('')).listview('refresh');

  });    
}

$(document).on('pagebeforeshow', '#all_user_history', function () 
{
  // hack to preserve dynamically loaded selectmenu values - async problems?
  $('#sgid_uh').html(JSON.parse(localStorage.getItem('smallgroupIDs')).join(''));

  updateUserHistoryTable();
});

$(document).on('change', '#daterange_uh' , function() { updateUserHistoryTable(); });
$(document).on('change', '#sgid_uh'      , function() { updateUserHistoryTable(); });
$(document).on('change', '#gender_uh'    , function() { updateUserHistoryTable(); });
$(document).on('change', '#gradyear_uh'  , function() { updateUserHistoryTable(); });

//********************************************************************************************************************************************
// generate content for user info page 
//********************************************************************************************************************************************
function updateUserInfoTable()
{
  $.ajax(
  {
    url: 'pages/query.php',
    type: 'POST',
    data: { query         : 9,
            sgid          : $('#sgid_ui').val(),
            genderRep     : $('#gender_ui').val(),
            gradyearRep   : $('#gradyear_ui').val(),
          },
    dataType: 'json'
  }).done(function( msg ) 
  {
    // console.log(msg);
    var r = [];
    r.push( '<thead><tr><th>User</th><th>Email</th><th>Gender</th><th>Birthday</th><th>Grad Year</th><th>School Year</th><th>Phone</th><th>Small Group</th></tr></thead>');

    $.each(msg, function (index, value) 
    {
      r.push( '<tr><td>' + value.firstname + ' ' + value.lastname + '</td>' +
                  '<td>' + value.email + '</td>' +
                  '<td>' + value.gender + '</td>' +
                  '<td>' + value.birthday + '</td>' +
                  '<td>' + value.gradYear + '</td>' +
                  '<td>' + value.schoolYear + '</td>' +
                  '<td>' + value.cellphone + '</td>' +
                  '<td>' + value.sgName + '</td>' +
              '</tr>' );
    });

    $('#user_info_table')[0].innerHTML = r.join('');
  });    
}

$(document).on('pagebeforeshow', '#user_info_page', function () 
{
  // hack to preserve dynamically loaded selectmenu values - async problems?
  $('#sgid_ui').html(JSON.parse(localStorage.getItem('smallgroupIDs')).join(''));

  updateUserInfoTable();
});

$(document).on('change', '#daterange_ui' , function() { updateUserInfoTable(); });
$(document).on('change', '#sgid_ui'      , function() { updateUserInfoTable(); });
$(document).on('change', '#gender_ui'    , function() { updateUserInfoTable(); });
$(document).on('change', '#gradyear_ui'  , function() { updateUserInfoTable(); });

//********************************************************************************************************************************************
// handle login form  
//********************************************************************************************************************************************
$(document).ready(function() 
{
  $('#submit').click(function()
  {
    var formData = $('#login_form').serialize();

    $.ajax(
    {
        type: 'POST',
        url: 'pages/login.php',
        cache: false,
        data: formData,
    });
    // return false;
  });
});