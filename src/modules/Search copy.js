import $ from "jquery"

class Search {
  // 1. describe and create/initiate our object
  constructor() {
    this.addSearchHTML()
    this.resultsDiv = $("#search-overlay__results")
    this.openButton = $(".js-search-trigger")
    this.closeButton = $(".search-overlay__close")
    this.searchOverlay = $(".search-overlay")
    this.searchField = $("#search-term")
    this.events()
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.previousValue;
    this.typingTImer;
  }

  // 2. events
  events() {
    this.openButton.on("click", this.openOverlay.bind(this))
    this.closeButton.on("click", this.closeOverlay.bind(this))
    this.searchField.on("keyup", this.typingLogic.bind(this))
    $(document).on('keydown', this.keyPressDispatcher.bind(this))
  }


  

  // 3. methods (function, action...)
  typingLogic() {
    if(this.searchField.val() != this.previousValue) {
      clearTimeout(this.typingTImer)

      if(this.searchField.val()) {
        if(!this.isSpinnerVisible) {
         this.resultsDiv.html('<div class="spinner-loader"></div>')
         this.isSpinnerVisible = true
        }
        this.typingTImer = setTimeout(this.getResults.bind(this), 800);
      } else {
        this.resultsDiv.html("")
        this.isSpinnerVisible = false
      }
    }
    this.previousValue = this.searchField.val()
  }

  getResults() {
    $.getJSON(universityData.root_url + '/wp-json/unversity/v1/search?term=' + this.searchField.val(), (results) => {
      this.resultsDiv.html(`
        <div class="row">
          <div class="one-third">
            <h2 class="search-overlay__section-title">General Information</h2>
            ${results.generalInfo.length ? '<ul class="link-list min-list">' : `<p>No general Information matches that search. <a href="${universityData.root_url}/posts">view all posts</a></p>`}
            ${results.generalInfo.map(item=> `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == 'post' ? `by ${item.autherName}`: ''} </li>`).join('')}
            ${results.generalInfo.length ? `</ul>` : ""}
            </div>
            <div class="one-third">
            <h2 class="search-overlay__section-title">Programs</h2>
            ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No Programs matche that search.<a href="${universityData.root_url}/program">View all Progams</a></p>`}
            ${results.programs.map(item=> `<li><a href="${item.permalink}">${item.title}</a> </li>`).join('')}
            ${results.professors.length ? `</ul>` : ""}
            <h2 class="search-overlay__section-title">Professors</h2>
             ${results.professors.length ? '<ul class="professor-cards">' : `<p>No Professor matche that search. </p>`}
            ${results.professors.map(item => `
              <li class="professor-card__list-item">
                <a class="professor-card" href="${item.permalink}">
                  <img class="professor-card__image" src="${item.image}">
                  <span class="professor-card__name">${item.title}</span>
                </a>
              </li>
              `).join('')}
            ${results.programs.length ? `</ul>` : ""}
            </div>
            <div class="one-third">
            <h2 class="search-overlay__section-title">Campuses</h2>
            ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No Campuses matche that search.<a href="${universityData.root_url}/campuses">View all Campuses</a></p>`}
            ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a> </li>`).join('')}
            ${results.campuses.length ? `</ul>` : ""}
            <h2 class="search-overlay__section-title">Events</h2>
            ${results.events.length ? '' : `<p>No Event match that search. <a href="${universityData.root_url}/event">View all Events</a></p>`}
            ${results.events.map(item=> `
              <div class="event-summary">
                <a class="event-summary__date t-center" href="${item.permalink}">
                  <span class="event-summary__month">${item.month}</span>
                  <span class="event-summary__day">${item.day}</span>
                </a>
                <div class="event-summary__content">
                  <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                  <p>${item.description} <a href="${item.permalink}" class="nu gray">Learn more</a></p>
                </div>
                </div>
              `).join('')}
            </div>
            </div>`)
            
    })

    // Using Sincronous way with async await
    // $.when(
    //   $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search='+ this.searchField.val()),
    //   $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search='+ this.searchField.val())
    // ).then((posts, pages) => {
    //   var combainedResults = posts[0].concat(pages[0])
    //     this.resultsDiv.html(`
    //     <h2 class="search-overlay__section-title">General Information</h2>
    //     ${combainedResults.length ? '<ul class="link-list min-list">' : '<p>No general Information matches that search.</p>'}
    //     ${combainedResults.map(item=> `<li><a href="${item.link}">${item.title.rendered}</a> ${item.type == 'post' ? `by ${item.autherName}`: ''} </li>`).join('')}
    //     ${combainedResults.length ? `</ul>` : ""}
    //     `)
    // }, () => {
    //   this.resultsDiv.html('<p>Unexpected error, please try again.</p>')
    // })

  }

  keyPressDispatcher(e) {
    if( e.keyCode == 83 && !this.isOverlayOpen ) {
      this.openOverlay()
    }
    if( e.keyCode == 27 && this.isOverlayOpen) {
      this.closeOverlay()
    }
    
  }


  openOverlay() {
    this.searchOverlay.addClass("search-overlay--active");
    $("body").addClass("body-no-scroll")
    this.searchField.val("")
    setTimeout(() => this.searchField.focus(), 301)
    this.isOverlayOpen = true
  }

  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay--active")
    $("body").removeClass("body-no-scroll")
    this.isOverlayOpen = false
  }

  addSearchHTML() {
    $("body").append(`<div class="search-overlay">
    <div class="search-overlay__top">
      <div class="container">
        <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
        <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
        <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
      </div>
    </div>
    <div class="container">
      <div id="search-overlay__results"></div>
  </div>
  </div>`)
  }
}
export default Search
	