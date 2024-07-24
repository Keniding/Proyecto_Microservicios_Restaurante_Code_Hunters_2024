import { apiBase } from 'config/config';

document.addEventListener('DOMContentLoaded', function () {
    const tagsContainer = document.getElementById('tags-container');
    const selectedTagsContainer = document.getElementById('selected-tags-container');
    let url = apiBase.apiBaseUrl;
    let endpoint = '/modifications';
    let selectedTags = [];

    async function fetchTags() {
        try {
            const response = await fetch(`${url}${endpoint}`);
            const data = await response.json();
            generateTags(data);
        } catch (error) {
            console.error('Error al obtener los datos:', error);
        }
    }

    function generateTags(tags) {
        const selectedTagNames = selectedTags.map(tag => tag.name);

        tagsContainer.innerHTML = '';
        selectedTagsContainer.innerHTML = '';

        const tagsByCategory = {};

        tags.forEach(tag => {
            if (!tagsByCategory[tag.category]) {
                tagsByCategory[tag.category] = [];
            }
            tagsByCategory[tag.category].push(tag);
        });

        Object.keys(tagsByCategory).forEach(category => {
            const categoryButton = document.createElement('button');
            categoryButton.classList.add('category-button');
            categoryButton.textContent = category;

            const categoryTagsContainer = document.createElement('div');
            categoryTagsContainer.classList.add('tags-container');
            categoryTagsContainer.style.display = 'none';

            tagsByCategory[category].forEach(tag => {
                const tagElement = document.createElement('div');
                tagElement.classList.add('tag');
                tagElement.textContent = `${tag.name}`;
                tagElement.dataset.category = tag.category;
                tagElement.dataset.color = tag.color;

                if (selectedTagNames.includes(tag.name)) {
                    tagElement.classList.add('selected');
                    tagElement.style.backgroundColor = tag.color;
                    tagElement.style.color = 'white';
                    addSelectedTag(tag);
                }

                tagElement.addEventListener('click', function () {
                    if (this.classList.contains('selected')) {
                        this.classList.remove('selected');
                        this.style.backgroundColor = '#f0f0f0';
                        this.style.color = 'black';
                        selectedTags = selectedTags.filter(t => !(t.name === tag.name && t.category === tag.category));
                        removeSelectedTag(tag);
                    } else {
                        this.classList.add('selected');
                        this.style.backgroundColor = this.dataset.color;
                        this.style.color = 'white';
                        addSelectedTag(tag);

                        selectedTags.push(tag);
                    }
                    console.log(selectedTags)
                });

                categoryTagsContainer.appendChild(tagElement);
            });

            categoryButton.addEventListener('click', function (event) {
                event.preventDefault();
                if (categoryTagsContainer.style.display === 'none') {
                    categoryTagsContainer.style.display = 'block';
                    createInputAndSaveButton(category, categoryTagsContainer);
                } else {
                    categoryTagsContainer.style.display = 'none';
                    removeInputAndSaveButton(category, categoryTagsContainer);
                }

            });

            tagsContainer.appendChild(categoryButton);
            tagsContainer.appendChild(categoryTagsContainer);
        });
    }

    function addSelectedTag(tag) {
        const selectedTagElement = document.createElement('div');
        selectedTagElement.classList.add('selected-tag');
        selectedTagElement.textContent = tag.name;
        selectedTagElement.dataset.name = tag.name;
        selectedTagElement.dataset.category = tag.category;
        selectedTagElement.style.backgroundColor = tag.color;

        selectedTagsContainer.appendChild(selectedTagElement);
    }

    function removeSelectedTag(tag) {
        const selectedTagElement = selectedTagsContainer.querySelector(`.selected-tag[data-name="${tag.name}"]`);
        if (selectedTagElement) {
            selectedTagsContainer.removeChild(selectedTagElement);
        }
    }

    function createInputAndSaveButton(category, container) {
        if (!document.getElementById(`input-${category}`)) {
            const inputElement = document.createElement('input');
            inputElement.type = 'text';
            inputElement.id = `input-${category}`;
            inputElement.name = `input-${category}`;
            inputElement.placeholder = `Otro ${category}`;
            container.appendChild(inputElement);

            const saveButton = document.createElement('button');
            saveButton.textContent = 'Guardar';
            saveButton.id = `save-button-${category}`;
            saveButton.addEventListener( 'click', function (event) {
                event.preventDefault();
                const data = {
                    name: inputElement.value,
                    category: category
                };
                saveCategory(data);

            });
            container.appendChild(saveButton);
        }
    }

    function saveCategory(data) {
        let endpoint = '/modification';

        fetch(`${url}${endpoint}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(result => {
                console.log('Success:', result);
                fetchTags();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function removeInputAndSaveButton(category, container) {
        const inputElement = document.getElementById(`input-${category}`);
        const saveButton = document.getElementById(`save-button-${category}`);
        if (inputElement) {
            container.removeChild(inputElement);
        }
        if (saveButton) {
            container.removeChild(saveButton);
        }
    }

    fetchTags();

});