// modal api
var modalapi = {
    init: function() {
        // add modal container to the top of the body
        var modalcontainer = document.createElement('div');
        modalcontainer.id = 'modal-contianer';
        modalcontainer.className = 'modal-container';
        // add modal
    },
    // open modal
    open: function() {
        // show modal
        $('#modal-contianer').show();
    },
    // close modal
    close: function({removeall = false}) {
        // hide modal
        $('#modal-contianer').hide();
        // remove all modal
        if (removeall) {
            $('#modal-contianer #modal').empty();
        }
    },
    // set modal content
    addmodal: function({title, content, buttons}) {
        // add modal
        if (content) {
            // check ig the element is a string
            if (typeof content === 'string') {
                // add the string to the modal
                var itemmodal = document.createElement('div');
                // modal title
                if (title) {
                    var itemtitle_container = document.createElement('div');
                    var itemtitle = document.createElement('h1');
                    itemtitle_container.className = 'modal-title-container';
                    itemtitle.className = 'modal-title';
                    itemtitle.innerHTML = title;
                    itemtitle_container.appendChild(itemtitle);
                    itemmodal.appendChild(itemtitle_container);
                }
                // body
                var itembody = document.createElement('div');
                itembody.innerHTML = content;
                itembody.className = 'modal-body';
                itemmodal.appendChild(itembody);
                itemmodal.id = 'modalcontent';
                itemmodal.className = 'modal-dialog';
                // add buttons
                if (buttons) {
                    var itembuttons = document.createElement('div');
                    itembuttons.className = 'modal-buttons';
                    // add buttons
                    for (var i = 0; i < buttons.length; i++) {
                        var itembutton = document.createElement('button');
                        itembutton.innerHTML = buttons[i].text;
                        itembutton.onclick = buttons[i].onclick;
                        itembuttons.appendChild(itembutton);
                    }
                    itemmodal.appendChild(itembuttons);
                }
                $('#modal-contianer #modal').append(itemmodal);
                return itemmodal;
            } else {
                content.id = 'modalcontent';
                // add the element to the modal
                $('#modal-contianer #modal').html(content.otuerHTML);
            }
        }
    },
    // set modal content
    setContent: function(content) {
        // set modal content
        $('#modal-contianer #modal-content').html(content);
    }
};
