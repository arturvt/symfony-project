

var listContent = {
  people: [
    {firstName: "YYYehuda", lastName: "Katz"},
    {firstName: "Carl", lastName: "Lerche"},
    {firstName: "Alan", lastName: "Johnson"}
  ]
}

Handlebars.registerHelper('list', function(items, options) {
  var out = "<ul>";

  for(var i=0, l=items.length; i<l; i++) {
    out = out + "<li>" + options.fn(items[i]) + "</li>";
  }

  return out + "</ul>";
});


var source   = $("#list-template").html();
var template = Handlebars.compile(source);

var html = template(listContent);

$('#list').append(html);


loadJSON();



function loadJSON() {

    $.ajax({
        type: "GET",
        datatype: "json",
        url: "http://localhost:8001/app_dev.php/blog/user/1",

        beforeSend: function () {
            $("#footer").css("visibility", "visible");
        },

        success: function successExpandedPortfolio(response) {
            console.log('Success!');
            console.log('Status: ', response.status);


            var data = response;
            var items = [];

            console.log(response);

            $.each(data.people, function (key, val) {
                items.push("<li>" + val.name + ' ' + val.lastName + "</li>");
            });

            $("<ul/>", {
                "class": "my-new-list",
                html: items.join("")
            }).appendTo("body");
            $('#list').append();
            $("#footer").css("visibility", "hidden");
            renderJson();

        },

        error: function errorExpandedPortfolio(response) {
            console.log('Error! Response: ', response);
            $("#footer").css("background-color", "red").text("Error loading Json. Reason: "+response.statusText);

        },

        timeout: function timeoutReached(response) {
            console.log(response);
        },

        complete: function end() {
            console.log('Complete!');
        }
    });
}

function renderJson() {
    var listContent = {
        people: [
            {firstName: "xxxx", lastName: "Katz"},
            {firstName: "Caxxxrl", lastName: "Lerche"},
            {firstName: "xxx", lastName: "Johnson"}
        ]
    }

    Handlebars.registerHelper('list', function(items, options) {
        var out = "<ul>";

        for(var i=0, l=items.length; i<l; i++) {
            out = out + "<li>" + options.fn(items[i]) + "</li>";
        }

        return out + "</ul>";
    });


    var source   = $("#list-json").html();
    var template = Handlebars.compile(source);

    var html = template(listContent);

    $('#list').append(html);
}

