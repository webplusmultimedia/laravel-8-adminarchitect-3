(function () {
    window.getCookie = function (name) {
        var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        if (match) return match[2];
    }

    window.afficheMessage = function (message, error = 'success') {
        document.getElementById("msgErreur").innerHTML = `<div role="alert" class="alert alert-${error} alert-dismissible">
        <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
         <p>${message}</p>
    </div>`
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }


    const dropFileZone = document.getElementById("yasala-zones")
    const _form = document.getElementById("yasala-galerie")
    const inputFile = document.getElementById("yasala-inputfile")
    const dropZone = document.getElementById("yasalaDropzone")
    //const errorZone = document.getElementById("errorBag")
    const ancre = dropFileZone ? dropFileZone.getAttribute('data-ancre') : null
    const collection = dropFileZone && dropFileZone.getAttribute('collection') ? dropFileZone.getAttribute('collection') : 'default'
    const module = dropFileZone ? dropFileZone.getAttribute('data-module') : null
    const modelId = dropFileZone ? dropFileZone.getAttribute('data-model-id') : null
    const prefix_url = dropFileZone ? dropFileZone.getAttribute('data-prefix') : null
    const delete_a = $('.detete-img');
    let maxFileSize = 0
    let maxImages = 0
    let images
    /*if (ancre) {
        window.open(window.location.href + '#' + ancre, '_parent')
    }*/

    if (dropFileZone) {
        maxFileSize = dropFileZone.getAttribute('data-maxFileSize') ? parseInt(dropFileZone.getAttribute('data-maxFileSize')) : 2000
        maxImages = parseInt(dropFileZone.getAttribute('data-maxImage'))
        images = dropFileZone.getAttribute('data-images') ? JSON.parse(dropFileZone.getAttribute('data-images')) : null
        //console.log(images)
    }

    if (dropZone) {
        const message = dropZone.querySelector(".dz-message")
        const image = dropZone.querySelector(".img-preview")
        const error_div = dropZone.querySelector('.dz-error');
        const error_msg = error_div.querySelector('.dz-error-message')
        const progressZone = dropZone.querySelector(".dz-progress")

        if (images) {
            for (let i = 0; i < images.data.length; i++) {
                let el = document.createElement('div', {is: 'yasala-zone'})
                el.setAttribute('data-image', JSON.stringify({id: images.data[i].id, name: images.data[i].name}))
                dropFileZone.appendChild(el)
            }
        }


        dropZone.addEventListener('click', function () {
            error_div.style.display = 'none';
            message.style.display = 'block';
            inputFile.click();
        })

        inputFile.addEventListener('change', handleFiles, false)

        dropZone.addEventListener('drop', dropHandler, false)

        /*
        *  ulopd a file by xhr
        * */
        function uploadFiles(files) {

            if (isTooMoreImages(files)) {
                error_div.style.display = 'block';
                message.style.display = 'none'
                error_msg.innerHTML = `Le nombre maximal de fichier à télécharger est de ${maxImages}`
                return false
            }

            for (var i = 0; i < files.length; i++) {

                if (submitFiles(files[i]) === true) {
                    let formData = new FormData(),
                        xhr = new XMLHttpRequest();
                    const nom_fichier = `_media_[${collection}]`

                    formData.append("_token", dropFileZone.getAttribute("data-token"));
                    formData.append(nom_fichier, files[i]);
                    xhr.open('POST', dropFileZone.getAttribute("data-url"));
                    xhr.responseType = 'json';

                    xhr.upload.onprogress = function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = parseInt((evt.loaded / evt.total) * 100);
                            progressZone.style.width = `${percentComplete}%`;
                        }
                    };


                    //xhr.addEventListener('progress', progressHandler, false);
                    xhr.upload.addEventListener('loadstart', loadStartHandler, false)

                    xhr.upload.addEventListener('loadend', loadEndHandler, false)
                    xhr.addEventListener('load', loadHandler, false);

                    xhr.send(formData);

                }
            }
        }

        function loadStartHandler(e) {
            progressZone.style.width = '0'
        }

        function loadEndHandler(e) {
            //progressZone.style.width = 0
        }


        function loadHandler() {

            let nbImages = dropFileZone.childElementCount - 1;

            if (this.status === 200 || this.status === 20 ) {
                nbImages++;
                const data = this.response;
                let el = document.createElement('div', {is: 'yasala-zone',data : 'ok'})
                el.setAttribute('data-image', JSON.stringify({id: data.id, name: data.name}))
                dropFileZone.appendChild(el);

                window.afficheMessage("L'image a pas été enregistré");
                if (parseInt(nbImages) === maxImages) {
                    dropZone.style.display = 'none';
                }

            } else {

                window.afficheMessage("L'image n'a pas été enregistré", 'danger');
            }
        }

        function handleFiles() {
            uploadFiles(this.files);
        }

        function submitFiles(file) {
            if (file) {
                if (file.size / 1000 > maxFileSize) {
                    error_div.style.display = 'block';
                    message.style.display = 'none'
                    error_msg.innerHTML = `Votre image est supérieur à ${maxFileSize / 1000} Mo`
                    return false
                }

                const typeImage = ["image/png", "image/jpeg"];

                if (typeImage.find(v => v === file.type))
                    return true;
                error_div.style.display = 'block';
                message.style.display = 'none'
                error_msg.innerHTML = `Uniquement des fichiers images<br>(jpeg ou png)`
                return false

            } else {
                error_div.style.display = 'none';
                message.style.display = 'block';
                return false
            }
        }

        function isTooMoreImages(files) {
            if (maxImages === 0)
                return false;
            const nb_files_load = files.length;

            const nbImages = parseInt(dropFileZone.childElementCount - 1)

            return nbImages + nb_files_load > maxImages
        }

        function dropHandler(event) {
            event.preventDefault();
            dropZone.style.borderColor = '#a6a6a6';
            uploadFiles(event.dataTransfer.files);
        }

        dropZone.addEventListener('dragover', function (event) {
            event.preventDefault();
            dropZone.style.borderColor = '#1F9ED6';
            dropZone.style.backgroundColor = 'rgba(31,158,214,0.1)';
            return false;
        })

        dropZone.addEventListener('dragleave', function (event) {
            event.preventDefault()
            dropZone.style.borderColor = '#a6a6a6';
            dropZone.style.backgroundColor = "transparent";
            return false;
        })


    }

    let dragged, startKey, keyEnd

    class YasalaImageZone extends HTMLDivElement {


        constructor() {
            super();
            this.setAttribute('class', "yasala-zone")
            this.addEventListener("drag", this.onDrag, false)
            this.addEventListener("dragend", this.onDragEnd, false)
            this.addEventListener("dragover", this.onDragHover, false)
            this.addEventListener("drop", this.onDrop, false)
            this.addEventListener("dragenter", this.onDragEnter, false)
            this.addEventListener("dragleave", this.onDragLeave, false)

        }

        connectedCallback(e) {
            if(!this.getAttribute('data-id'))
                this.setImage()
        }

        onDrag(event) {
            event.preventDefault()
            // store a ref. on the dragged elem
            dragged = event.target;
            startKey = this._getIndexNode(event.target.parentNode)
            //console.info(dragKey)
            // make it half transparent
            event.target.style.opacity = .5;

        }

        onDragEnd(event) {
            // reset the transparency
            event.target.style.opacity = "";
        }

        onDragHover(event) {
            // prevent default to allow drop
            //console.info('over : ', dragKey)
            event.preventDefault();
        }

        onDragEnter(event) {
            // highlight potential drop target when the draggable element enters it

            if (event.target.className === "img" && dragged !== event.target) {
                keyEnd = this._getIndexNode(event.target.parentNode)
                let zone = event.target.parentNode
                zone.style.background = "purple";
            }

        }

        onDragLeave(event) {
            // reset background of potential drop target when the draggable element leaves it
            if (event.target.className === "img" && dragged !== event.target) {
                let zone = event.target.parentNode
                zone.style.background = "";
            }

        }

        onDrop(event) {
            // prevent default action (open as link for some elements)
            event.preventDefault();

            // console.log( 'drag && target',dragged , event.target)
            // move dragged elem to the selected drop target
            if (event.target.className === "img" && dragged !== event.target) {
                // console.log(dragged.parentNode)
                let zoneEnd = event.target.parentNode
                let zoneStart = dragged.parentNode
                zoneStart.style.background = "";
                zoneEnd.style.background = "";
                let grandeZone = zoneStart.parentNode
                let dropFileZone = document.getElementById("yasala-zones")

                let parentss = dropFileZone.parentNode.querySelectorAll('.yasala-zone')

                // si end > start
                if (keyEnd > startKey)
                    grandeZone.insertBefore(parentss[startKey], parentss[keyEnd + 1])
                else if (keyEnd < startKey)
                    grandeZone.insertBefore(parentss[startKey], parentss[keyEnd])


                let ids = [];
                let parentNodes = document.getElementById("yasala-zones").parentNode.querySelectorAll('.yasala-zone')
                parentNodes.forEach((el, key) => {
                    ids[key] = (parseInt(el.getAttribute('data-id')))
                })

                if (ids)
                    this.ajaxReorder(ids)


            }
        }

        ajaxReorder(ids) {
            let formData = new FormData()

            formData.append("_token", dropFileZone.getAttribute("data-token"));
            ids.forEach(id => formData.append("ids[]", id))

            const request = new Request(dropFileZone.getAttribute("data-url-reorder"), {method: 'POST', body: formData})

            fetch(request)
        }

        _getIndexNode(target) {
            let zoneYasalaNode = dropFileZone.parentNode.querySelectorAll('.yasala-zone')

            let index = null;
            for (let i = 0; i < zoneYasalaNode.length; i++) {
                if (zoneYasalaNode.item(i) === target)
                    index = i
            }
            return index
        }

        _createImage(image) {

            const img = document.createElement('img')

            let thumbImg = image.name.replace(/ /g, '-') + '-thumb.jpg'

            img.setAttribute('src', `/uploads/${image.id}/conversions/${thumbImg}`)
            img.setAttribute('class', 'img')
            img.setAttribute('alt', `${image.name}`)
            return img
        }

        _createZone(image) {
            const yasazone = document.createElement('div')
            yasazone.setAttribute('class', 'ya-name')
            let linkY = document.createElement('a', {is: 'yasala-delete-icone'})

            linkY.setAttribute('data-id', `${image.id}`)
            yasazone.appendChild(linkY)
            return yasazone
        }

        setImage() {
            const image = JSON.parse(this.getAttribute('data-image'))
            this.setAttribute('data-id', image.id)
            this.appendChild(this._createImage(image))
            this.appendChild(this._createZone(image))
        }

    }

    customElements.define('yasala-zone', YasalaImageZone, {extends: 'div'});


    class YasalaAnchorDelete extends HTMLAnchorElement {

        constructor() {
            super();
            this.addEventListener("click", this.onclick)

            this.style = "cursor: pointer;"
            this.title = "Supprimer cette image"
            this.href = `/${prefix_url}/${module}/${modelId}/media/`
            this.href = this.href + this.getAttribute('data-id')
            this.appendChild(this.createIcon())
        }


        onclick(e) {
            e.preventDefault()
            const result = confirm(`Voulez-vous Supprimer cette image ?`);
            if (result) {
                this.deleteImage()
            }
        }

        connectedCallback(e) {

        }

        createIcon() {
            const el = document.createElement("i")
            el.setAttribute('class', "fa fa-trash-o")
            return el;
        }

        deleteImage() {
            let formData = new FormData()
            const header = new Headers()
            header.set('X-XSRF-TOKEN', decodeURIComponent(getCookie('XSRF-TOKEN')))

            const component = this
            formData.append("_token", dropFileZone.getAttribute("data-token"));
            const request = new Request(component.href, {headers: header, method: 'delete'})
            fetch(request)
                .then((response) => {
                    return response.status
                })
                .then(status => {
                    if (status === 200 || status === 204) {
                        component.parentElement.parentElement.remove()
                        window.afficheMessage("L'image a été suprimée")
                    } else
                        window.afficheMessage("Problème de suppression de l'image <br>Merci de recommencer", 'danger')
                })
                .catch((e) => console.log(e))
        }


    }

    customElements.define('yasala-delete-icone', YasalaAnchorDelete, {extends: 'a'});


}());
