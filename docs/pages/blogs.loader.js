var InBlog = false;
var pageOpen = true;
var docs;
const blogsPath = "./blogs/main.blogs.json"
const loadingBar = ".Mainnav .loading-bar #bar"
var bodyContent = ".MainContainer #blogs";
var progressbar = {
    total: 0,
    loaded: 0,
}


var cssref = document.createElement("link");
cssref.rel = 'stylesheet';
cssref.type = 'text/css';
document.getElementsByTagName("head")[0].appendChild(cssref);

function goHome() {
    
    $(loadingBar).css("width", "0%");

    $(bodyContent).empty();
    $(bodyContent).append(`<h1 class="contentLoading">Loading Blogs</h1>`);
    $(".header-blog-image .img-header").attr("src", ``);
    $(".in-blog-container").hide();
    $(".page-description").html("");
    $(".header-blog-image").removeClass("open");
    cssref.href = "";
    InBlog = false;
    $.getJSON(blogsPath).then((data) => {
        // on purpose error

        new SyntaxError("Error: No blogs found");

        // const nextURL = 'https://alexveebee.github.io/AlexVeeBee/pages/blog.index.html';
        const nextURL = `http://127.0.0.1:5500/docs/pages/blog.index.html`;
        const nextTitle = 'Loading';
        const nextState = { additionalInformation: '' };
        if (!pageOpen) {
            window.history.pushState(nextState, nextTitle, nextURL);
        }
        pageOpen = false;
        $(".page-title").html("Blogs")
        $(bodyContent).removeClass("blogs-item");
        $(bodyContent).addClass("blogs-home");

        $('.img-header').parent(".header").removeClass('img-loaded');
        $("body").removeClass("h-bkg");
            // in-blog-container">btn-icon

        // sort by date
        // data.sort((a, b) => {
        //     return new Date(b.date) - new Date(a.date);
        // });
        // add to page
        $(bodyContent).empty();
        data.forEach((blog, index) => {
            $(bodyContent).append(`
                <div class="blog card-hoverable" tabindex="1" onclick="goToBlog(${index})">
                    <div class="image">
                        <img width="200" height="${blog.smallImage ? "160" : "90"}" src="${blog.smallImage != null ? blog.smallImage : "https://media.discordapp.net/attachments/1025132161789075547/1044357878053621830/unknown.png" }" alt="">
                    </div>
                    <div class="info">
                        <div class="blog-title">
                            <h1>${blog.title}</h1>
                            ${
                                blog.description != null ? `<p>${blog.description}</p>` : ""
                            }
                        </div>
                        <div class="blog-date">
                            <span>${blog.date}</span>
                        </div>
                    </div>
                </div>
            `);
        }).catch((err) => {
            console.error(err);
        })
            const handleOnMouseMove = e => {
            const {currentTarget: target} = e
    
            const rect = target.getBoundingClientRect(),
                x = e.clientX - rect.left,
                y = e.clientY - rect.top;
    
                target.style.setProperty("--m-x", `${x}px`);
                target.style.setProperty("--m-y", `${y}px`);
            }

            for(const card of document.querySelectorAll(".card-hoverable")) {
                card.onmousemove = e => handleOnMouseMove(e);
            }
    })
}

function goToBlog(id) {
    $(bodyContent).empty();
    $(bodyContent).append(`<h1 class="contentLoading">Loading</h1>`);

    console.log(id);
    if (typeof id != "number") {
        if (typeof id == "string") {
            console.warn("String provided, attempting to convert to number");
        } else {
        console.error(
`No blog id provided!\n
    type: ${ typeof id == 'number' ? id : typeof id }
    id: ${id}`);
            goHome();
            return;
        }
    }

    var itemjson
    $.getJSON(blogsPath, (data) => {
        var item = data[id]
        itemjson = item
        // const nextURL = `https://alexveebee.github.io/AlexVeeBee/pages/blog.index.html?blogid=${id}`;
        const nextURL = `http://127.0.0.1:5500/docs/pages/blog.index.html?blogid=${id}`;
        const nextTitle = 'Loading';
        const nextState = { additionalInformation: '' };
        if (!pageOpen) {
            window.history.pushState(nextState, nextTitle, nextURL);
        }
        pageOpen = false;
    }).then(() => {
        $(bodyContent).addClass("blogs-item");
        $(bodyContent).removeClass("blogs-home");
        $(".in-blog-container").show();
        InBlog = true

        if (itemjson.title == undefined) {
        } else {
            $(".page-title").html(itemjson.title)
            itemjson.description ? $(".page-description").html(itemjson.description) : $(".page-description").html("");
        }

        if (itemjson.hasHeaderImage) {
            $(".header-blog-image").addClass("open");
            $(".header-blog-image .img-header").attr("src", `${itemjson.headerImage}`);
            $('.img-header').parent(".header").removeClass('img-loaded');
            $("body").addClass("h-bkg");
            var img = $('.img-header');

            img.on('load', function() {
                $('.img-header').parent(".header").addClass('img-loaded');
                // remove event listener
                img.off('load');
            });
            if (img[0].complete) {
                $('.img-header').parent(".header").addClass('img-loaded');
            }
        }

        if (itemjson.CSS_PATH != undefined) {
            progressbar.total += 1;
            cssref.href = "./blogs/"+itemjson.CSS_PATH;
        }
        $(bodyContent).empty();
        $(bodyContent).load("./blogs/"+itemjson.html, () => {
        //     $("body .main-Page .content .contentLoading").remove();
        //     var devBlogInfoContainer = "body .main-Page .content .devblog-container"
        //     // $(devBlogInfoContainer+" .NewsTitle[set]").html(itemjson.title)
        //     // $(devBlogInfoContainer+" .NewsDescription[set]").html(itemjson.description)
        //     // $(devBlogInfoContainer+" .NewsDate-release[set]").html(itemjson.newsReleased)
        //     // $("body .main-Page .content .devblog-container *[devblog_section_userid] ").each(( t, e ) => {
        //     //     var elementTarget = this
        //     //     var id = $(e).attr("devblog_section_userid")
        //     //     $(e).html("by "+users[id].name+" "+users[id].rank)
        //     // })
        });
    })
}

$(document).ready(() => {
    $.getJSON(blogsPath, (u) => {
        console.log("loaded")
        docs = u;
    })
//    $(".pageBkg-img").append(`<audio controls class="pageBkg-audio"autoplay><source src="./objects/Audio/Background Music.mp3" type="audio/mp3"></audio>`)
   // $(".pageBkg-img .pageBkg-audio").attr("src","./objects/Audio/Background Music.mp3")

    // shhhh, this is copied from stackoverflow | credit: Sameer Kazi   
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
        return false;
    };
    // 
    // $(bodyContent).empty();
    $(bodyContent).append(`<h1 class="contentLoading">Loading</h1>`);

    if (!getUrlParameter("blogid")) {
        goHome()
    } else {
        goToBlog(getUrlParameter("blogid"))
    }
    $(window).on('popstate', function(e){
        if (!getUrlParameter("blogid")) {
            pageOpen = true;
            goHome()
        } else {
            pageOpen = true;
            goToBlog(getUrlParameter("blogid"))
        }
    });
    // var sidebar = $(".mainPage .sidebar-container")
    // var closeBox = $(".mainPage .sidebar-mobile-closeBox")
})