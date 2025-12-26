import $ from "jquery"
class MyNotes {
  constructor() {
    this.events()
  }

  events() {
    $("#my-notes").on("click", ".delete-note", this.deleteNote);
    $("#my-notes").on("click", ".edit-note", this.editNate.bind(this));
    $("#my-notes").on("click", ".update-note", this.upDateNote.bind(this));
    $(".submit-note").on("click", this.createNote.bind(this));


  }

  //Edit Note Methode

  editNate(e) {
    var thisNote = $(e.target).parents("li");
    if (thisNote.data("state") == "editable") {
      this.makeNoteReadOnly(thisNote);
    }else {
      this.makeNoteEditable(thisNote);
    }
  }

  //Make Note Editable Methode
  makeNoteEditable(thisNote) {
    thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i> Cancel');
    thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
    thisNote.find(".update-note").addClass("update-note--visible");
    thisNote.data("state", "editable");
  }
  makeNoteReadOnly(thisNote) {
    thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');
    thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
    thisNote.find(".update-note").removeClass("update-note--visible");
    thisNote.data("state", "cancel");

  }

  // Methods come here
  deleteNote(e) {
    var thisNOte = $(e.target).parents("li");
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce)
      },
      url: universityData.root_url + "/wp-json/wp/v2/note/" + thisNOte.data('id'),
      type: "DELETE",
      success: (responce) => {
        thisNOte.slideUp();
        console.log("Congrats!");
        console.log(responce);
      },
      error: (responce) => {
        console.log("Sorry");
        console.log(responce);
      }
    })
  }
  upDateNote(e) {
    var thisNote = $(e.target).parents("li");
    var ourUpdatedPost = {
      "title" : thisNote.find(".note-title-field").val(),
      "content" : thisNote.find(".note-body-field").val()
    }
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce)
      },
      url: universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.data('id'),
      type: "POST",
      data: ourUpdatedPost,
      success: (responce) => {
        this.makeNoteReadOnly(thisNote);
        console.log("Congrats!");
        console.log(responce);
      },
      error: (responce) => {
        console.log("Sorry");
        console.log(responce);
      }
    })
  }

  createNote(e) {
    var ourNewPost = {
      "title" : $(".new-note-title").val(),
      "content" : $(".new-note-body").val(),
      "status" : "publish"
    }
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce)
      },
      url: universityData.root_url + "/wp-json/wp/v2/note/",
      type: "POST",
      data: ourNewPost,
      success: (responce) => {
        $('.new-note-title, .new-note-body').val(''),
        $(`<li data-id="${responce.id}">
          <input readonly class="note-title-field" value="${responce.title.raw}"></input>
          <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
          <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
          <textarea readonly class="note-body-field" readonly>${responce.content.raw}</textarea>
          <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
      </li>
          `).prependTo("#my-notes").hide().slideDown(),
        console.log("Congrats!");
        console.log(responce);
      },
      error: (responce) => {
        console.log("Sorry");
        console.log(responce);
      }
    })
  }

}

export default MyNotes;