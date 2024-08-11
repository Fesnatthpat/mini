let imgInput = document.getElementById('imgInput');
        let previewImg = document.getElementById('previewImg');

        imgInput.onchange = evt => {
            const [file] = imgInput.files
            if (file) {
                previewImg.src = URL.createObjectURL(file);
            }
        }