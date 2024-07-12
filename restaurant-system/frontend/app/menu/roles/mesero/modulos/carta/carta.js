import {apiBase} from "config/config";

document.addEventListener('DOMContentLoaded', function() {
    let url = apiBase.apiBaseUrl;
    let endpoint = '/categories';
    let bus = '/foodForCategory/';

    fetch(`${url}${endpoint}`)
        .then(response => response.json())
        .then(data => {
            const menuContainer = document.getElementById('menu-container');
            const comidasContainer = document.getElementById('comidas-container');

            data.forEach(categoria => {
                const categoriaItem = document.createElement('div');
                categoriaItem.classList.add('categoria-item');
                categoriaItem.innerHTML = `<h3>${categoria.nombre}</h3>`;
                categoriaItem.addEventListener('click', () => {
                    comidasContainer.innerHTML = '';
                    fetch(`${url}${bus}${categoria.id}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(food => {
                                const comidaCard = document.createElement('div');
                                comidaCard.classList.add('comida-card');
                                comidaCard.innerHTML = `
                                    <h3>${food.nombre} - $${food.precio}</h3>
                                    <p>${food.descripcion}</p>
                                `;
                                comidaCard.addEventListener('click', () => {
                                    window.location.href = `http://localhost:8100/app/menu/roles/mesero/modulos/orden/orden.php?foodId=${food.id}`;
                                });
                                comidasContainer.appendChild(comidaCard);
                            })
                        })
                });
                menuContainer.appendChild(categoriaItem);
            });
        })
        .catch(error => console.error('Error al obtener los datos:', error));
});