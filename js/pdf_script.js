var totalImage = 0;
    var index = 0;
    var mainInde = 0;
    var beforeData = 0;
    const allConvertedFile = [];
    const itemsToRemove = [];
    const shouldStop = [];

    const dropArea = document.getElementById('drop_down_body');
    const fileInput = document.getElementById('choose_image');

    dropArea.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropArea.style.borderColor = 'blue';
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.style.borderColor = '#ccc';
    });

    dropArea.addEventListener('drop', (event) => {
        event.preventDefault();
        dropArea.style.borderColor = '#ccc';

        const files = event.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;

            // Create and dispatch a custom event
            const changeEvent = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(changeEvent);
        }
    });

    // Event listener for file input change event
    fileInput.addEventListener('change', loadFile);


    var loadFile = function (event) {
        const input = event.target;
        let maxFile = '<?php echo esc_html($setting_row->max_file_upload); ?>';
        if (input.files.length > maxFile) {
            alert("You can upload a maximum of " + maxFile + " files.");
            return false;
        }
        document.getElementById("drop_down_body").style.pointerEvents = "none";
        
        document.getElementById("again-button").style.pointerEvents = "painted";
        document.getElementById("again-button").style.opacity = "1";

        if(totalImage == 0){
            document.getElementById("drag-drop-text").remove();
        }
        
        beforeData = totalImage;
        totalImage = totalImage + input.files.length;
        document.getElementsByClassName("download-count")[0].innerHTML = totalImage;
        document.getElementById("selected-image-grid").classList.add("drag-display-mode");
 
        for (mainInde; mainInde < totalImage; mainInde++) {
            var selectedFileElement = document.getElementById('selected-image-grid');
            var divRoot = document.createElement('div');

            divRoot.id = "item-model-" + mainInde;
            var gridDiv = document.createElement('div');
            gridDiv.className = "grid-content";
            var displayDiv = document.createElement('div');
            displayDiv.className = "display-div";
            var image = document.createElement('img');
            image.className = "display-image";
            image.loading = "lazy"
            image.height = "150"
            var blurDiv = document.createElement('div');
            blurDiv.className = "absolute blur-area";
            var controlBox = document.createElement('div');
            controlBox.className = "control-box";
            var extraLayout = document.createElement('div');
            extraLayout.className = "extra-box-layout";
            var actionType = document.createElement('div');
            actionType.className = "action-type";
            var actionIcon = document.createElement('i');
            actionIcon.className = "heic-action-icon zp zp-cloud-upload";
            var actionText = document.createElement('span');
            actionText.className = "heic-action-text";
            actionText.innerHTML = "Uploading";
            actionType.appendChild(actionIcon);
            actionType.appendChild(actionText);
            extraLayout.appendChild(actionType);
            var progressHolder = document.createElement('div');
            progressHolder.className = "progress-holder";
            var progressParcent = document.createElement('span');
            progressParcent.className = "progress-parcent";
            progressParcent.innerHTML = "0%";
            var uploadProgress = document.createElement('div');
            uploadProgress.className = "upload-progress";
            displayDiv.appendChild(image);
            displayDiv.appendChild(blurDiv);
            blurDiv.appendChild(controlBox);
            controlBox.appendChild(extraLayout);
            extraLayout.appendChild(progressHolder);
            progressHolder.appendChild(progressParcent);
            progressHolder.appendChild(uploadProgress);

            gridDiv.appendChild(displayDiv);
            divRoot.appendChild(gridDiv);

            selectedFileElement.appendChild(divRoot);
        }

        converterLoop();

        // if (input.files && input.files[0]) {
        //     const file = input.files[0];
        //     $('#selected-file').html('<div><span>.../'+file.name+'</span></div>');
        //     var dropDownBody = document.getElementById("drop_down_body");
        //     dropDownBody.style.opacity = "0.5";
        //     dropDownBody.style.pointerEvents = "none";

        //     var gDriveBtn = document.getElementById("g_drive_btn");
        //     var dropBoxBtn = document.getElementById("drop_box_btn");
        //     gDriveBtn.style.opacity = "0.5";
        //     dropBoxBtn.style.opacity = "0.5";
        //     gDriveBtn.style.cursor = "default";
        //     dropBoxBtn.style.cursor = "default";


        //     // // Check if the file is a HEIC file
        //     // if (file.type === 'image/heic' || file.name.endsWith('.heic')) {
        //     //     heic2any({
        //     //         blob: file,
        //     //         toType: "image/jpeg",
        //     //     }).then(function (convertedBlob) {
        //     //         const output = document.getElementById('output');
        //     //         output.src = URL.createObjectURL(convertedBlob);
        //     //         output.onload = function () {
        //     //             URL.revokeObjectURL(output.src); // free memory
        //     //         };
        //     //     }).catch(function (error) {
        //     //         console.error('Error converting HEIC to JPEG:', error);
        //     //     });
        //     // } else {
        //     //     // Display image preview for non-HEIC images
        //     //     const output = document.getElementById('output');
        //     //     output.src = URL.createObjectURL(file);
        //     //     output.onload = function () {
        //     //         URL.revokeObjectURL(output.src); // free memory
        //     //     };
        //     //     console.log(file, output.src);
        //     // }
        // }

    };


    function converterLoop() {
        for (index; index < totalImage; index++) {
            uploadImage(index);
        }
    }

    function uploadImage(layoutId) {
        const fileInput = document.getElementById('choose_image');
        var currentCount = layoutId - beforeData;
        const file = fileInput.files[currentCount];

        if (!file) {
            alert('Please select a file.');
            return;
        }

        const formData = new FormData();
        formData.append('image', file);
        formData.append('format', "pdf");

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../wp-content/plugins/heicconverter_plugin/ajax/upload.php', true);

        xhr.upload.addEventListener('progress', function (event) {
            if (event.lengthComputable) {
                var percentComplete = (event.loaded / event.total) * 100;
                document.getElementsByClassName("upload-progress")[layoutId].style.width = percentComplete + "%";
                document.getElementsByClassName("progress-parcent")[layoutId].innerHTML = parseInt(percentComplete) + "%";
                if (percentComplete >= 100) {
                    document.getElementsByClassName("upload-progress")[layoutId].style.width = "0%";
                    document.getElementsByClassName("progress-parcent")[layoutId].innerHTML = "0%";
                    document.getElementsByClassName("heic-action-icon")[layoutId].classList.remove("zp-cloud-upload");
                    document.getElementsByClassName("heic-action-icon")[layoutId].classList.add("zp-sync");
                    document.getElementsByClassName("heic-action-text")[layoutId].innerHTML = "Converting";
                    loopWithSleep(layoutId);
                }
            }
        });

        xhr.addEventListener('load', function () {
            if (xhr.status === 200) {
                shouldStop.push({ id: layoutId, status: true });
                const response = JSON.parse(xhr.responseText);
               
                var closeItem = document.createElement('i');
                closeItem.style.pointerEvents = "painted";
                closeItem.className = "zp zp-close";
                closeItem.setAttribute("onclick", "itemClose('" + response.download_path + "', '" + layoutId + "')");
                document.getElementsByClassName("blur-area")[layoutId].appendChild(closeItem);

                document.getElementsByClassName("extra-box-layout")[layoutId].style.display = "none";
                const downloadLink = document.createElement('a');
                downloadLink.setAttribute("target", "_blank");
                downloadLink.setAttribute("href", "download?download="+response.download_link);
                downloadLink.innerHTML = '<i class="zp zp-download"></i>Download';
                document.getElementsByClassName("control-box")[layoutId].appendChild(downloadLink);
                document.getElementsByClassName("control-box")[layoutId].classList.add("done");

                document.getElementsByClassName("display-image")[layoutId].src = "../wp-content/uploads/heicconverter/" + response.preview_image;
                allConvertedFile.push(response.download_path);
                if (totalImage == index) {
                    document.getElementById("g_drive_btn").style.opacity = "1";
                    document.getElementById("g_drive_btn").style.pointerEvents = "painted";
                } else {
                    document.getElementById("g_drive_btn").style.opacity = "0.5";
                    document.getElementById("g_drive_btn").style.pointerEvents = "none";
                }

                insertRecord(response.download_file, response.convert_type);
            } else {
                console.log('Upload failed!');
            }
        });

        xhr.send(formData);
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    async function loopWithSleep(layoutId) {
        for (let i = 0; i < 100; i++) {
            const item = shouldStop.find(obj => obj.id === layoutId);
            const status = item ? item.status : null;
            if (status) {
                document.getElementsByClassName("upload-progress")[layoutId].style.width = "100%";
                document.getElementsByClassName("progress-parcent")[layoutId].innerHTML = "100%";
                break;
            }
            document.getElementsByClassName("upload-progress")[layoutId].style.width = i + "%";
            document.getElementsByClassName("progress-parcent")[layoutId].innerHTML = i + "%";
            await sleep(40); // Sleep for 1000 milliseconds (1 second)
        }
    }

    function loadImage(url, layoutId) {
        const xhr = new XMLHttpRequest();
        const progressBar = document.getElementById('progress-bar');
        const img = document.getElementById('image');

        xhr.open('GET', url, true);
        xhr.responseType = 'blob';

        xhr.onprogress = function (event) {
            if (event.lengthComputable) {
                const percentComplete = (event.loaded / event.total) * 100;
                document.getElementsByClassName("upload-progress")[layoutId].style.width = percentComplete + "%";
                document.getElementsByClassName("progress-parcent")[layoutId].innerHTML = parseInt(percentComplete) + "%";
            }
        };

        xhr.onload = function () {
            if (this.status === 200) {
                const blob = this.response;
                const imgURL = URL.createObjectURL(blob);
                img.src = imgURL;
                img.style.display = 'block';
            }
        };

        xhr.send();
    }

    function itemClose(data, id) {
        itemsToRemove.push(data);
        document.getElementById("item-model-" + id).remove();
        let totalCount = allConvertedFile.length - itemsToRemove.length;
        document.getElementsByClassName("download-count")[0].innerHTML = totalCount;
        mainInde--;
        index--;
        totalImage--;
        if (totalCount == 0) {
            document.getElementById("g_drive_btn").style.opacity = "0.5";
            document.getElementById("g_drive_btn").style.pointerEvents = "none";
            window.location.href = "/";
        }
    }

    function downloadAll() {
        for (let i = allConvertedFile.length - 1; i >= 0; i--) {
            if (itemsToRemove.includes(allConvertedFile[i])) {
                allConvertedFile.splice(i, 1);
            }
        }

        document.getElementById("g_drive_btn").style.pointerEvents = "none";
        document.getElementById("g_drive_btn").style.opacity = "0.5";

        var data = {
            files: allConvertedFile
        };

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../wp-content/plugins/heicconverter_plugin/ajax/download_multiple_pdf.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.getElementById("g_drive_btn").style.pointerEvents = "painted";
                document.getElementById("g_drive_btn").style.opacity = "1";
                const response = JSON.parse(xhr.responseText);
                var link = document.createElement('a');
                link.setAttribute('href', "https://heicjpgconverter.com/download?download="+ response.download_zip);
                link.setAttribute('download', response.file_name);
                link.click();
            }
        };
        xhr.send(JSON.stringify(data));
    }
    document.getElementById("mobile-nav-btn").onclick = function (e) {

        var nodeList = document.getElementById("nav-menu");
        if (nodeList.style.left == "0px") {
            nodeList.style.left = "-275px";
            e.target.className = "zp zp-menu";
        } else {
            nodeList.style.left = "0px";
            e.target.className = "zp zp-arrow-back";
        }

    };

    function insertRecord(fileName, fileType) {
        const formData = new FormData();
        formData.append('file_name', fileName);
        formData.append('file_type', fileType);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../wp-content/plugins/heicconverter_plugin/ajax/insert_record.php', true);

        xhr.onload = function () {

        };

        xhr.send(formData);
    }

