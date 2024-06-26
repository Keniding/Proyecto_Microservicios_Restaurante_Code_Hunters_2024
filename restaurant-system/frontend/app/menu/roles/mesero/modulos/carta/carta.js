document.addEventListener('DOMContentLoaded', function() {
    fetch('../../../../../backend/api/modulos/carta/services/get_comidas.php')
        .then(response => response.json())
        .then(data => {
            const menuContainer = document.getElementById('menu-container');
            const comidasContainer = document.getElementById('comidas-container');
            
            data.forEach(categoria => {
                const categoriaItem = document.createElement('div');
                categoriaItem.classList.add('categoria-item');
                categoriaItem.innerHTML = `<h3>${categoria.categoria_nombre}</h3>`;
                
                categoriaItem.addEventListener('click', () => {
                    comidasContainer.innerHTML = '';
                    categoria.comidas.forEach(comida => {
                        const comidaCard = document.createElement('div');
                        comidaCard.classList.add('comida-card');
                        comidaCard.innerHTML = `
                            <h3>${comida.nombre} - $${comida.precio}</h3>
                            <p>${comida.descripcion}</p>
                        `;
                        comidaCard.addEventListener('click', () => {
                            window.location.href = `order.php?comida_id=${comida.id}`;
                        });
                        comidasContainer.appendChild(comidaCard);
                    });
                });

                menuContainer.appendChild(categoriaItem);
            });
        })
        .catch(error => console.error('Error al obtener los datos:', error));
});