const imageInput = document.getElementById('image');
const uploadLabel = document.getElementById('upload-label');
const previewContainer = document.getElementById('preview-container');
const previewImage = document.getElementById('preview-image');
const removeBtn = document.getElementById('remove-btn');

// Файл таңдалғанда орындайтын оқиға
imageInput.addEventListener('change', function (event) {
    const file = event.target.files[0]; // Тек БІРІНШІ файлды аламыз

    if (file) {
        // Файл көлемін тексеру (2 МБ = 2 * 1024 * 1024 байт)
        if (file.size > 2 * 1024 * 1024) {
            alert('Файл көлемі 2 МБ-тан аспауы керек!');
            imageInput.value = ''; // Файлды тазалау
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            // Суреттің URL мекенжайын орнату
            previewImage.src = e.target.result;
            
            // Жүктеу блогын жасырып, суретті көрсету
            uploadLabel.classList.add('hidden');
            previewContainer.classList.remove('hidden');
        };

        reader.readAsDataURL(file);
    }
});

// Суретті өшіру батырмасын басу
removeBtn.addEventListener('click', function () {
    imageInput.value = ''; // Файлды өшіру
    previewImage.src = '';
    
    // Суретті жасырып, қайта жүктеу блогын көрсету
    previewContainer.classList.add('hidden');
    uploadLabel.classList.remove('hidden');
});