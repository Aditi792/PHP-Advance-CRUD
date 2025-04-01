//function for pagination
function pagination(totalpages, currentspages) {
  let pageList = "";

  if (totalpages > 1) {
    currentspages = parseInt(currentspages);
    pageList += `<ul class="pagination justify-content-center">`;

    // Previous Button
    const prevClass=currentspages==1 ? "disabled" : "";
    pageList += `<li class="page-item ${prevClass}">
                  <a class="page-link" href="#" data-page="${currentspages - 1}">Previous</a>
                 </li>`;

    // Page Numbers
    for (let p = 1; p <= totalpages; p++) {
      const activeClass = currentspages == p ? "active" : "";
      pageList += `<li class="page-item ${activeClass}">
                      <a class="page-link" href="#" data-page="${p}">${p}</a>
                   </li>`;
    }

    // Next Button
    const nextClass = currentspages == totalpages ? "disabled" : "";
    pageList += `<li class="page-item ${nextClass}">
                    <a class="page-link" href="#" data-page="${currentspages + 1}">Next</a>
                 </li>`;
    pageList += `</ul>`;

    $("#Pagination").html(pageList);
  }
}


//function to get users from database
function getuserrows(user) {
  let userrow = "";
  if (user) {
    userrow = `<tr>
                <th scope="row"><img src=uploads/${user.photo}></th>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.number}</td>
                <td>
                    <a href="#" class="mr-3 text-success profile" data-bs-target="#preview" data-bs-toggle="modal" title="view profile" data-id=${user.id}><i class="fa-solid fa-eye mr-3"></i></a>

                    <a href="#" class="mr-3 text-info edituser"  data-bs-target="#userModal" data-bs-toggle="modal" title="edituser" data-id=${user.id}><i class="fa-solid fa-pen-to-square mr-3"></i></a>

                    <a href="#" class="mr-3 text-danger deletuser" title="delete" data-id=${user.id}><i class="fa-solid fa-trash mr-3"></i></a>
                </td>
            </tr>`;
  }
  return userrow;
}

//get users function
function getusers() {
  let pageno = $("#currentpage").val();
  $.ajax({
    url: "/PHP Advance CRUD/ajax.php",
    type: "GET",
    dataType: "json",
    data: {page:pageno, action: "getallusers"},
    beforeSend: function () {
      //console.log("waiting.....");
    },
    success: function (rows) {
      //console.log(rows);
      if (rows.users) {
        let userslist = "";

        $.each(rows.users, function (index, user) {
          userslist += getuserrows(user);
        });
        //adding table rows in the table
        $("#usertable tbody").html(userslist);

        //dynamic pagination
        let totaluser = rows.count;
        let totalpages = Math.ceil(parseInt(totaluser) / 4);
        const currentpages = $("#currentpage").val();
        
        pagination(totalpages, currentpages);
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error:", xhr.responseText); // ✅ Debug server response
    },
  });
}

//calling getuser function
getusers();

//loading document
$(document).ready(function () {
  //add user

  $(document).on("submit", "#addform", function (e) {
    e.preventDefault();

    //ajax
    $.ajax({
      url: "/PHP Advance CRUD/ajax.php",
      type: "POST",
      dataType: "json",
      data: new FormData(this),
      processData: false,
      contentType: false,
      beforeSend: function () {
       // console.log("waiting.....");
      },
      success: function (response) {
        //console.log(response);
        if (response) {
          $("#userModal").modal("hide");
          $("#addform")[0].reset();
          getusers();
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", xhr.responseText); // ✅ Debug server response
      },
    });
  });

  //onclick event for pagination
  $(document).on("click", "ul.pagination li a", function (event) {
    event.preventDefault();

    const pagenum = $(this).data("page");
    $("#currentpage").val(pagenum);
    getusers();

    $(this).parent().siblings().removeClass("active");  // Remove active class from all li
    $(this).parent().addClass("active"); // Add active class to parent <li>
  });


  //onclick event for editing
  $(document).on("click", "a.edituser", function () {
    let uid = $(this).data("id");
    $.ajax({
      url: "/PHP Advance CRUD/ajax.php",
      type: "GET",
      dataType: "json",
      data: { id: uid, action: "editusersdata" },
      beforeSend: function () {
        //console.log("waiting.....");
      },
      success: function (rows) {
        //console.log(rows);
        if (rows) {
          $("#username").val(rows.name);
          $("#email").val(rows.email);
          $("#number").val(rows.number);
          $("#userId").val(rows.id);
        }
      },
      error: function () {
        console.log("oops! ,something wrong "); // ✅ Debug server response
      },
    });
  });

  //onclick for adding user btn null
  $("#adduserbtn").on("click", function () {
    $("#addform")[0].reset();
    $("#userId").val("");
  });

  //onclick event for deleting
  $(document).on("click", "a.deletuser", function(e) {
    e.preventDefault();

    let uid = $(this).data("id");

    if (confirm("Are you want to delete ?")) {
      $.ajax({
        url: "/PHP Advance CRUD/ajax.php",
        type: "GET",
        dataType: "json",
        data: { id: uid, action: "deleteusersdata" },
        beforeSend: function () {
          //console.log("waiting.....");
        },
        success: function (response) {
          if (response.deleted == 1) {
            $(".displayMsg")
              .html(
                `<div class="alert alert-success alert-dismissible">Data Deleted Successfully !!
                </div>`
              )
              .fadeIn()
              .fadeOut(1000);
              getusers();
          }
        },
        error: function () {
          console.log("oops! ,something wrong ");
        },
      });
    }
  });

  //profile view
  $(document).on("click", "a.profile", function () {
    let uid = $(this).data("id");
    $.ajax({
      url: "/PHP Advance CRUD/ajax.php",
      type: "GET",
      dataType: "json",
      data: { id: uid, action: "editusersdata" },
      success: function (user) {
        if (user) {
          const profile = `<div class="row">
    <div class="col-sm-6 col-md-4">
        <img src="uploads/${user.photo}" class="rounded responsive"/>
    </div>
    <div class="col-sm-6 col-md-8">
        <h4 class="text-primary">${user.name}</h4>
        <p>${user.email}</p>
        <p>${user.number}</p>
    </div>
</div>`;
          $("#profile").html(profile);
        }
      },
      error: function () {
        console.log("oops! ,something wrong "); // ✅ Debug server response
      },
    });
  });

  //search
  $(document).on("keyup","#searchuser" ,function () {
    const searchText = $(this).val();

    if (searchText.length > 1) {
      $.ajax({
        url: "/PHP Advance CRUD/ajax.php",
        type: "GET",
        dataType: "json",
        data: { searchQuery: searchText, action: "searchUser" },
        success: function (users) {
          if (users) {
            let usersList = "";
            $.each(users, function (index, user) {
              usersList+=getuserrows(user);
            });
            $("#usertable tbody").html(usersList);
            $("#Pagination").hide();
          }
        },
        error: function () {
          console.log("oops! ,something wrong ");
        },
      });
    } else {
      getusers();
      $("#Pagination").show();
    }
  });

  //calling function when document is loaded
  getusers();
});
